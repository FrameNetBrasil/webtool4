<div
    class="w-full"
    hx-trigger="reload-gridFE from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/fes/grid"
>
    @php($coreType = ['cty_core','cty_core-unexpressed','cty_peripheral','cty_extra-thematic'])
    @foreach($coreType as $ct)
        @php($array = $fes[$ct] ?? [])
        @if(!empty($array))
            <h3 class="ui header">{!! config("webtool.fe.coreness.{$ct}") !!}</h3>
            <div class="card-grid">
                @foreach($array as $fe)
                    <div class="ui card">
                        <div class="content">
                            <span class="right floated">
                                <x-ui::delete url="/fe/delete/{{$fe->idFrameElement}}"></x-ui::delete>
                            </span>
                            <div class="header">
                                <a href="/fe/{{$fe->idFrameElement}}/edit">
                                <x-element::fe :name="$fe->name" :idColor="$fe->idColor" :type="$fe->coreType"></x-element::fe>
                                </a>
                            </div>
                            <div class="description">
                                {{$fe->description}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
</div>
