<x-layout::index>
    <div class="app-layout annotation-corpus">
        <div class="annotation-header">
            <div class="flex-container between">
                <div class="flex-item">
                    <x-ui::breadcrumb
                        :sections="[['/','Home'],['/annotation/fe','FE Annotation'],['','#' . $idDocumentSentence]]"></x-ui::breadcrumb>
                </div>
            </div>
        </div>
        <div class="annotation-canvas">
            <div class="annotation-navigation">
                <div class="flex-container between">
                    <div class="tag">
                        <div class="ui label wt-tag-id">
                            Corpus: {{$corpus->name}}
                        </div>
                        <div class="ui label wt-tag-id">
                            Document: {{$document->name}}
                        </div>
                    </div>
                    <div>
                        @if($idPrevious)
                            <a href="/annotation/fe/sentence/{{$idPrevious}}">
                                <button class="ui left labeled icon button">
                                    <i class="left arrow icon"></i>
                                    Previous
                                </button>
                            </a>
                        @endif
                        @if($idNext)
                            <a href="/annotation/fe/sentence/{{$idNext}}">
                                <button class="ui right labeled icon button">
                                    <i class="right arrow icon"></i>
                                    Next
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="annotation-sentence">
                <div
                    class="container"
                    hx-trigger="reload-sentence from:body"
                    hx-target="this"
                    hx-swap="outerHTML"
                    hx-get="/annotation/fe/annotations/{{$idDocumentSentence}}"
                >
                    @foreach($tokens as $i => $token)
                        @php
                            $hasAS = isset($token['idAS']) ? ' hasAS ' : '';
                            $hasLU = $token['hasLU'] ? ' hasLU ' : '';
                            if(isset($token['idAS'])) {
                                if($token['idAS'] == $idAnnotationSet) {
                                    $word =  $token['word'];
                                }
                            }
                        @endphp
                        <span
                            class="word {{$hasLU}}"
                            id="{{$i}}"
                        >
                            @if($hasAS != '')
                                <button
                                    class="hasAS"
                                    hx-get="/annotation/fe/as/{{$token['idAS']}}/{{$token['word']}}"
                                    hx-target="#workArea"
                                    hx-swap="innerHTML"
                                >{{$token['word']}}
                                </button>
                            @else
                                @if($hasLU != '')
                                    <button
                                        class="hasLU"
                                        hx-get="/annotation/fe/lus/{{$idDocumentSentence}}/{{$i}}"
                                        hx-target="#workArea"
                                        hx-swap="innerHTML"
                                    >{{$token['word']}}
                                    </button>
                                @else
                                    {{$token['word']}}
                                @endif
                            @endif
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="annotation-workarea">
                @if(is_null($idAnnotationSet))
                    <div class="annotation-span"></div>
                    <div class="annotation-lu-candidate"></div>
                @else
                    <div
                            class="annotation-panel"
                            hx-trigger="load"
                            hx-get="/annotation/fe/as/{{$idAnnotationSet}}/{{$word}}"
                            hx-target=".annotation-panel"
                            hx-swap="innerHTML"
                    >
                    </div>
                    <div class="annotation-labels">

                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout::index>
