<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\FullText\DeleteLabelData;
use App\Data\Annotation\FullText\SelectionData;
use App\Data\Annotation\FullText\AnnotationData;
use App\Data\Annotation\FullText\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\AnnotationSet;
use App\Repositories\Document;
use App\Repositories\WordForm;
use App\Services\AnnotationFullTextService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class FullTextController extends Controller
{
    #[Get(path: '/annotation/fullText')]
    public function browse()
    {
        $search = session('searchFEAnnotation') ?? SearchData::from();
        return view("Annotation.FullText.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/annotation/fullText/grid')]
    public function grid(SearchData $search)
    {
        return view("Annotation.FullText.grids", [
            'search' => $search,
            'sentences' => [],
        ]);
    }

    #[Get(path: '/annotation/fullText/grid/{idDocument}/sentences')]
    public function documentSentences(int $idDocument)
    {
        $document = Document::byId($idDocument);
        $sentences = AnnotationFullTextService::listSentences($idDocument);
        return view("Annotation.FullText.sentences", [
            'document' => $document,
            'sentences' => $sentences
        ]);
    }

    #[Get(path: '/annotation/fullText/sentence/{idDocumentSentence}')]
    public function sentence(int $idDocumentSentence)
    {
        $data = AnnotationFullTextService::getAnnotationData($idDocumentSentence);
        return view("Annotation.FullText.annotationSentence", $data);
    }

    #[Get(path: '/annotation/fullText/annotations/{idSentence}')]
    public function annotations(int $idSentence)
    {
        $data = AnnotationFullTextService::getAnnotationData($idSentence);
        return view("Annotation.FullText.Panes.annotations", $data);
    }

    #[Get(path: '/annotation/fullText/spans/{idAS}')]
    public function getSpans(int $idAS)
    {
        return AnnotationFullTextService::getSpans($idAS);
    }

    #[Get(path: '/annotation/fullText/as/{idAS}/{token}')]
    public function annotationSet(int $idAS, string $token)
    {
        $data = AnnotationFullTextService::getASData($idAS, $token);
//        debug($data['lu']);
        return view("Annotation.FullText.Panes.annotationSet", $data);
    }

    #[Get(path: '/annotation/fullText/lus/{idDocumentSentence}/{idWord}')]
    public function getLUs(int $idDocumentSentence, int $idWord)
    {
        $data = AnnotationFullTextService::getLUs($idDocumentSentence, $idWord);
        $data['idWord'] = $idWord;
        $data['idDocumentSentence'] = $idDocumentSentence;
        return view("Annotation.FullText.Panes.lus", $data);
    }

    #[Post(path: '/annotation/fullText/annotate')]
    public function annotate(AnnotationData $input)
    {
        try {
            debug($input);
            debug(request("selection"));
            $input->range = SelectionData::from(request("selection"));
            if ($input->range->type != '') {
                $data = AnnotationFullTextService::annotateEntity($input);
                //return view("Annotation.FullText.Panes.annotationSet", $data);
                //return $data;
                return $input->idAnnotationSet;
            } else {
                throw new \Exception("No selection.");
            }
        } catch (\Exception $e) {
            $trigger = $this->notify("error", $e->getMessage());
            return response('', 500)
                ->header('HX-Trigger', $trigger);
        }
    }

    #[Delete(path: '/annotation/fullText/label')]
    public function deleteFE(DeleteLabelData $data)
    {
        try {
            AnnotationFullTextService::deleteLabel($data);
            //AnnotationFullTextService::getASData($data->idAnnotationSet);
            //return view("Annotation.FullText.Panes.annotationSet", $data);
            return $data->idAnnotationSet;
        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
            $trigger = $this->notify("error", $e->getMessage());
            return response('', 500)
                ->header('HX-Trigger', $trigger);
        }
    }

    #[Post(path: '/annotation/fullText/create')]
    public function createAS(CreateASData $input)
    {
        $idAnnotationSet = AnnotationFullTextService::createAnnotationSet($input);
        if (is_null($idAnnotationSet)) {
            return $this->renderNotify("error", "Error creating AnnotationSet.");
        } else {
            $data = AnnotationFullTextService::getASData($idAnnotationSet);
            return response()
                ->view("Annotation.FullText.Panes.annotationSet", $data)
                ->header('HX-Trigger', 'reload-sentence');

        }
    }

    #[Delete(path: '/annotation/fullText/annotationset/{idAnnotationSet}')]
    public function deleteAS(int $idAnnotationSet)
    {
        try {
            $annotationSet = Criteria::byId("view_annotationset","idAnnotationSet", $idAnnotationSet);
            AnnotationSet::delete($idAnnotationSet);
            return $this->clientRedirect("/annotation/fullText/sentence/{$annotationSet->idDocumentSentence}");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}

