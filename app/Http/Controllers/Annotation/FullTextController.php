<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\FullText\AnnotationData;
use App\Data\Annotation\FullText\CreateASData;
use App\Data\Annotation\FullText\DeleteLabelData;
use App\Data\Annotation\FullText\SearchData;
use App\Data\Annotation\FullText\SelectionData;
use App\Data\Comment\CommentData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\AnnotationSet;
use App\Repositories\Document;
use App\Services\Annotation\BrowseService;
use App\Services\Annotation\FullTextService;
use App\Services\CommentService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("auth")]
class FullTextController extends Controller
{
    #[Get(path: '/annotation/fullText/script/{folder}')]
    public function jsObjects(string $folder)
    {
        return response()
            ->view("Annotation.FullText.Scripts.{$folder}")
            ->header('Content-type', 'text/javascript');
    }

    #[Get(path: '/annotation/fullText')]
    public function browse(SearchData $search)
    {
        $corpus = BrowseService::browseCorpusBySearch($search);
        return view("Annotation.FullText.browse", [
            'data' => $corpus,
        ]);
    }

    #[Post(path: '/annotation/fullText/tree')]
    public function tree(SearchData $search)
    {
        if (!is_null($search->idDocumentSentence)) {
            $data = BrowseService::browseSentences(FullTextService::getSentence($search->idDocumentSentence));
        } else {
            if (!is_null($search->idDocument)) {
                $data = BrowseService::browseSentences(FullTextService::listSentences($search->idDocument));
            } else {
                if (!is_null($search->idCorpus) || ($search->document != '')) {
                    $data = BrowseService::browseDocumentBySearch($search);
                } else {
                    $data = BrowseService::browseCorpusBySearch($search);
                }
            }
        }
        return view("Annotation.FullText.browse", [
            'data' => $data
        ])->fragment("tree");
    }

