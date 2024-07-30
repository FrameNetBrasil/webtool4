<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\DynamicMode\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Video;
use App\Services\AnnotationDynamicService;
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
        return view("Panes.DynamicMode.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/annotation/dynamicMode/grid')]
    public function grid(SearchData $search)
    {
        return view("Panes.DynamicMode.grid", [
            'search' => $search
        ]);
    }

    #[Get(path: '/annotation/dynamicMode/objectFE/{idFrame}')]
    public function objectFE($idFrame)
    {
        $frame = new Frame($idFrame);
        data('idFrame', $idFrame);
        data('frameName', $frame->name ?? '');
        return $this->render("Panes.DynamicMode.Panes.objectFEPane");
    }

    #[Get(path: '/annotation/dynamicMode/{idDocument}')]
    public function annotation(int $idDocument)
    {
        $document = Document::byId($idDocument);
        $document->corpus = Corpus::byId($document->idCorpus);
        $documentVideo = Criteria::table("view_document_video")
            ->where("idDocument", $idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        return view("Annotation.DynamicMode.annotation", [
            'document' => $document,
            'video' => $video,
            'objects' => [],
            'fragment' => 'fe'
        ]);
    }

    #[Get(path: '/annotation/dynamicMode/gridObjects/{idDocument}')]
    public function gridObjects(int $idDocument)
    {
        return AnnotationDynamicService::getObjectsByDocument($idDocument);
    }

    #[Post(path: '/annotation/dynamicMode/updateObject')]
    public function updateObject()
    {
        debug($this->data);
        try {
            $dynamicObjectMM = new DynamicObjectMM();
            $dynamicObjectMM->updateObject($this->data);
            return $dynamicObjectMM->getData();
//            $this->renderJSon(json_encode(['type' => 'success', 'message' => 'Object saved.', 'data' => $result]));
        } catch (\Exception $e) {
            debug($e->getMessage());
//            $this->renderJSon(json_encode(['type' => 'error', 'message' => $e->getMessage()]));
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
    public function updateBBox()
    {
        try {
            debug($this->data);
            $dynamicBBoxMM = new DynamicBBoxMM(data('idDynamicBBoxMM'));
            $dynamicBBoxMM->updateBBox(data('bbox'));
            return $dynamicBBoxMM->getData();
//            $this->renderJSon(json_encode(['type' => 'success', 'message' => 'Object saved.', 'data' => $result]));
        } catch (\Exception $e) {
            debug($e->getMessage());
//            $this->renderJSon(json_encode(['type' => 'error', 'message' => $e->getMessage()]));
        }
    }

    #[Get(path: '/annotation/dynamicMode/gridSentences/{idDocument}')]
    public function gridSentences(int $idDocument)
    {
        return (array)AnnotationDynamicService::listSentencesByDocument($idDocument);
    }
}
