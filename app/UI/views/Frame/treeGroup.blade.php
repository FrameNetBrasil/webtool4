@php($field = ($search->byGroup == 'domain') ? "idFramalDomain" : "idFramalType")
<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">
        <div class="datagrid-title">
            {{$group}}
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="domainTable">
            <tbody
            >
            @foreach($groups as $idGroup => $group)
                <tr
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                >
                    <td
                        hx-post="/frame/grid"
                        hx-vals='{"{{$field}}":{{$idGroup}}, "byGroup" : "{{$search->byGroup}}"}'
                        class="cursor-pointer"
                    >
                        {{$group['name']}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

