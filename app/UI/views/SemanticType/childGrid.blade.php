<div
    id="gridSemanticType"
    class="grid"
    hx-trigger="reload-gridChildST from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/semanticType/{{$idEntity}}/childGrid"
>
    @foreach($relations as $relation)
        <div class="col-3">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete SemanticType"
                            onclick="manager.confirmDelete(`Removing SemanticType '{{$relation->name}}'.`, '/semanticType/{{$relation->idEntityRelation}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        <x-element.semantictype name="{{$relation->name}}"></x-element.semantictype>
                    </div>
                    <div class="description">
                    </div>
                </div>
            </div>
        </div>
{{--        <tr>--}}
{{--            <td class="wt-datagrid-action">--}}
{{--                <div--}}
{{--                    class="action material-icons-outlined wt-datagrid-icon wt-icon-delete"--}}
{{--                    title="delete relation"--}}
{{--                    hx-delete="/semanticType/{{$relation->idEntityRelation}}"--}}
{{--                ></div>--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                <span>{{$relation->name}}</span>--}}
{{--            </td>--}}
{{--        </tr>--}}
    @endforeach
</div>
