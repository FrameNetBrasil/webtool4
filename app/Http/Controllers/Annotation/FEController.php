<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\FE\AnnotationData;
use App\Data\Annotation\FE\CreateASData;
use App\Data\Annotation\FE\DeleteFEData;
use App\Data\Annotation\FE\SearchData;
use App\Data\Annotation\FE\SelectionData;
use App\Http\Controllers\Controller;
use App\Repositories\AnnotationSet;
use App\Repositories\Document;
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

    #[Get(path: '/annotation/fe/as/{idAS}')]
    public function annotationSet(int $idAS)
    {
        $data = AnnotationFEService::getASData($idAS);
        return view("Annotation.FE.Panes.annotationSet", $data);
    }

    #[Get(path: '/annotation/fe/lus/{idDocumentSentence}/{idWord}')]
    public function getLUs(int $idDocumentSentence, int $idWord)
    {
        $data = AnnotationFEService::getLUs($idDocumentSentence, $idWord);
        $data['idWord'] = $idWord;
        $data['idDocumentSentence'] = $idDocumentSentence;
        debug($data);
        return view("Annotation.FE.Panes.lus", $data);
    }

    #[Post(path: '/annotation/fe/annotate')]
    public function annotate(AnnotationData $input)
    {
        $input->range = SelectionData::from(request("selection"));
        debug($input);
        $data = AnnotationFEService::annotateFE($input);
        return view("Annotation.FE.Panes.annotationSet", $data);
    }

    #[Post(path: '/annotation/fe/create')]
    public function createAS(CreateASData $input)
    {
        $data = [];
        debug($input);
        debug(request('wordList'));
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
            AnnotationSet::delete($idAnnotationSet);
            $this->trigger('reload-sentence');
            return response()
                ->view("Annotation.FE.Panes.dummy", [])
                ->header('HX-Trigger', 'reload-sentence');
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/fe/frameElement')]
    public function deleteFE(DeleteFEData $data)
    {
        try {
            AnnotationSet::deleteFE($data);
            $data = AnnotationFEService::getASData($data->idAnnotationSet);
            return view("Annotation.FE.Panes.annotationSet", $data);
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


}

