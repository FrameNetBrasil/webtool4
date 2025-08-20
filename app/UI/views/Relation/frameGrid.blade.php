<div
    class="w-full"
    hx-trigger="reload-gridFrameRelation from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/relations/grid"
>
    @php($i = 0)
    @foreach ($relations as $nameEntry => $relations1)
        @php([$entry, $name] = explode('|', $nameEntry))
        @php($relId = str_replace(' ', '_', $name))
        <h3 class="ui header"><span class='color_{{$entry}}'>{{$name}}</span></h3>
        <div class="card-grid">
            @foreach ($relations1 as $idRelatedFrame => $relation)
                <div class="ui card">
                    <div class="content">
                            <span class="right floated">
                                <x-ui::delete
                                    message="Removing Relation '{{$name}} {{$relation['name']}}'"
                                    url="/relation/frame/{{$relation['idEntityRelation']}}"
                                ></x-ui::delete>
                            </span>
                        <div class="header">
                            <a
                                href="/frame/{{$idRelatedFrame}}"
                            >
                                <x-element::frame name="{{$relation['name']}}"></x-element::frame>
                            </a>
                        </div>
                        <div class="description">
                            <a
                                hx-target="#gridFrameRelationsContent"
                                hx-swap="innerHTML"
                                hx-get="/fe/relations/{{$relation['idEntityRelation']}}/frame/{{$idFrame}}"
                            >
                                Edit FE-FE
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
</div>

{{--<div--}}
{{--    id="gridFrameRelations"--}}
{{--    class="ui card h-full w-full mb-2"--}}
{{--    hx-trigger="reload-gridFrameRelation from:body"--}}
{{--    hx-target="this"--}}
{{--    hx-swap="outerHTML"--}}
{{--    hx-get="/frame/{{$idFrame}}/relations/grid"--}}
{{-->--}}
{{--    <div class="flex-grow-1 content bg-white">--}}

{{--        <div--}}
{{--            id="gridFrameRelationsContent"--}}
{{--            --}}{{--            class="grid"--}}
{{--        >--}}
{{--            @php($i = 0)--}}
{{--            @foreach ($relations as $nameEntry => $relations1)--}}
{{--                @php([$entry, $name] = explode('|', $nameEntry))--}}
{{--                @php($relId = str_replace(' ', '_', $name))--}}
{{--                <x-card-plain--}}
{{--                    title="<span class='color_{{$entry}}'>{{$name}}</span>"--}}
{{--                    @class(["frameReport__card" => (++$i < count($report['relations']))])--}}
{{--                    class="frameReport__card--internal">--}}
{{--                    <div class="flex flex-wrap gap-1">--}}
{{--                        @foreach ($relations1 as $idRelatedFrame => $relation)--}}
{{--                            <button--}}
{{--                                id="btnRelation_{{$relId}}_{{$idRelatedFrame}}"--}}
{{--                                class="ui button basic grey"--}}
{{--                            >--}}
{{--                                <div--}}
{{--                                    class="flex align-items-center "--}}
{{--                                >--}}
{{--                                    <a--}}
{{--                                        href="/frame/{{$idRelatedFrame}}"--}}
{{--                                        class="font-bold"--}}
{{--                                    >--}}
{{--                                        <x-element::frame name="{{$relation['name']}}"></x-element::frame>--}}
{{--                                    </a>--}}
{{--                                    <a--}}
{{--                                        hx-target="#gridFrameRelationsContent"--}}
{{--                                        hx-swap="innerHTML"--}}
{{--                                        hx-get="/fe/relations/{{$relation['idEntityRelation']}}/frame/{{$idFrame}}"--}}
{{--                                        class="fe-fe cursor-pointer right pl-2"--}}
{{--                                    >--}}
{{--                                        FE-FE--}}
{{--                                    </a>--}}
{{--                                    <div class="right pl-2">--}}
{{--                                        <x-delete--}}
{{--                                            title="delete Relation"--}}
{{--                                            x-data \n  @click.prevent="messenger.confirmDelete(`Removing Relation '{{$name}} {{$relation['name']}}'.`, '/relation/frame/{{$relation['idEntityRelation']}}')"--}}
{{--                                        ></x-delete>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </button>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </x-card-plain>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
