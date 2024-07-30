<div
    style="display:none"
>
    @foreach($data['layerLabels'] as $entry => $idLabels)
        @if($entry == 'lty_fe')
            @foreach($idLabels as $idAnnotationSet => $idFELabels)
                <div id="menu_{{$entry}}_{{$idAnnotationSet}}" class="corpusAnnotationMenu menu-content">
                    <div class="container">
                        <div class="itemMenu" data-idlabeltype="0">
                            <x-icon icon="delete">Clear</x-icon>
                        </div>
                        @foreach($idFELabels as $idLabel)
                            <div
                                class="itemMenu color_{{$data['labelTypes'][$idLabel]['idColor']}}"
                                data-idlabeltype="{{$idLabel}}"
                            >
                                <x-icon icon="fe-{{$data['labelTypes'][$idLabel]['coreType']}}">
                                    <span>{{$data['labelTypes'][$idLabel]['label']}}</span>
                                </x-icon>
                            </div>
                        @endforeach
                    </div>
                </div>
                <script>
                    $(function() {
                        $('#menu_{{$entry}}_{{$idAnnotationSet}}').menu();
                    });
                </script>
            @endforeach
        @else
            <div id="menu_{{$entry}}" class="corpusAnnotationMenu menu-content">
                <div class="container">
                    <div class="itemMenu" data-idlabeltype="0">
                        <x-icon icon="delete">Clear</x-icon>
                    </div>
                    @foreach($idLabels as $idLabel)
                        <div class="itemMenu color_{{$data['labelTypes'][$idLabel]['idColor']}}"
                             data-idlabeltype="{{$idLabel}}">
                            {{$data['labelTypes'][$idLabel]['label']}}
                        </div>
                    @endforeach
                </div>
            </div>
            <script>
                $(function() {
                    $('#menu_{{$entry}}').menu();
                });
            </script>
        @endif
    @endforeach
</div>
