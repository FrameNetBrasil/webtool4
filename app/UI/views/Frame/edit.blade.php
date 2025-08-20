<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/frame','Frames'],['',$frame?->name]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container" >
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
                    <div class="page-content"  style="overflow:visible">
                            <div class="frame-metadata-section">
                                @include('Frame.Report.partials.frame-metadata')
                            </div>
                            <x-ui::tabs
                                id="editFrame"
                                :tabs="[
          'entries' => ['label' => 'Translations', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/entries'],
          'fes' => ['label' => 'FrameElements', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/fes'],
          'lus' => ['label' => 'LUs', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/lus'],
          'classification' => ['label' => 'Classification', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/classification'],
          'relations' => ['label' => 'Frame-Frame Relations', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/relations'],
          'feRelations' => ['label' => 'FE-FE Relations', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/feRelations'],
          'semanticTypes' => ['label' => 'SemanticTypes', 'icon' => 'translate', 'url' => '/frame/'.$frame->idFrame.'/semanticTypes'],
      ]"
                                defaultTab="entries"
{{--                                context="frame"--}}
{{--                                sectionTitle=""--}}
{{--                                :sectionToggle="false"--}}
                            />
                    </div>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>

