<?php

namespace App\Http\Controllers\D3;

use App\Data\CreateFrameData;
use App\Data\CreateRelationFEInternalData;
use App\Data\D3\SearchData;
use App\Data\Frame\CreateData;
use App\Data\UpdateFrameClassificationData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FE\FEController;
use App\Repositories\Entry;
use App\Repositories\Frame;
use App\Services\AppService;
use App\Services\D3\BrowseService;
use App\Services\RelationService;
use App\Services\Trankit\TrankitService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("web")]
class TreeController extends Controller
{
    #[Get(path: '/d3')]
    public function browse(SearchData $search)
    {
        $data = BrowseService::browseCorpusBySearch($search, [], 'CorpusAnnotation');
        session(["corpusAnnotationType" => "fe"]);
        return view('D3.browseSentences', [
            'page' => 'D3 Annotation',
            'url' => '/d3/sentence',
            'data' => $data,
        ]);
    }

    #[Get(path: '/d3/sentence/{idDocumentSentence}')]
    public function sentence(int $idDocumentSentence)
    {
        // Fetch sentence from database
        $sentenceData = Criteria::table('sentence')
            ->join('document_sentence as ds', 'sentence.idSentence', '=', 'ds.idSentence')
            ->where('ds.idDocumentSentence', $idDocumentSentence)
            ->select('sentence.idSentence', 'sentence.text')
            ->first();

        if (! $sentenceData) {
            abort(404, 'Sentence not found');
        }

        // Initialize Trankit service
        $trankit = new TrankitService();
        $trankit->init('http://localhost:8405');

        // Parse sentence
        $result = $trankit->getUDTrankit($sentenceData->text, 1);
        $udNodes = $result->udpipe;

        // Convert UD structure to D3 hierarchical format
        $treeData = $this->convertUDToD3Tree($udNodes);

        return view('D3.main', [
            'idDocumentSentence' => $idDocumentSentence,
            'sentenceText' => $sentenceData->text,
            'treeData' => json_encode($treeData),
        ]);
    }

    /**
     * Convert UD parse structure to D3 hierarchical tree format
     */
    private function convertUDToD3Tree(array $udNodes): array
    {
        if (empty($udNodes)) {
            return [];
        }

        // Find root node (where parent == 0)
        $rootId = null;
        foreach ($udNodes as $node) {
            if ($node['parent'] == 0) {
                $rootId = $node['id'];
                break;
            }
        }

        if ($rootId === null) {
            return [];
        }

        // Build tree recursively
        return $this->buildTreeNode($rootId, $udNodes);
    }

    /**
     * Recursively build tree node with children
     */
    private function buildTreeNode(int $nodeId, array $udNodes): array
    {
        $node = null;
        foreach ($udNodes as $n) {
            if ($n['id'] == $nodeId) {
                $node = $n;
                break;
            }
        }

        if (! $node) {
            return [];
        }

        // Format label: word [POS] [rel]
        $label = $node['word'];
        if ($node['pos']) {
            $label .= ' [' . $node['pos'] . ']';
        }
        if ($node['rel']) {
            $label .= ' [' . $node['rel'] . ']';
        }

        $treeNode = [
            'name' => $label,
            'id' => $node['id'],
            'word' => $node['word'],
            'pos' => $node['pos'],
            'rel' => $node['rel'],
        ];

        // Add children recursively
        if (! empty($node['children'])) {
            $treeNode['children'] = [];
            foreach ($node['children'] as $childId) {
                $childNode = $this->buildTreeNode($childId, $udNodes);
                if (! empty($childNode)) {
                    $treeNode['children'][] = $childNode;
                }
            }
        }

        return $treeNode;
    }

}
