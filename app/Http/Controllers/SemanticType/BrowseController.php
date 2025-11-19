<?php

namespace App\Http\Controllers\SemanticType;

use App\Data\SemanticType\SearchData;
use App\Http\Controllers\Controller;
use App\Services\SemanticType\BrowseService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware('auth')]
class BrowseController extends Controller
{
    #[Get(path: '/semanticType')]
    public function browse(SearchData $search)
    {
        $data = BrowseService::browseSemanticTypeBySearch($search);
        return view('SemanticType.browse', [
            'data' => $data,
        ]);
    }

    #[Post(path: '/semanticType/browse/search')]
    public function search(SearchData $search)
    {
        $title = '';
        $data = BrowseService::browseSemanticTypeBySearch($search);
        return view('SemanticType.tree', [
            'title' => $title,
            'data' => $data,
        ]);
    }

    /**
     * Format tree data with icons specific to webtool4's UI
     */
//    private function formatDataWithIcons(array $data): array
//    {
//        $corpusIcon = view('components.icon.corpus')->render();
//        $documentIcon = view('components.icon.document')->render();
//
//        $formatted = [];
//        foreach ($data as $item) {
//            if ($item['type'] === 'corpus') {
//                $item['text'] = $corpusIcon.$item['text'];
//            } elseif ($item['type'] === 'document') {
//                // Check if text already has corpus prefix (from search results)
//                if (str_contains($item['text'], ' / ')) {
//                    $item['text'] = $documentIcon.$item['text'];
//                } else {
//                    // For documents in corpus tree, use the partial view
//                    $item['text'] = view('Annotation.partials.document', [
//                        'document' => $item['text'],
//                        'corpusName' => '',
//                    ])->render();
//                }
//            }
//            $formatted[] = $item;
//        }
//
//        return $formatted;
//    }
}
