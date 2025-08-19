<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/frame','Frames'],['',$frame?->name]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container">
                    <div class="page-header">
                        <div class="page-header-content">
                            <div class="page-header-main">
                                <div class="page-title-section">
                                    <div class="page-title">
                                        <x-ui::element.frame name="{{$frame->name}}"></x-ui::element.frame>
                                    </div>
                                    <div
                                        class="page-subtitle">{!! str_replace('ex>','code>',nl2br($frame->description)) !!}</div>
                                </div>
                                <div class="page-actions">
                                    @if(session('isAdmin'))
                                        <button
                                            x-data
                                            class="ui danger button"
                                            @click.prevent="messenger.confirmDelete(`Removing Frame '{{$frame?->name}}'.`, '/frame/{{$frame->idFrame}}')"
                                        >Delete</button>
                                    @endif
                                    <a href="/frame" class="ui button basic icon back-button">
                                        <i class="arrow left icon"></i>
                                        Back to Frames
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="content-container">
                            <div class="frame-metadata-section">
                                @include('Frame.Report.partials.frame-metadata')
                            </div>
                            <x-ui::tabs
                                :tabs="[
          ['id' => 'entries', 'label' => 'Translations', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/entries'],
          ['id' => 'fes', 'label' => 'FrameElements', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/fes'],
          ['id' => 'lus', 'label' => 'LUs', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/lus'],
          ['id' => 'classification', 'label' => 'Classification', 'translate' => 'text', 'url' => '/frame/'.$frame->idFrame.'/classification'],
          ['id' => 'relations', 'label' => 'Frame-Frame Relations', 'translate' => 'text', 'url' => '/frame/'.$frame->idFrame.'/relations'],
          ['id' => 'feRelations', 'label' => 'FE-FE Relations', 'translate' => 'text', 'url' => '/frame/'.$frame->idFrame.'/feRelations'],
          ['id' => 'semanticTypes', 'label' => 'SemanticTypes', 'translate' => 'text', 'url' => '/frame/'.$frame->idFrame.'/semanticTypes'],
      ]"
                                defaultTab="entries"
                                context="frame"
                                sectionTitle=""
                                :sectionToggle="false"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>

