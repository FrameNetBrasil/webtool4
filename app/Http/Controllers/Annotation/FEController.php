<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\FE\AnnotationData;
use App\Data\Annotation\Corpus\CreateASData;
use App\Data\Annotation\FE\DeleteFEData;
use App\Data\Annotation\Browse\SearchData;
use App\Data\Annotation\FE\SelectionData;
use App\Data\Comment\CommentData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\AnnotationSet;
use App\Services\Annotation\BrowseService;
use App\Services\Annotation\CorpusService;
use App\Services\AnnotationFEService;
use App\Services\CommentService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("auth")]
class FEController extends Controller
{
//    #[Get(path: '/annotation/fe/script/{folder}')]
//    public function jsObjects(string $folder)
//    {
//        return response()
//            ->view("Annotation.FE.Scripts.{$folder}")
//            ->header('Content-type', 'text/javascript');
//    }
    #[Get(path: '/annotation/fe')]
    public function browse(SearchData $search)
    {
        $data = BrowseService::browseCorpusBySearch($search);

        return view('Annotation.browseSentences', [
            'page' => "FE Annotation",
            'url' => "/annotation/fe/sentence",
            'data' => $data,
        ]);
    }

    #[Get(path: '/annotation/fe/sentence/{idDocumentSentence}/{idAnnotationSet?}')]
    public function annotation(int $idDocumentSentence,int $idAnnotationSet = null)
    {
        $data = CorpusService::getResourceData($idDocumentSentence, $idAnnotationSet);
        return view("Annotation.FE.annotation", $data);
    }

    #[Get(path: '/annotation/fe/as/{idAS}/{token?}')]
    public function annotationSet(int $idAS, string $token = '')
    {
        $data = CorpusService::getAnnotationSetData($idAS, $token);
        return view('Annotation.FE.Panes.annotationSet', $data);
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
            $input->range = SelectionData::from($input->selection);
            if ($input->range->end < $input->range->start) {
                throw new \Exception("Wrong selection.");
            }
            if ($input->range->type != '') {
                $data = AnnotationFEService::annotateFE($input);
                return view("Annotation.FE.Panes.asAnnotation", $data);
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
            $data = CorpusService::getAnnotationSetData($data->idAnnotationSet, $data->token);
            return view("Annotation.FE.Panes.asAnnotation", $data);
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/fe/createAS')]
    public function createAS(CreateASData $input)
    {
        $idAnnotationSet = CorpusService::createAnnotationSet($input);
        if (is_null($idAnnotationSet)) {
            return $this->renderNotify('error', 'Error creating AnnotationSet.');
        } else {
            return $this->clientRedirect("/annotation/fe/sentence/{$input->idDocumentSentence}/{$idAnnotationSet}");
        }
    }

    #[Delete(path: '/annotation/fe/annotationset/{idAnnotationSet}')]
    public function deleteAS(int $idAnnotationSet)
    {
        try {
            $annotationSet = Criteria::byId("view_annotationset","idAnnotationSet", $idAnnotationSet);
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

