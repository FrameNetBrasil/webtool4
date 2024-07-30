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
                        hx-post="/sandbox/tree/grid"
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

<style>
    #domainTableContainer table {
        border-spacing: 0px;
        width:100%;
        flex-grow: 0;
        table-layout: fixed;
    position: absolute; top:0px; bottom:0; left:0; right:0;
    }

    #domainTableContainer tr {
        width:100%;
    }

    #domainTableContainer td {
        padding: 4px;
        word-wrap: break-word;
        width:100%;
    }

    #domainTableContainer tr:nth-child(even) {
        background-color: #EEE;
    }

    #domainTableContainer tr:hover {
        background-color: #dbdbdb;
    }
</style>
