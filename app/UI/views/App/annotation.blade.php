@php
    $annotation = [
        'annotationfe' => ['FE', '/annotation/fe', 'Corpus annotation for FE layer','ui::icon.frame'],
        'annotationfulltext' => ['Full text', '/annotation/fullText', 'Corpus annotation for all layers','ui::icon.frame'],
        'annotationdynamic' => ['Dynamic mode', '/annotation/dynamicMode', 'Video annotation.', 'ui::icon.frame' ],
        'annotationdeixis' => ['Deixis', '/annotation/deixis', 'Video annotation for deixis.', 'ui::icon.frame'],
        'annotationstaticbbox' => ['Static bbox', '/annotation/staticBBox', 'Image annotation.','ui::icon.frame'],
        'annotationstaticevent' => ['Static event', '/annotation/staticEvent', 'Image annotation for eventive frames.','ui::icon.frame'],
        'annotationset' => ['Annotation Sets', '/annotation/as', 'Check annotation sets.','ui::icon.frame'],
    ];
    $annotationType = [
        'corpus' => ['title' => "Corpus", "pages" => ['annotationfe','annotationfulltext','annotationset']],
        'video' => ['title' => "Video", "pages" => ['annotationdynamic','annotationdeixis']],
        'image' => ['title' => "Image", "pages" => ['annotationstaticbbox','annotationstaticevent']],
    ];

@endphp

<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['','Annotation']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-header">
                <div class="page-header-content">
                    <div class="page-title">
                        Annotation
                    </div>
                </div>
            </div>
            <div class="page-content grid-page">
                <div class="ui container">
                    @foreach($annotationType as $type)
                        <div class="grid-section">
                            <div class="section-header">
                                <h2 class="ui header">{{$type['title']}}</h2>
                            </div>
                            <div class="section-grid">
                                <div class="card-grid dense">
                                    @foreach($type['pages'] as $category)
                                        @php
                                            $item = $annotation[$category];
                                        @endphp
                                        <a
                                            class="ui card option-card"
                                            data-category="{{$category}}"
                                            href="{{$item[1]}}"
                                            hx-boost="true"
                                        >
                                            <div class="content">
                                                <div class="header">
                                                    <x-dynamic-component :component="$item[3]"/>
                                                    {{$item[0]}}
                                                </div>
                                                <div class="description">
                                                    {{$item[2]}}
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</x-layout::index>
