@php
    $options = [
        'frame' => ['Frame', '/structure/frame', '','ui::icon.frame'],
        'lexicon' => ['Lexicon', '/structure/lexicon', '','ui::icon.domain'],
        'lucandidate' => ['Lu Candidate', '/structure/luCandidate', '','ui::icon.frame'],
        'constructicon' => ['Constructicon', '/structure/constructicon', '','ui::icon.construction'],
        'reframing' => ['Reframing', '/reframing', '','ui::icon.lu'],
    ];
@endphp

<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['','Structure']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container">
                    <div class="card-grid dense">
                        @foreach($options as $category => $option)
                            <a
                                class="ui card option-card"
                                data-category="{{$category}}"
                                href="{{$option[1]}}"
                                hx-boost="true"
                            >
                                <div class="content">
                                    <div class="header">
                                        <x-dynamic-component :component="$option[3]" />
                                        {{$option[0]}}
                                    </div>
                                    <div class="description">
                                        {{$option[2]}}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layout::index>
