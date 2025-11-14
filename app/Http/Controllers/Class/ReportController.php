<?php

namespace App\Http\Controllers\Class;

use App\Data\Class\SearchData;
use App\Http\Controllers\Controller;
use App\Services\Class\BrowseService;
use App\Services\Class\ReportService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'web')]
class ReportController extends Controller
{
    #[Get(path: '/report/class')]
    public function browse(SearchData $search)
    {
        $classes = BrowseService::browseClassBySearch($search);

        return view('Class.Report.main', [
            'data' => $classes,
        ]);
    }

    #[Post(path: '/report/class/search')]
    public function tree(SearchData $search)
    {
        $data = BrowseService::browseClassBySearch($search);

        return view('Class.Report.main', [
            'data' => $data,
        ])->fragment('search');
    }

    #[Get(path: '/report/class/{idClass}/{lang?}')]
    public function report(int|string $idClass = '', string $lang = '')
    {
        $data = ReportService::report($idClass, $lang);
        $data['isHtmx'] = $this->isHtmx();

        if ($data['isHtmx']) {
            return view('Class.Report.reportPartial', $data);
        }

        return view('Class.Report.report', $data);
    }
}
