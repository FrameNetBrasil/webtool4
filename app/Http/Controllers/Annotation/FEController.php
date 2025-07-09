<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\FE\AnnotationData;
use App\Data\Annotation\FE\CreateASData;
use App\Data\Annotation\FE\DeleteFEData;
use App\Data\Annotation\FE\SearchData;
use App\Data\Annotation\FE\SelectionData;
use App\Data\Comment\CommentData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\AnnotationSet;
use App\Repositories\Document;
use App\Repositories\WordForm;
use App\Services\AnnotationFEService;
use App\Services\AnnotationService;
use App\Services\CommentService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("auth")]
class FEController extends Controller
{
    #[Get(path: '/annotation/fe')]
    public function browse()
    {
        $search = session('searchFEAnnotation') ?? SearchData::from();
        $corpus = AnnotationService::browseCorpusBySearch($search);
        return view("Annotation.FE.browse", [
            'corpus' => $corpus,
        ]);
    }

    #[Get(path: '/annotation/fe/tree/{type?}/{id?}')]
    public function tree(string $type = null, int $id = null)
    {
        debug($type);
        debug($id);
        $search = session('searchFEAnnotation') ?? SearchData::from();
        if (is_null($type)) {
            $data = AnnotationService::browseCorpusBySearch($search);
//        debug($corpus);
        } else if ($type == 'corpus') {
            $search->idCorpus = $id;
            $data = AnnotationService::browseDocumentBySearch($search);
        } else if ($type == 'document') {
            $data = AnnotationService::browseSentences(AnnotationFEService::listSentences($id));
        }
        return view("Annotation.FE.tree", [
            'data' => $data
        ]);
    }

    #[Post(path: '/annotation/fe/grid')]
    public function grid(SearchData $search)
    {
        return view("Annotation.FE.grids", [
            'search' => $search,
            'sentences' => [],
        ]);
    }

    #[Get(path: '/annotation/fe/grid/{idDocument}/sentences')]
    public function documentSentences(int $idDocument)
    {
        $document = Document::byId($idDocument);
        $sentences = AnnotationFEService::listSentences($idDocument);
        return view("Annotation.FE.sentences", [
            'document' => $document,
            'sentences' => $sentences
        ]);
    }

    #[Get(path: '/annotation/fe/sentence/{idDocumentSentence}/{idAnnotationSet?}')]
    public function sentence(int $idDocumentSentence, int $idAnnotationSet = null)
    {
        $data = AnnotationFEService::getAnnotationData($idDocumentSentence);
        if (!is_null($idAnnotationSet)) {
            $data['idAnnotationSet'] = $idAnnotationSet;
        }
        return view("Annotation.FE.annotationSentence", $data);
    }

    #[Get(path: '/annotation/fe/annotations/{idSentence}')]
    public function annotations(int $idSentence)
    {
        $data = AnnotationFEService::getAnnotationData($idSentence);
        return view("Annotation.FE.Panes.annotations", $data);
    }

    #[Get(path: '/annotation/fe/as/{idAS}/{token}')]
    public function annotationSet(int $idAS, string $token)
    {
        $data = AnnotationFEService::getASData($idAS, $token);
        debug($data);
        return view("Annotation.FE.Panes.annotationSet", $data);
    }

    #[Get(path: '/annotation/fe/lus/{idDocumentSentence}/{idWord}')]
    public function getLUs(int $idDocumentSentence, int $idWord)
    {
        $data = AnnotationFEService::getLUs($idDocumentSentence, $idWord);
        $data['idWord'] = $idWord;
        $data['idDocumentSentence'] = $idDocumentSentence;
        return view("Annotation.FE.Panes.lus", $data);
    }

    #[Post(path: '/annotation/fe/annotate')]
    public function annotate(AnnotationData $input)
    {
        try {
            $input->range = SelectionData::from(request("selection"));
            if ($input->range->end < $input->range->start) {
                throw new \Exception("Wrong selection.");
            }
            if ($input->range->type != '') {
                $data = AnnotationFEService::annotateFE($input);
                //$data['alternativeLU'] = [];
//                debug("#######################################################");

//                $this->trigger('reload-annotationSet');
//                $this->trigger('reload-annotationSet');
                return view("Annotation.FE.Panes.annotationSet", $data);
//                return $input->idAnnotationSet;
            } else {
                return $this->renderNotify("error", "No selection.");
            }
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/fe/frameElement')]
    public function deleteFE(DeleteFEData $data)
    {
        try {
            AnnotationFEService::deleteFE($data);
            $data = AnnotationFEService::getASData($data->idAnnotationSet, $data->token);
            debug("--------------------------------------------------------");
            //$data['alternativeLU'] = [];
            return view("Annotation.FE.Panes.annotationSet", $data);
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/fe/create')]
    public function createAS(CreateASData $input)
    {
        $idAnnotationSet = AnnotationFEService::createAnnotationSet($input);
        if (is_null($idAnnotationSet)) {
            return $this->renderNotify("error", "Error creating AnnotationSet.");
        } else {
            //$data = AnnotationFEService::getASData($idAnnotationSet);
//            $this->trigger('reload-sentence');
//            return view("Annotation.FE.Panes.annotationSet", $data);
            return $this->clientRedirect("/annotation/fe/sentence/{$input->idDocumentSentence}/{$idAnnotationSet}");

        }
    }

    #[Delete(path: '/annotation/fe/annotationset/{idAnnotationSet}')]
    public function deleteAS(int $idAnnotationSet)
    {
        try {
            $annotationSet = Criteria::byId("view_annotationset", "idAnnotationSet", $idAnnotationSet);
            AnnotationSet::delete($idAnnotationSet);
            return $this->clientRedirect("/annotation/fe/sentence/{$annotationSet->idDocumentSentence}");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    /*
     * Comment
     */

    #[Get(path: '/annotation/fe/formComment/{idAnnotationSet}')]
    public function getFormComment(int $idAnnotationSet)
    {
        $object = CommentService::getAnnotationSetComment($idAnnotationSet);
        return view("Annotation.FE.Panes.formComment", [
            'object' => $object
        ]);
    }

    #[Post(path: '/annotation/fe/updateObjectComment')]
    public function updateObjectComment(CommentData $data)
    {
        try {
            debug($data);
            CommentService::updateAnnotationSetComment($data);
            $this->trigger('reload-annotationSet');
            return $this->renderNotify("success", "Comment registered.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/fe/comment/{idAnnotationSet}')]
    public function deleteObjectComment(int $idAnnotationSet)
    {
        try {
            CommentService::deleteAnnotationSetComment($idAnnotationSet);
            $this->trigger('reload-annotationSet');
            return $this->renderNotify("success", "Object comment removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


}

