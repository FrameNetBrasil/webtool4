@php
    $options = [
        'user' => ['Group/User', '/admin/user', '','ui::icon.frame'],
        'document' => ['Corpus/Document', '/admin/corpus', '','ui::icon.domain'],
        'video' => ['Video/Document', '/admin/video', '','ui::icon.frame'],
        'image' => ['Image/Document', '/admin/image', '','ui::icon.frame'],
        'semantictype' => ['Domain/SemanticType', '/admin/semantictype', '','ui::icon.frame'],
        'label' => ['Layer/GenericLabel', '/admin/label', '','ui::icon.frame'],
        'relations' => ['Relations', '/admin/relations', '','ui::icon.frame'],
    ];
@endphp

<x-layout::index>
    <div class="app-layout no-tools">
        @include('layouts.header')
        @include("layouts.sidebar")
        <main class="app-main">
            <x-ui::breadcrumb :sections="[['/','Home'],['','Admin']]"></x-ui::breadcrumb>
            <div class="page-content">
                <div class="content-container">
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
