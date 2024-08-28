<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\FE\AnnotationData;
use App\Data\Annotation\FE\CreateASData;
use App\Data\Annotation\FE\DeleteFEData;
use App\Data\Annotation\FE\SearchData;
use App\Data\Annotation\FE\SelectionData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\AnnotationSet;
use App\Repositories\Document;
use App\Repositories\WordForm;
use App\Services\AnnotationFEService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class FEController extends Controller
{
    #[Get(path: '/annotation/fe')]
    public function browse()
    {
        $search = session('searchFEAnnotation') ?? SearchData::from();
        return view("Annotation.FE.browse", [
            'search' => $search
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

    #[Get(path: '/annotation/fe/sentence/{idDocumentSentence}')]
    public function sentence(int $idDocumentSentence)
    {
        $data = AnnotationFEService::getAnnotationData($idDocumentSentence);
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
        $data = AnnotationFEService::getASData($idAS);
        debug($token);
        $idLU = $data['lu']->idLU;
        $data['alternativeLU'] = [];
        foreach(WordForm::getLUs($token) as $lu) {
            if ($lu->idLU != $idLU) {
                $data['alternativeLU'][] = $lu;
            }
        }
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
            if ($input->range->type != '') {
                $data = AnnotationFEService::annotateFE($input);
                return view("Annotation.FE.Panes.annotationSet", $data);
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
            $data = AnnotationFEService::getASData($data->idAnnotationSet);
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
            $data = AnnotationFEService::getASData($idAnnotationSet);
            return response()
                ->view("Annotation.FE.Panes.annotationSet", $data)
                ->header('HX-Trigger', 'reload-sentence');

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

}

