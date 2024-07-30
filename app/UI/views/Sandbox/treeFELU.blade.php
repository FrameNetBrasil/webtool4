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
                hx-vals='{"{{$field}}":{{$search->$field}}}'
            >Back
            </button>
            {{$currentFrame}}
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="frameFELU">
            <tbody>
            @if(($search->lu == ''))
                @foreach($fes as $idFE => $fe)
                    <tr
                    >
                        <td
                            hx-get="/sandbox/tree"
                            class="cursor-pointer"
                        >
                            <div>
                                <div style="height:1rem;line-height: 1rem;margin:2px 0 2px 0">
                                    <span class="{{$fe['iconCls']}}"></span>
                                    <span class="color_{{$fe['idColor']}}">{{$fe['name'][0]}}</span>
                                </div>
                                <div class='definition'>{{$fe['name'][1]}}</div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            @foreach($lus as $idLU => $lu)
                <tr
                >
                    <td
                        hx-get="/sandbox/tree"
                        class="cursor-pointer"
                    >
                        <div>
                            <div style="height:1rem;line-height: 1rem;margin:2px 0 2px 0">
                                <span class="{{$lu['iconCls']}}"></span>
                                <span>{{$lu['name'][0]}}</span>
                            </div>
                            <div class='definition'>{{$lu['name'][1]}}</div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<style>
    #feluTableContainer table {
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

    #feluTableContainer tr {
        width: 100%;
    }

    #feluTableContainer td {
        padding: 4px;
        word-wrap: break-word;
        width: 100%;
    }

    #feluTableContainer tr:nth-child(even) {
        background-color: #EEE;
    }

    #feluTableContainer tr:hover {
        background-color: #dbdbdb;
    }
</style>