    #[Get(path: '/annotation/fullText/sentence/{idDocumentSentence}/{idAnnotationSet?}')]
    public function sentence(int $idDocumentSentence, int $idAnnotationSet = null)
    {
        $data = FullTextService::getAnnotationData($idDocumentSentence,$idAnnotationSet);
        return view("Annotation.FE.annotation", $data);
    }

//    #[Get(path: '/annotation/fullText/{idDocument?}')]
//    public function browse(int $idDocument = null)
//    {
//        $search = session('searchFEAnnotation') ?? SearchData::from();
//        return view("Annotation.FullText.browse", [
//            'idDocument' => $idDocument,
//            'search' => $search
//        ]);
//    }
//
//    #[Post(path: '/annotation/fullText/grid/{idDocument?}')]
//    public function grid(SearchData $search, int $idDocument = null)
//    {
//        $sentences = [];
//        $document = null;
//        if (!is_null($idDocument)) {
//            $document = Document::byId($idDocument);
//            $sentences = FullTextService::listSentences($idDocument);
//        }
//        return view("Annotation.FullText.grids", [
//            'idDocument' => $idDocument,
//            'search' => $search,
//            'document' => $document,
//            'sentences' => $sentences,
//        ]);
//    }
//
//    #[Get(path: '/annotation/fullText/grid/{idDocument}/sentences')]
//    public function documentSentences(int $idDocument)
//    {
//        $document = Document::byId($idDocument);
//        $sentences = FullTextService::listSentences($idDocument);
//        return view("Annotation.FullText.sentences", [
//            'document' => $document,
//            'sentences' => $sentences
//        ]);
//    }
//
//    #[Get(path: '/annotation/fullText/sentence/{idDocumentSentence}/{idAnnotationSet?}')]
//    public function sentence(int $idDocumentSentence,int $idAnnotationSet = null)
//    {
//        $data = FullTextService::getAnnotationData($idDocumentSentence);
//        if (!is_null($idAnnotationSet)) {
//            $data['idAnnotationSet'] = $idAnnotationSet;
//        }
//        return view("Annotation.FullText.annotationSentence", $data);
//    }
//
//    #[Get(path: '/annotation/fullText/annotations/{idSentence}')]
//    public function annotations(int $idSentence)
//    {
//        $data = FullTextService::getAnnotationData($idSentence);
//        return view("Annotation.FullText.Panes.annotations", $data);
//    }
//
//    #[Get(path: '/annotation/fullText/spans/{idAS}')]
//    public function getSpans(int $idAS)
//    {
//        return FullTextService::getSpans($idAS);
//    }
//
//    #[Get(path: '/annotation/fullText/as/{idAS}/{token}')]
//    public function annotationSet(int $idAS, string $token)
//    {
//        $data = FullTextService::getASData($idAS, $token);
//        return view("Annotation.FullText.Panes.annotationSet", $data);
//    }
//
//    #[Get(path: '/annotation/fullText/lus/{idDocumentSentence}/{idWord}')]
//    public function getLUs(int $idDocumentSentence, int $idWord)
//    {
//        $data = FullTextService::getLUs($idDocumentSentence, $idWord);
//        $data['idWord'] = $idWord;
//        $data['idDocumentSentence'] = $idDocumentSentence;
//        return view("Annotation.FullText.Panes.lus", $data);
//    }
//
//    #[Post(path: '/annotation/fullText/annotate')]
//    public function annotate(AnnotationData $input)
//    {
//        try {
//            //debug($input);
//            //debug(request("selection"));
//            $input->range = SelectionData::from(request("selection"));
//            if ($input->range->end < $input->range->start) {
//                throw new \Exception("Wrong selection.");
//            }
//            if ($input->range->type != '') {
//                FullTextService::annotateEntity($input);
//                return $input->idAnnotationSet;
//            } else {
//                throw new \Exception("No selection.");
//            }
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
//
//    #[Delete(path: '/annotation/fullText/label')]
//    public function deleteFE(DeleteLabelData $data)
//    {
//        try {
//            FullTextService::deleteLabel($data);
//            //AnnotationFullTextService::getASData($data->idAnnotationSet);
//            //return view("Annotation.FullText.Panes.annotationSet", $data);
//            return $data->idAnnotationSet;
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
//
//    #[Post(path: '/annotation/fullText/create')]
//    public function createAS(CreateASData $input)
//    {
//        $idAnnotationSet = FullTextService::createAnnotationSet($input);
//        if (is_null($idAnnotationSet)) {
//            return $this->renderNotify("error", "Error creating AnnotationSet.");
//        } else {
//            $data = FullTextService::getASData($idAnnotationSet);
//            $this->trigger('reload-sentence');
//            return view("Annotation.FullText.Panes.annotationSet", $data);
////            return response()
////                ->view("Annotation.FullText.Panes.annotationSet", $data)
////                ->header('HX-Trigger', ');
//
//        }
//    }
//
//    #[Delete(path: '/annotation/fullText/annotationset/{idAnnotationSet}')]
//    public function deleteAS(int $idAnnotationSet)
//    {
//        try {
//            $annotationSet = Criteria::byId("view_annotationset", "idAnnotationSet", $idAnnotationSet);
//            AnnotationSet::delete($idAnnotationSet);
//            return $this->clientRedirect("/annotation/fullText/sentence/{$annotationSet->idDocumentSentence}");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
//
//    /*
//     * Comment
//     */
//
//    #[Get(path: '/annotation/fullText/formComment/{idAnnotationSet}')]
//    public function getFormComment(int $idAnnotationSet)
//    {
//        $object = CommentService::getAnnotationSetComment($idAnnotationSet);
//        return view("Annotation.FullText.Panes.formComment", [
//            'object' => $object
//        ]);
//    }
//    #[Post(path: '/annotation/fullText/updateObjectComment')]
//    public function updateObjectComment(CommentData $data)
//    {
//        try {
//            debug($data);
//            CommentService::updateAnnotationSetComment($data);
//            $this->trigger('reload-annotationSet');
//            return $this->renderNotify("success", "Comment registered.");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
//    #[Delete(path: '/annotation/fullText/comment/{idAnnotationSet}')]
//    public function deleteObjectComment(int $idAnnotationSet)
//    {
//        try {
//            CommentService::deleteAnnotationSetComment($idAnnotationSet);
//            $this->trigger('reload-annotationSet');
//            return $this->renderNotify("success", "Object comment removed.");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }

}

