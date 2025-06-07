@php
    $reports = [
        'reportframe' => ['Frame', '/report/frame', 'List of all frames and its structure.','ui::icon.frame'],
        'reportlu' => ['LU', '/report/lu', 'List of lexical and visual Lexical Units','ui::icon.lu'],
    //    'networkstructure' => ['Network', '/network', 'MASTER', []],
        'cxnreport' => ['Constructions', '/report/cxn', 'List of all constructions and its structure.', 'ui::icon.construction' ],
        'reporttqr' => ['TQR', '/report/qualia', 'Structure of Ternary Qualia Relarion (TQR).', 'ui::icon.qualia'],
        'reportst' => ['SemanticType', '/report/semanticType', 'List of Semantic Types and its hierarchy.','ui::icon.semantictype'],
        'reportc5' => ['MoCCA', '/report/c5', 'List of all components of MoCCA Project.','ui::icon.concept'],
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
            <div class="content-container">
                <div class="card-grid dense">
                    @foreach($reports as $category => $report)
                        <div
                            class="ui card option-card"
                            data-category="{{$category}}"
                            hx-get="{{$report[1]}}"
                            hx-target="body"
                            hx-swap="outerHTML"
                            hx-indicator="this"
                        >
                            <div class="content">
                                <div class="header">
                                    <x-dynamic-component :component="$report[3]"/>
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
