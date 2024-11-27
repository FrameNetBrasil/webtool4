<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\Deixis\CreateObjectData;
use App\Data\Annotation\Deixis\DocumentData;
use App\Data\Annotation\Deixis\ObjectAnnotationData;
use App\Data\Annotation\Deixis\ObjectFrameData;
use App\Data\Annotation\Deixis\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Video;
use App\Services\AnnotationDeixisService;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;


#[Middleware(name: 'auth')]
class DeixisController extends Controller
{
    #[Get(path: '/annotation/deixis')]
    public function browse()
    {
        $search = session('searchCorpus') ?? SearchData::from();
        return view("Annotation.Deixis.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/annotation/deixis/grid')]
    public function grid(SearchData $search)
    {
        return view("Annotation.Deixis.grid", [
            'search' => $search
        ]);
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

    #[Get(path: '/annotation/deixis/{idDocument}')]
    public function annotation(int $idDocument)
    {
        $data = $this->getData($idDocument);
        debug($data);
        return view("Annotation.Deixis.annotation", $data->toArray());
    }

    #[Post(path: '/annotation/deixis/createNewObjectAtLayer')]
    public function createNewObjectAtLayer(CreateObjectData $data)
    {
        debug($data);
        try {
            return AnnotationDeixisService::createNewObjectAtLayer($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }
    #[Post(path: '/annotation/deixis/formAnnotation')]
    public function formAnnotation(ObjectData $data)
    {
        debug($data);
        $object = AnnotationDeixisService::getObject($data->idDynamicObject ?? 0);
        return view("Annotation.Deixis.Panes.formPane", [
            'order' => $data->order,
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/deixis/formAnnotation/{idDynamicObject}')]
    public function getFormAnnotation(int $idDynamicObject)
    {
        $object = AnnotationDeixisService::getObject($idDynamicObject ?? 0);
        return view("Annotation.Deixis.Panes.formAnnotation", [
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/deixis/gridObjects/{idDocument}')]
    public function gridObjects(int $idDocument)
    {
        return AnnotationDeixisService::getObjectsByDocument($idDocument);
    }

    #[Get(path: '/annotation/deixis/loadLayerList/{idDocument}')]
    public function loadLayerList(int $idDocument)
    {
        return AnnotationDeixisService::getLayersByDocument($idDocument);
    }

    #[Post(path: '/annotation/deixis/updateObject')]
    public function updateObject(ObjectData $data)
    {
        debug($data);
        try {
            $idDynamicObject = AnnotationDeixisService::updateObject($data);
            return Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/updateObjectFrame')]
    public function updateObjectFrame(ObjectFrameData $data)
    {
        debug("========= updateObjectFrame", $data);
        try {
            return AnnotationDeixisService::updateObjectFrame($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/updateObjectAnnotation')]
    public function updateObjectAnnotation(ObjectAnnotationData $data)
    {
        debug($data);
        try {
            return AnnotationDeixisService::updateObjectAnnotation($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/cloneObject')]
    public function cloneObject(CloneData $data)
    {
        debug($data);
        try {
            $idDynamicObject = AnnotationDynamicService::cloneObject($data);
            return Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/deixis/{idDynamicObject}')]
    public function deleteObject(int $idDynamicObject)
    {
        try {
            AnnotationDeixisService::deleteObject($idDynamicObject);
            return $this->renderNotify("success", "Object removed.");
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/deixis/updateBBox')]
    public function updateBBox(UpdateBBoxData $data)
    {
        try {
            debug($data);
            $idBoundingBox = AnnotationDynamicService::updateBBox($data);
            return Criteria::byId("boundingbox", "idBoundingBox", $idBoundingBox);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/annotation/deixis/fes/{idFrame}')]
    public function feCombobox(int $idFrame)
    {
        return view("Annotation.Deixis.Panes.fes", [
            'idFrame' => $idFrame
        ]);
    }


}
