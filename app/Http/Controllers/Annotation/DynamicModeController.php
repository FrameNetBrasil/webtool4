<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\DynamicMode\AnnotationCommentData;
use App\Data\Annotation\DynamicMode\CloneData;
use App\Data\Annotation\DynamicMode\CreateBBoxData;
use App\Data\Annotation\DynamicMode\DocumentData;
use App\Data\Annotation\DynamicMode\ObjectAnnotationData;
use App\Data\Annotation\DynamicMode\ObjectData;
use App\Data\Annotation\DynamicMode\SearchData;
use App\Data\Annotation\DynamicMode\SentenceData;
use App\Data\Annotation\DynamicMode\UpdateBBoxData;
use App\Data\Annotation\DynamicMode\WordData;
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

    #[Post(path: '/annotation/dynamicMode/formObject')]
    public function formObject(ObjectData $data)
    {
        debug($data);
        $object = AnnotationDynamicService::getObject($data->idDynamicObject ?? 0);
        return view("Annotation.DynamicMode.Panes.formPane", [
            'order' => $data->order,
            'object' => $object
        ]);
    }

    #[Get(path: '/annotation/dynamicMode/formObject/{idDynamicObject}/{order}')]
    public function getFormObject(int $idDynamicObject, int $order)
    {
        $object = AnnotationDynamicService::getObject($idDynamicObject ?? 0);
        return view("Annotation.DynamicMode.Panes.formPane", [
            'order' => $order,
            'object' => $object
        ]);
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

    #[Post(path: '/annotation/dynamicMode/updateObjectAnnotation')]
    public function updateObjectAnnotation(ObjectAnnotationData $data)
    {
        debug($data);
        try {
            $idDynamicObject = AnnotationDynamicService::updateObjectAnnotation($data);
            return Criteria::byId("dynamicobject", "idDynamicObject", $idDynamicObject);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/cloneObject')]
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

    #[Delete(path: '/annotation/dynamicMode/{idDynamicObject}')]
    public function deleteObject(int $idDynamicObject)
    {
        try {
            debug("========== deleting {$idDynamicObject}");
            AnnotationDynamicService::deleteObject($idDynamicObject);
            return $this->renderNotify("success", "Object removed.");
        } catch (\Exception $e) {
            debug($e->getMessage());
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/annotation/dynamicMode/updateBBox')]
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

    #[Post(path: '/annotation/dynamicMode/createBBox')]
    public function createBBox(CreateBBoxData $data)
    {
        try {
            debug($data);
            return AnnotationDynamicService::createBBox($data);
        } catch (\Exception $e) {
            debug($e->getMessage());
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

    #[Get(path: '/annotation/dynamicMode/sentences/{idDocument}')]
    public function gridSentences(int $idDocument)
    {
        $sentences = AnnotationDynamicService::listSentencesByDocument($idDocument);
        return view("Annotation.DynamicMode.Panes.sentences", [
            'sentences' => $sentences
        ]);
    }

    #[Post(path: '/annotation/dynamicMode/comment')]
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

    #[Get(path: '/annotation/dynamicMode/buildSentences/{idDocument}')]
    public function buildSentences(int $idDocument)
    {
        $data = $this->getData($idDocument);
        return view("Annotation.DynamicMode.buildSentences", $data->toArray());
    }

    #[Get(path: '/annotation/dynamicMode/formSentence/{idDocument}/{idSentence}')]
    public function formSentence(int $idDocument, int $idSentence)
    {
        $sentence = Criteria::byId("sentence", "idSentence", $idSentence);
        if (is_null($sentence)) {
            $sentence = (object)[
                "idSentence" => 0,
                "text" => ''
            ];
        } else {
            $ts = Criteria::byId("view_sentence_timespan", "idSentence", $idSentence);
            $sentence->startTime = $ts->startTime;
            $sentence->endTime = $ts->endTime;
        }
        $documentVideo = Criteria::table("view_document_video")
            ->where("idDocument", $idDocument)
            ->first();
        $video = Video::byId($documentVideo->idVideo);
        return view("Annotation.DynamicMode.Panes.formSentencePane", [
            'idDocument' => $idDocument,
            "sentence" => $sentence,
            'idLanguage' => $video->idLanguage
        ]);
    }

    #[Post(path: '/annotation/dynamicMode/formSentence')]
    public function sentence(SentenceData $data)
    {
        try {
            if ($data->text == "") {
                return $this->renderNotify("error", "No data.");
            }
            debug($data);
            if ($data->idSentence == 0) {
                AnnotationDynamicService::createSentence($data);
            } else {
                AnnotationDynamicService::updateSentence($data);
            }
            $this->trigger('reload-gridSentence');
            return $this->renderNotify("success", "Sentence updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/annotation/dynamicMode/words/{idVideo}')]
    public function words(int $idVideo)
    {
        $words = Criteria::table("view_video_wordmm")
            ->where("idVideo", "=", $idVideo)
            ->whereNull("idDocumentSentence")
            ->all();
        return $words;
    }

    #[Post(path: '/annotation/dynamicMode/joinWords')]
    public function joinWords(WordData $data)
    {
        try {
            debug($data);
            $idSentence = AnnotationDynamicService::buildSentenceFromWords($data);
            if ($idSentence == 0) {
                throw new \Exception("Error joining words.");
            }
            return $idSentence;
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/annotation/dynamicMode/buildSentences/sentences/{idDocument}')]
    public function buildSentenceSentences(int $idDocument)
    {

        $sentences = AnnotationDynamicService::listSentencesByDocument($idDocument);
        return view("Annotation.DynamicMode.Panes.buildSentences", [
            'idDocument' => $idDocument,
            'sentences' => $sentences
        ]);
    }

    #[Post(path: '/annotation/dynamicMode/splitSentence')]
    public function splitSentence(SentenceData $data)
    {
        try {
            AnnotationDynamicService::splitSentence($data);
            $this->trigger('reload-gridSentence');
            return $this->renderNotify("success", "Sentence updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


}
