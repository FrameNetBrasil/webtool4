<div class="grid h-full">
    <div id="frameTableContainer" class="col">
        <div class="wt-datagrid flex flex-column" style="height:100%">
            <div class="table" style="position:relative;height:100%">
                <table id="frameTable">
                    <tbody>
                    @foreach($frames as $idFrame => $frame)
                        <tr
                        >
                            <td
                                {{--                                hx-get="/report/frame/content/{{$idFrame}}"--}}
                                {{--                                hx-get="/report/frame/{{$idFrame}}"--}}
                                {{--                                class="cursor-pointer name"--}}
                                {{--                                hx-target="#reportArea"--}}
                                {{--                                hx-swap="innerHTML"--}}
                            >
                                <a
                                    href="/report/frame/{{$idFrame}}"
                                >
                                    <div>
                                        <div class="flex justify-content-between">
                                            <div class='color_frame'
                                                 style="height:1rem;line-height: 1rem;margin:2px 0 2px 0">
                                                <span class="{{$frame['iconCls']}}"></span>
                                                <span>{{$frame['name'][0]}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
