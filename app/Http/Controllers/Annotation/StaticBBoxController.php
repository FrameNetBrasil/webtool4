<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\StaticBBox\AnnotationCommentData;
use App\Data\Annotation\StaticBBox\CloneData;
use App\Data\Annotation\StaticBBox\DocumentData;
use App\Data\Annotation\StaticBBox\ObjectAnnotationData;
use App\Data\Annotation\StaticBBox\ObjectData;
use App\Data\Annotation\StaticBBox\SearchData;
use App\Data\Annotation\StaticBBox\UpdateBBoxData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Image;
use App\Services\AnnotationStaticBBoxService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;


#[Middleware(name: 'auth')]
class StaticBBoxController extends Controller
{
    #[Get(path: '/annotation/staticBBox')]
    public function browse()
    {
        $search = session('searchCorpus') ?? SearchData::from();
        return view("Annotation.StaticBBox.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/annotation/staticBBox/grid')]
    public function grid(SearchData $search)
    {
        return view("Annotation.StaticBBox.grid", [
            'search' => $search
        ]);
    }

    private function getData(int $idDocument): DocumentData
    {
        $document = Document::byId($idDocument);
        $corpus = Corpus::byId($document->idCorpus);
        $documentImage = Criteria::table("view_document_image")
            ->where("idDocument", $idDocument)
            ->first();
        $image = Image::byId($documentImage->idImage);
        $comment = Criteria::byFilter("annotationcomment", ["id1", "=", $documentImage->idDocumentImage])->first();
        return DocumentData::from([
            'idDocument' => $idDocument,
            'idDocumentImage' => $documentImage->idDocumentImage,
            'document' => $document,
            'corpus' => $corpus,
            'image' => $image,
            'fragment' => 'fe',
            'comment' => $comment->comment ?? '',
            'idPrevious' => AnnotationStaticBBoxService::getPrevious($document),
            'idNext' => AnnotationStaticBBoxService::getNext($document),
        ]);
    }

    #[Get(path: '/annotation/staticBBox/{idDocument}')]
    public function annotation(int $idDocument)
    {
        $data = $this->getData($idDocument);
        return view("Annotation.StaticBBox.annotation", $data->toArray());
    }

    #[Post(path: '/annotation/staticBBox/formObject')]
    public function formObject(ObjectData $data)
    {
        debug($data);
        $object = AnnotationStaticBBoxService::getObject($data->idStaticObject ?? 0);
        return view("Annotation.StaticBBox.Panes.formPane", [
            'order' => $data->order,
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/staticBBox/formObject/{idDynamicObject}/{order}')]
    public function getFormObject(int $idStaticObject, int $order)
    {
        $object = AnnotationStaticBBoxService::getObject($idStaticObject ?? 0);
        return view("Annotation.StaticBBox.Panes.formPane", [
            'order' => $order,
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/staticBBox/gridObjects/{idDocument}')]
    public function objectsForGrid(int $idDocument)
    {
        return AnnotationStaticBBoxService::getObjectsByDocument($idDocument);
    }

    #[Post(path: '/annotation/staticBBox/updateObject')]
    public function updateObject(ObjectData $data)
    {
        debug($data);
        try {
            $idStaticObject = AnnotationStaticBBoxService::updateObject($data);
            return Criteria::byId("staticobject", "idStaticObject", $idStaticObject);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/staticBBox/updateObjectAnnotation')]
    public function updateObjectAnnotation(ObjectAnnotationData $data)
    {
        debug($data);
        try {
            $idStaticObject = AnnotationStaticBBoxService::updateObjectAnnotation($data);
            return Criteria::byId("staticobject", "idStaticObject", $idStaticObject);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/staticBBox/cloneObject')]
    public function cloneObject(CloneData $data)
    {
        debug($data);
        try {
            $idStaticObject = AnnotationStaticBBoxService::cloneObject($data);
            return Criteria::byId("staticobject", "idStaticObject", $idStaticObject);
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/staticBBox/{idStaticObject}')]
    public function deleteObject(int $idStaticObject)
    {
        try {
            AnnotationStaticBBoxService::deleteObject($idStaticObject);
            return $this->renderNotify("success", "Object removed.");
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/staticBBox/updateBBox')]
    public function updateBBox(UpdateBBoxData $data)
    {
        try {
            debug($data);
            $idBoundingBox = AnnotationStaticBBoxService::updateBBox($data);
            return Criteria::byId("dynamicobject", "idDynamicObject", $data->idStaticObject);
            //return Criteria::byId("boundingbox", "idBoundingBox", $idBoundingBox);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/annotation/staticBBox/fes/{idFrame}')]
    public function feCombobox(int $idFrame)
    {
        return view("Annotation.StaticBBox.Panes.fes", [
            'idFrame' => $idFrame
        ]);
    }

    #[Get(path: '/annotation/staticBBox/sentences/{idDocument}')]
    public function gridSentences(int $idDocument)
    {
        $sentences = AnnotationStaticBBoxService::listSentencesByDocument($idDocument);
        return view("Annotation.StaticBBox.Panes.sentences", [
            'sentences' => $sentences
        ]);
    }

    #[Post(path: '/annotation/staticBBox/comment')]
    public function annotationComment(AnnotationCommentData $data)
    {
        debug($data);
        try {
            $comment = Criteria::byFilter("annotationcomment", ["id1", "=", $data->idDocumentVideo])->first();
            if (!is_null($comment)) {
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
