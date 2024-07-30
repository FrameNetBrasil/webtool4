<div
    id="gridFERelation"
    title=""
    type="child"
    hx-trigger="reload-gridFERelation from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fe/relations/{{$idEntityRelation}}/grid"
>
    @foreach($relations as $relation)
        <div class="col-6">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete FE Relation"
                            onclick="manager.confirmDelete(`Removing FE Relation.`, '/relation/fe/{{$relation->idEntityRelation}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <div class="grid">
                            <div class="col">
                                <x-element.frame
                                    name="{{$frame->name}}"
                                ></x-element.frame>
                                <x-element.fe
                                    name="{{$relation->feName}}"
                                    type="{{$relation->feCoreType}}"
                                    idColor="{{$relation->feIdColor}}"
                                ></x-element.fe>

                            </div>
                            <div class="col">
                                <span class="color_{{$relation->entry}}">{{$relation->relationName}}</span>

                            </div>
                            <div class="col">
                                <x-element.frame
                                    name="{{$relatedFrame->name}}"
                                ></x-element.frame>
                                <x-element.fe
                                    name="{{$relation->relatedFEName}}"
                                    type="{{$relation->relatedFECoreType}}"
                                    idColor="{{$relation->relatedFEIdColor}}"
                                ></x-element.fe>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{--    <x-slot:header>--}}
    {{--        <thead>--}}
    {{--        <td class="wt-datagrid-action">--}}
    {{--        </td>--}}
    {{--        <td>--}}
    {{--            <span>{{$frame->name}}</span>--}}
    {{--        </td>--}}
    {{--        <td>--}}
    {{--            <span>{{$relatedFrame->name}}</span>--}}
    {{--        </td>--}}
    {{--        </thead>--}}
    {{--    </x-slot:header>--}}
    {{--    @foreach($relations as $relation)--}}
    {{--        <tr>--}}
    {{--            <td class="wt-datagrid-action">--}}
    {{--                <div--}}
    {{--                    class="action material-icons-outlined wt-tree-icon wt-icon-delete"--}}
    {{--                    title="delete relation"--}}
    {{--                    hx-delete="/relation/fe/{{$relation['idEntityRelation']}}"--}}
    {{--                ></div>--}}
    {{--            </td>--}}
    {{--            <td>--}}
    {{--                <span class="{{$relation['feIconCls']}}"></span>--}}
    {{--                <span class="color_{{$relation['feIdColor']}}">{{$relation['feName']}}</span>--}}
    {{--            </td>--}}
    {{--            <td>--}}
    {{--                <span class="{{$relation['relatedFEIconCls']}}"></span>--}}
    {{--                <span class="color_{{$relation['relatedFEIdColor']}}">{{$relation['relatedFEName']}}</span>--}}
    {{--            </td>--}}
    {{--        </tr>--}}
    {{--    @endforeach--}}
</div>
