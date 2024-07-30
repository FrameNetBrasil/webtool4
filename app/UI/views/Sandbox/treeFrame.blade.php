@php($field = ($search->byGroup == 'domain') ? "idFramalDomain" : "idFramalType")
<div class="wt-datagrid flex flex-column" style="height:100%">
    <div class="datagrid-header">

        <div class="datagrid-title">
            <button
                class="btnBack"
                data-go="domainTableContainer"
                hx-target="#gridArea"
                hx-swap="innerHTML"
                hx-post="/sandbox/tree/grid"
            >Back
            </button>
            {{$currentGroup}}
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="frameTable">
            <tbody>
            @foreach($frames as $idFrame => $frame)
                <tr
                    hx-target="#gridArea"
                    hx-swap="innerHTML"
                >
                    <td
                        hx-post="/sandbox/tree/grid"
                        hx-vals='{"idFrame":{{$idFrame}},"{{$field}}":{{$search->$field}}}'
                        class="cursor-pointer"
                    >
                        <div>
                            <div class='color_frame' style="height:1rem;line-height: 1rem;margin:2px 0 2px 0">
                                <span class="{{$frame['iconCls']}}"></span>
                                <span>{{$frame['name'][0]}}</span>
                            </div>
                            <div class='definition'>{{$frame['name'][1]}}</div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
    #frameTableContainer table {
        border-spacing: 0px;
        width: 100%;
        flex-grow: 0;
        table-layout: fixed;
        position: absolute;
        top: 0px;
        bottom: 0;
        left: 0;
        right: 0;
    }

    #frameTableContainer tr {
        width: 100%;
    }

    #frameTableContainer td {
        padding: 4px;
        word-wrap: break-word;
        width: 100%;
    }

    #frameTableContainer tr:nth-child(even) {
        background-color: #EEE;
    }

    #frameTableContainer tr:hover {
        background-color: #dbdbdb;
    }
</style>

