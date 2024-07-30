<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\StaticFrameMode\CreateData;
use App\Data\Annotation\StaticFrameMode\ObjectFrameData;
use App\Data\Annotation\StaticFrameMode\SearchDataMode1;
use App\Http\Controllers\Controller;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\Frame;
use App\Repositories\ImageMM;
use App\Repositories\LU;
use App\Repositories\Sentence;
use App\Repositories\StaticSentenceMM;
use App\Repositories\UserAnnotation;
use App\Services\AnnotationStaticFrameModeService;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware(name: 'auth')]
class StaticFrameMode1Controller extends Controller
{
    #[Get(path: '/annotation/staticFrameMode1')]
    public function browse()
    {
        $search = session('searchFrame') ?? SearchDataMode1::from();
        return view("Panes.StaticFrameMode1.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/annotation/grid/staticFrameMode1')]
    public function grid(SearchDataMode1 $search)
    {
        return view("Panes.StaticFrameMode1.grid", [
            'search' => $search
        ]);
    }

    #[Get(path: '/annotation/staticFrameMode1/listForTree')]
    public function listForTree(SearchDataMode1 $search)
    {
        $result = [];
        if (!is_null($search->idCorpus)) {
            $documents = Document::listByFilter($search)->all();
            foreach ($documents as $document) {
                $node = [];
                $node['id'] = 'd' . $document->idDocument;
                $node['type'] = 'document';
                $node['name'] = $document->name;
                $node['idSentence'] = '';
                $node['idSentenceMM'] = '';
                $node['image'] = '';
                $node['status'] = '';
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-document';
                $node['children'] = [];
                $result[] = $node;
            }
        } elseif (!is_null($search->idDocument)) {
            $sentences = StaticSentenceMM::listByDocument($search->idDocument, $search->image);
            $sentenceForAnnotation = UserAnnotation::listSentenceByUser(AppService::getCurrentUser()->idUser, $search->idDocument);
            $hasSentenceForAnnotation = (count($sentenceForAnnotation) > 0);
            foreach ($sentences as $sentence) {
                if ($hasSentenceForAnnotation) {
                    if (!in_array($sentence->idSentence, $sentenceForAnnotation)) {
                        continue;
                    }
                }
                $node = [];
                $node['id'] = 's' . $sentence->idStaticSentenceMM;
                $node['type'] = 'sentence';
                $node['name'] = $sentence->text;
                $node['idSentence'] = $sentence->idSentence;
                $node['idStaticSentenceMM'] = $sentence->idStaticSentenceMM;
                $node['image'] = $sentence->image;
                $node['status'] = $sentence->status;
                $node['state'] = 'open';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-annotation-static-frame-mode-1';
                $node['children'] = null;
                $result[] = $node;
            }
        } else {
            $corpora = Corpus::listByFilter($search)->all();
            foreach ($corpora as $row) {
                $node = [];
                $node['id'] = 'c' . $row->idCorpus;
                $node['type'] = 'corpus';
                $node['name'] = [$row->name];
                $node['idSentence'] = '';
                $node['idSentenceMM'] = '';
                $node['image'] = '';
                $node['status'] = '';
                $node['state'] = 'closed';
                $node['iconCls'] = 'material-icons-outlined wt-tree-icon wt-icon-corpus';
                $node['children'] = [];
                $result[] = $node;
            }
        }
        return $result;
    }

    private function getData(int $idStaticSentenceMM): array
    {
        $staticSentenceMM = StaticSentenceMM::getById($idStaticSentenceMM);
        $document = Document::getById($staticSentenceMM->idDocument);
        $imageMM = ImageMM::getById($staticSentenceMM->idImageMM);
        $annotation = AnnotationStaticFrameModeService::getObjectsForAnnotationImage($idStaticSentenceMM);
        $sentence = Sentence::getById($staticSentenceMM->idSentence);
        $corpus = Corpus::getById($document->idCorpus);
        return [
            'idStaticSentenceMM' => $idStaticSentenceMM,
            'idStaticSentenceMMPrevious' => AnnotationStaticFrameModeService::getPrevious($idStaticSentenceMM) ?? '',
            'idStaticSentenceMMNext' => AnnotationStaticFrameModeService::getNext($idStaticSentenceMM) ?? '',
            'document' => $document,
            'sentence' => $sentence,
            'corpus' => $corpus,
            'imageMM' => $imageMM,
            'objects' => $annotation['objects'],
            'frames' => $annotation['frames'],
        ];
    }

    #[Get(path: '/annotation/staticFrameMode1/sentence/{idStaticSentenceMM}')]
    public function annotationSentence(int $idStaticSentenceMM)
    {
        return view("Panes.StaticFrameMode1.annotationSentence", $this->getData($idStaticSentenceMM));
    }

    #[Get(path: '/annotation/staticFrameMode1/sentence/{idSentenceMM}/object')]
    public function annotationSentenceObject(int $idSentenceMM)
    {
        return $this->getData($idSentenceMM);
    }

    #[Post(path: '/annotation/staticFrameMode1/fes')]
    public function annotationSentenceFes(CreateData $input)
    {
         $data = $this->getData($input->idStaticSentenceMM);
//        debug($this->data);
        $idFrame = '';
        if (is_numeric($input->idLU)) {
            $lu = LU::getById($input->idLU);
            $idFrame = $lu->idFrame;
        } else if (is_numeric($input->idFrame)) {
            $idFrame = $input->idFrame;
        }
//        debug($idFrame);
        if ($idFrame != '') {
            $frames = $data['frames'];
            if (!AnnotationStaticFrameModeService::hasFrame($input->idStaticSentenceMM, $idFrame)) {
                $data['idFrame'] = $idFrame;
                $frame = Frame::getById($idFrame);
                $frames[$idFrame] = [
                    'name' => $frame->name,
                    'idFrame' => $idFrame,
                    'objects' => []
                ];
                //debug($frames);
                $data['frames'] = $frames;
            }
            return view("Panes.StaticFrameMode1.fes", $data);
        } else {
            return $this->renderNotify("error", "Frame not found!");
        }
    }

    #[Put(path: '/annotation/staticFrameMode1/fes/{idStaticSentenceMM}/{idFrame}')]
    public function annotationSentenceFesSubmit(int $idStaticSentenceMM, int $idFrame, ObjectFrameData $data)
    {
        debug($data);
        try {
            foreach ($data->idStaticObjectSentenceMM as $objects) {
                foreach ($objects as $idStaticObjectSentenceMM => $idFrameElement) {
                    if ($idFrameElement == '') {
                        throw new \Exception("FrameElement must be informed.");
                    }
                }
            }
            AnnotationStaticFrameModeService::updateObjectSentenceFE($idStaticSentenceMM, $idFrame, $data->idStaticObjectSentenceMM);
            return $this->renderNotify("success", "FrameElement updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/staticFrameMode1/fes/{idStaticSentenceMM}/{idFrame}')]
    public function annotationSentenceFesDelete(int $idStaticSentenceMM, int $idFrame)
    {
        try {
            AnnotationStaticFrameModeService::deleteAnnotationByFrame($idStaticSentenceMM, $idFrame);
            $this->renderNotify("success", "Frame deleted.");
            return view("Panes.StaticFrameMode1.annotationSentence", $this->getData($idStaticSentenceMM));
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

}
