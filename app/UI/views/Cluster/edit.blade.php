@use("Carbon\Carbon")
<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/structure','Structure'],['/cluster','Cluster'],['',$frame?->name]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container page-edit">
                <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-header-main">
                            <div class="page-title-section">
                                <div class="page-title">
                                    <x-ui::element.frame_ns :frame="$frame"></x-ui::element.frame_ns>
                                </div>
                                <div
                                    class="page-subtitle">{!! str_replace('ex>','code>',nl2br($frame->description)) !!}</div>
                            </div>
                            @if(session('isAdmin'))
                                <button
                                    class="ui right labeled icon button"
                                    hx-get="/cluster/nextFrom/{{$frame->idFrame}}"
                                >
                                    <i class="right arrow icon"></i>
                                    Next
                                </button>
                                <button
                                    x-data
                                    type=button"
                                    class="ui danger button"
                                    @click="messenger.confirmDelete(`Removing Cluster '{{$frame?->name}}'.`, '/cluster/{{$frame->idFrame}}')"
                                >Delete</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="page-content">
                    <div class="frame-metadata-section">
                        @include('Cluster.Report.partials.frame-metadata')
                    </div>

                    @include("Cluster.menu")
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>
