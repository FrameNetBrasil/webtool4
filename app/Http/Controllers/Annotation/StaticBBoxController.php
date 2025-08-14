<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\StaticBBox\AnnotationCommentData;
use App\Data\Annotation\StaticBBox\CloneData;
use App\Data\Annotation\StaticBBox\DocumentData;
use App\Data\Annotation\StaticBBox\ObjectAnnotationData;
use App\Data\Annotation\StaticBBox\ObjectData;
use App\Data\Annotation\StaticBBox\SearchData;
use App\Data\Annotation\StaticBBox\UpdateBBoxData;
use App\Data\Comment\CommentData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Image;
use App\Services\Annotation\BrowseService;
use App\Services\AnnotationStaticBBoxService;
use App\Services\CommentService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;


#[Middleware(name: 'auth')]
class StaticBBoxController extends Controller
{
//    #[Get(path: '/annotation/staticBBox')]
//    public function browse()
//    {
//        $search = session('searchCorpus') ?? SearchData::from();
//        return view("Annotation.StaticBBox.browse", [
//            'search' => $search
//        ]);
//    }
//
//    #[Post(path: '/annotation/staticBBox/grid')]
//    public function grid(SearchData $search)
//    {
//        return view("Annotation.StaticBBox.grid", [
//            'search' => $search
//        ]);
//    }

    #[Get(path: '/annotation/staticBBox')]
    public function browse(SearchData $search)
    {
        $corpus = BrowseService::browseCorpusBySearch($search, [], 'StaticBBoxAnnotation');

        return view('Annotation.StaticBBox.browse', [
            'data' => $corpus,
        ]);
    }

    #[Post(path: '/annotation/staticBBox/tree')]
    public function tree(SearchData $search)
    {
        if (! is_null($search->idCorpus) || ($search->document != '')) {
            $data = BrowseService::browseDocumentBySearch($search, [], 'StaticBBoxAnnotation', leaf: true);
        } else {
            $data = BrowseService::browseCorpusBySearch($search, [], 'StaticBBoxAnnotation');
        }

        return view('Annotation.StaticBBox.browse', [
            'data' => $data,
        ])->fragment('tree');
    }
    private function getData(int $idDocument): DocumentData
    {
        $document = Document::byId($idDocument);
        $corpus = Corpus::byId($document->idCorpus);
        $documentImage = Criteria::table("view_document_image")
            ->where("idDocument", $idDocument)
            ->first();
        $image = Image::byId($documentImage->idImage);
        return DocumentData::from([
            'idDocument' => $idDocument,
            'idDocumentImage' => $documentImage->idDocumentImage,
            'document' => $document,
            'corpus' => $corpus,
            'image' => $image,
            'fragment' => 'fe',
            'idPrevious' => AnnotationStaticBBoxService::getPrevious($document),
            'idNext' => AnnotationStaticBBoxService::getNext($document),
        ]);
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
            $this->trigger('updateObjectAnnotationEvent');
            //return Criteria::byId("staticobject", "idStaticObject", $idStaticObject);
            return $this->renderNotify("success", "Object updated.");
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

    /*
     * Comment
     */

    #[Get(path: '/annotation/staticBBox/formComment')]
    public function getFormComment(CommentData $data)
    {
        $object = CommentService::getStaticObjectComment($data->idStaticObject);
        return view("Annotation.StaticBBox.Panes.formComment", [
            'idDocument' => $data->idDocument,
            'order' => $data->order,
            'object' => $object
        ]);
    }

    #[Post(path: '/annotation/staticBBox/updateObjectComment')]
    public function updateObjectComment(CommentData $data)
    {
        try {
            debug($data);
            CommentService::updateStaticObjectComment($data);
            $this->trigger('updateObjectAnnotationEvent');
            return $this->renderNotify("success", "Comment registered.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/staticBBox/comment/{idDocument}/{idStaticObject}')]
    public function deleteObjectComment(int $idDocument, int $idStaticObject)
    {
        try {
            CommentService::deleteStaticObjectComment($idDocument, $idStaticObject);
            return $this->renderNotify("success", "Object comment removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    /*
     * get Object
     */
    #[Get(path: '/annotation/staticBBox/{idDocument}/{idStaticObject?}')]
    public function annotation(int $idDocument, int $idStaticObject = null)
    {
        $data = $this->getData($idDocument);
        if (!is_null($idStaticObject)) {
            $data->idStaticObject = $idStaticObject;
        }

        return view("Annotation.StaticBBox.annotation", $data->toArray());
    }

}
