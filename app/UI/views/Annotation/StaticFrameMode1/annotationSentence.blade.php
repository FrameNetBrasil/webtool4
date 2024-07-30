@php
    $panelWestWidth = $imageMM->width + 30;
    $panelImageHeight = $imageMM->height + 40;
@endphp
<x-layout.edit>
    <x-slot:title>
        Static Annotation: Frame Mode 1
    </x-slot:title>
    <x-slot:actions>
        <div class="navigationPane">
            @if($idStaticSentenceMMPrevious)
                <div class="navigationPane-previous">
                    <span class="material-icons-outlined">arrow_back</span>
                    <a href="/annotation/staticFrameMode1/sentence/{{$idStaticSentenceMMPrevious}}"><span>Previous</span></a>
                </div>
            @endif
            @if($idStaticSentenceMMNext)
                <div class="navigationPane-next">
                    <a href="/annotation/staticFrameMode1/sentence/{{$idStaticSentenceMMNext}}">Next</a>
                    <span class="material-icons-outlined">arrow_forward</span>
                </div>
            @endif
        </div>
    </x-slot:actions>
    <x-slot:main>
        @include('Annotation.StaticFrameMode1.Panes.annotation')
        <div id="staticFrameMode1AnnotationPane" class="staticFrameModeAnnotationPane">
            <div id="annotationImagePaneTitle"
                 data-options="region:'north', collapsible:false, title:'', border:0">
            </div>
            <div data-options="region:'west',border:0" style="padding:8px;width:{{$panelWestWidth}}px;">
                <div style="display:flex; flex-direction: column;">
                    <div id="annotationImagePaneImage" style="width:100%;height:{{$panelImageHeight}}px">
                        @include('Annotation.StaticFrameMode1.Panes.imagePane')
                    </div>
                    <div id="annotationImagePaneSentence" style="width:100%;">
                        @include('Annotation.StaticFrameMode1.Panes.sentencePane')
                    </div>
                </div>
            </div>
            <div data-options="region:'center',border:0" style="padding:8px;">
                <div style="display:flex; flex-direction: column;">
                    <div class="framePaneHeader">
                        <x-tag label="Corpus: {{$corpus->name}}"></x-tag>
                        <x-tag label="Document: {{$document->name}}"></x-tag>
                        <x-tag label="Image: {{$imageMM->name}}"></x-tag>
                        <x-tag label="#idStaticSentenceMM: {{$idStaticSentenceMM}}"></x-tag>
                    </div>
                    <div id="annotationImagePaneFrame">
                        @include('Annotation.StaticFrameMode1.Panes.framePane')
                    </div>
                </div>
            </div>
        </div>
        <div id="annotationPaneDialog" class="easyui-panel" data-options="border:0" style="width:0;height:0">

        </div>
        <script type="text/javascript">
            $(function() {
                $("#staticFrameMode1AnnotationPane").layout({
                    fit: true,
                    border: true
                });
            });
        </script>
    </x-slot:main>
</x-layout.edit>
