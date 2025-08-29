@php
    use App\Services\AppService;

    $options = [
        'project' => ['Project/Dataset', '/project','', 'MANAGER','ui::icon.frame'],
        'task' => ['Task/User', '/task', '','MANAGER','ui::icon.frame'],
        'user' => ['Group/User', '/user','', 'ADMIN','ui::icon.frame'],
        'document' => ['Corpus/Document','/corpus','', 'ADMIN','ui::icon.domain'],
        'video' => ['Video/Document', '/video','', 'ADMIN','ui::icon.frame'],
        'image' => ['Image/Document', '/image','', 'ADMIN','ui::icon.frame'],
        'semantictype' => ['Domain/SemanticType','/semanticType','', 'ADMIN','ui::icon.frame'],
        'layer' => ['Layer/GenericLabel', '/layers','', 'ADMIN','ui::icon.frame'],
        'relations' => ['Relations', '/relations','', 'ADMIN','ui::icon.frame'],
    ];
@endphp

<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['','Manager']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container">
                    <div class="card-grid dense">
                        @foreach($options as $category => $option)
                            @if (AppService::checkAccess($option[3]))
                                <a
                                    class="ui card option-card"
                                    data-category="{{$category}}"
                                    href="{{$option[1]}}"
                                    hx-boost="true"
                                >
                                    <div class="content">
                                        <div class="header">
                                            <x-dynamic-component :component="$option[4]" />
                                            {{$option[0]}}
                                        </div>
                                        <div class="description">
                                            {{$option[2]}}
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layout::index>
