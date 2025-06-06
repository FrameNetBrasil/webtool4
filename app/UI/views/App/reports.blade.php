@php
    $reports = [
        'reportframe' => ['Frame', '/report/frame', 'List of all frames and its structure.','frame'],
        'reportlu' => ['LU', '/report/lu', 'List of lexical and visual Lexical Units','lu'],
    //    'networkstructure' => ['Network', '/network', 'MASTER', []],
        'cxnreport' => ['Constructions', '/report/cxn', 'List of all constructions and its structure.', 'construction' ],
        'reporttqr' => ['TQR', '/report/qualia', 'Structure of Ternary Qualia Relarion (TQR).', 'qualia'],
        'reportst' => ['SemanticType', '/report/semanticType', 'List of Semantic Types and its hierarchy.','semantictype'],
        'reportc5' => ['MoCCA', '/report/c5', 'List of all components of MoCCA Project.','concept'],
    ];
@endphp

<x-layout::page>
    <x-slot:breadcrumb>
        <x-breadcrumb :sections="[]"></x-breadcrumb>
    </x-slot:breadcrumb>
    <x-slot:main>
        <x-ui::page-header
            title="Reports"
            subtitle="Access webtool reports.">
        </x-ui::page-header>
        <div class="page-content">
            <div class="content-container wide">
                <div class="card-grid dense">
                    @foreach($reports as $category => $report)
                        <div class="ui card option-card" data-category="{{$category}}">
                            <div class="content">
{{--                                <div class="option-card-icon {{$report[3]}}">--}}
{{--                                    <x-ui::icon.frame></x-ui::icon.frame>--}}
{{--                                </div>--}}
                                <div class="header">
                                    <span class="icon material frame"></span>
                                    {{$report[0]}}
                                </div>
                                <div class="description">
                                    {{$report[2]}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout::page>
