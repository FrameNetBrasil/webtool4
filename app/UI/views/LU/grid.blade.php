<div
    class="w-full"
    hx-trigger="reload-gridLU from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/lus/grid"
>
    @foreach($lus as $udPOS => $array)
        <h3 class="ui header">{{$udPOS}}</h3>
        <div class="card-grid">
            @foreach($array as $lu)
                <div class="ui card">
                    <div class="content">
                            <span class="right floated">
                                <x-ui::delete
                                    message="Removing LU '{{$lu->name}}'"
                                    url="/lu/{{$lu->idLU}}"
                                ></x-ui::delete>
                            </span>
                        <div class="header">
                            <a href="/lu/{{$lu->idLU}}/edit">
                                <x-element::lu :name="$lu->name"></x-element::lu>
                            </a>
                        </div>
                        <div class="description">
                            {{$lu->senseDescription}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
