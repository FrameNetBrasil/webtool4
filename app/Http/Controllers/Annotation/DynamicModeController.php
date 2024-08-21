<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\DynamicMode\AnnotationCommentData;
use App\Data\Annotation\DynamicMode\DocumentData;
use App\Data\Annotation\DynamicMode\ObjectData;
use App\Data\Annotation\DynamicMode\SearchData;
use App\Data\Annotation\DynamicMode\UpdateBBoxData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Video;
use App\Services\AnnotationDynamicService;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;


#[Middleware(name: 'auth')]
class DynamicModeController extends Controller
{
    #[Get(path: '/annotation/dynamicMode')]
    public function browse()
    {
        $search = session('searchCorpus') ?? SearchData::from();
        return view("Annotation.DynamicMode.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/annotation/dynamicMode/grid')]
    public function grid(SearchData $search)
    {
        return view("Annotation.DynamicMode.grid", [
            'search' => $search
        ]);
    }

    #[Get(path: '/annotation/dynamicMode/objectFE/{idFrame}')]
    public function objectFE($idFrame)
    {
        $frame = new Frame($idFrame);
        data('idFrame', $idFrame);
        data('frameName', $frame->name ?? '');
        return $this->render("Annotation.DynamicMode.Panes.objectFEPane");
    }

    private function getData(int $idDocument): DocumentData
    {
        $idLanguage = AppService::getCurrentIdLanguage();

        $document = Document::byId($idDocument);
        $corpus = Corpus::byId($document->idCorpus);

        $documentVideo = Criteria::table("view_document_video")
            ->where("idDocument", $idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        $comment = Criteria::byFilter("annotationcomment", ["id1", "=", $documentVideo->idDocumentVideo])->first();

//        $annotation =  AnnotationStaticEventService::getObjectsForAnnotationImage($document->idDocument, $sentence->idSentence);
        return DocumentData::from([
            'idDocument' => $idDocument,
            'idDocumentVideo' => $documentVideo->idDocumentVideo,
//            'idPrevious' => AnnotationStaticEventService::getPrevious($document->idDocument,$idDocumentSentence),
//            'idNext' => AnnotationStaticEventService::getNext($document->idDocument,$idDocumentSentence),
            'document' => $document,
            'corpus' => $corpus,
            'video' => $video,
//            'objects' => $annotation['objects'],
//            'frames' => $annotation['frames'],
//            'type' => $annotation['type'],
            'fragment' => 'fe',
            'comment' => $comment->comment ?? ''
        ]);
    }

    #[Get(path: '/annotation/dynamicMode/{idDocument}')]
    public function annotation(int $idDocument)
    {
        $data = $this->getData($idDocument);
        debug($data);
        return view("Annotation.DynamicMode.annotation", $data->toArray());
    }

    #[Get(path: '/annotation/dynamicMode/gridObjects/{idDocument}')]
    public function objectsForGrid(int $idDocument)
    {
        return AnnotationDynamicService::getObjectsByDocument($idDocument);
    }
    #[Post(path: '/annotation/dynamicMode/updateObject')]
    public function updateObject(ObjectData $data)
    {
        debug($data);
        try {
            $idDynamicObject = AnnotationDynamicService::updateObject($data);
            return Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/dynamicMode/{idDynamicObjectMM}')]
    public function deleteObjectObject(int $idDynamicObjectMM)
    {
        try {
            $dynamicObjectMM = new DynamicObjectMM($idDynamicObjectMM);
            $dynamicObjectMM->delete();
            return [];
//            $this->renderJSon(json_encode(['type' => 'success', 'message' => 'Object saved.', 'data' => $result]));
        } catch (\Exception $e) {
//            $this->renderJSon(json_encode(['type' => 'error', 'message' => $e->getMessage()]));
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateBBox')]
    public function updateBBox(UpdateBBoxData $data)
    {
        try {
            debug($data);
            $idBoundingBox = AnnotationDynamicService::updateBBox($data);
            return Criteria::byId("boundingbox", "idBoundingBox", $idBoundingBox);

//            $dynamicBBoxMM = new DynamicBBoxMM(data('idDynamicBBoxMM'));
//            $dynamicBBoxMM->updateBBox(data('bbox'));
//            return $dynamicBBoxMM->getData();
//            $this->renderJSon(json_encode(['type' => 'success', 'message' => 'Object saved.', 'data' => $result]));
        } catch (\Exception $e) {
            debug($e->getMessage());
//            $this->renderJSon(json_encode(['type' => 'error', 'message' => $e->getMessage()]));
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/annotation/dynamicMode/fes/{idFrame}')]
    public function feCombobox(int $idFrame)
    {
        return view("Annotation.DynamicMode.Panes.fes", [
            'idFrame' => $idFrame
        ]);
    }

    #[Get(path: '/annotation/dynamicMode/gridSentences/{idDocument}')]
    public function gridSentences(int $idDocument)
    {
        return (array)AnnotationDynamicService::listSentencesByDocument($idDocument);
    }
    #[Post(path: '/annotation/dynamicMode/comment')]
    public function annotationComment(AnnotationCommentData $data)
    {
        debug($data);
        try {
            $comment = Criteria::byFilter("annotationcomment", ["id1", "=", $data->idDocumentVideo])->first();
            if ($comment->idAnnotationComment) {
                Criteria::table("annotationcomment")
                    ->where("idAnnotationComment", "=", $comment->idAnnotationComment)
                    ->update([
                        "type" => "StaticEvent",
                        "id1" => $data->idDocumentVideo,
                        "comment" => $data->comment
                    ]);
            } else {
                Criteria::table("annotationcomment")
                    ->insert([
                        "type" => "StaticEvent",
                        "id1" => $data->idDocumentVideo,
                        "comment" => $data->comment
                    ]);
            }
            return $this->renderNotify("success", "Comment added.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
