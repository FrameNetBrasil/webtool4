<div
    class="annotationsContainer"
    hx-trigger="reload-sentence from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/annotation/corpus/annotations/{{$idSentence}}"
>
    @foreach($data['words'] as $i => $token)
        @php($hasAS = isset($token['idAS']) ? ' hasAS ' : '')
        @php($hasLU = $token['hasLU'] ? ' hasLU ' : '')
        <span
            class="word {{$hasLU}}"
            id="{{$i}}">
            @if($hasAS != '')
                <button
                    class="hxBtn hxSm hasAS"
                >{{$token['word']}}
                </button>
            @else
                @if($hasLU != '')
                    <button
                        class="hxBtn hxSm hasLU"
                        hx-get="/annotation/corpus/lus/{{$idSentence}}/{{$i}}"
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
