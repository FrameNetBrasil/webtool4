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
                                <x-element::fe :name="$fe->name" :idColor="$fe->idColor" :type="$fe->coreType"></x-element::fe>
                            </div>
                            <div class="description">
                                {{$fe->description}}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{--                <div--}}
            {{--                    id="gridFE"--}}
            {{--                    class="grid"--}}
            {{--                >--}}
            {{--                    @foreach($array as $fe)--}}
            {{--                        <div class="col-3">--}}
            {{--                            <div class="ui card w-full">--}}
            {{--                                <div class="content">--}}
            {{--                    <span class="right floated">--}}
            {{--                        <i--}}
            {{--                            class="red times icon cursor-pointer"--}}
            {{--                            title="delete FE"--}}
            {{--                        ></i>--}}

            {{--                    </span>--}}
            {{--                                    <div--}}
            {{--                                        class="header"--}}
            {{--                                    >--}}
            {{--                                        <a href="/fe/{{$fe->idFrameElement}}/edit">--}}
            {{--                                        <span style="display:inline-block;padding:0px 4px;" class="color_">--}}
            {{--    <span class="inline-block"><i class="circle icon" style="visibility: visible;font-size:0.875em"></i></span>--}}
            {{--</span>--}}

            {{--                                        </a>--}}
            {{--                                    </div>--}}
            {{--                                    <div class="description">--}}
            {{--                                        {{$fe->description}}--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                </div>--}}
        @endif
    @endforeach
</div>
