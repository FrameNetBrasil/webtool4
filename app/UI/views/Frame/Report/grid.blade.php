<div class="h-full">
    <div style="position:relative;height:100%;overflow:auto">
        <table class="ui striped small compact table" style="position:absolute;top:0;left:0;bottom:0;right:0">
            <tbody>
            @foreach($frames as $idFrame => $frame)
                <tr
                >
                    <td
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
