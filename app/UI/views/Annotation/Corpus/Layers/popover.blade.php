@foreach($data['layerLabels'] as $entry => $idLabels)
    @if($entry == 'lty_fe')
        @foreach($idLabels as $idAnnotationSet => $idFELabels)
            <hx-popover id="menu_{{$entry}}_{{$idAnnotationSet}}" class="popover" position="top-center">
                <div class="flex flex-wrap w-full gap-1 container">
                    <div class="itemMenu clear" data-idlabeltype="0">
                        <x-icon icon="delete">Clear</x-icon>
                    </div>
                    @foreach($idFELabels as $idLabel)
                        <div
                            class="itemMenu color_{{$data['labelTypes'][$idLabel]['idColor']}}"
                            data-idlabeltype="{{$idLabel}}"
                        >
                            <x-element.fe
                                name="{{$data['labelTypes'][$idLabel]['label']}}"
                                type="{{$data['labelTypes'][$idLabel]['coreType']}}"
                                idColor="{{$data['labelTypes'][$idLabel]['idColor']}}"
                            ></x-element.fe>
                        </div>
                    @endforeach
                </div>
            </hx-popover>
        @endforeach
        @foreach($idLabels as $idAnnotationSet => $idFELabels)
            <hx-popover id="menu_ni_{{$idAnnotationSet}}" class="popover" position="top-center">
                <div class="flex flex-wrap w-full gap-1 container">
                    @foreach($idFELabels as $idLabel)
                        @if(($data['labelTypes'][$idLabel]['coreType'] == 'cty_core') || ($data['labelTypes'][$idLabel]['coreType'] == 'cty_core-unexpressed'))
                            <div
                                class="itemMenu color_{{$data['labelTypes'][$idLabel]['idColor']}}"
                                data-idlabeltype="{{$idLabel}}"
                            >
                                <x-element.fe
                                    name="{{$data['labelTypes'][$idLabel]['label']}}"
                                    type="{{$data['labelTypes'][$idLabel]['coreType']}}"
                                    idColor="{{$data['labelTypes'][$idLabel]['idColor']}}"
                                ></x-element.fe>
                            </div>
                        @endif
                    @endforeach
                </div>
            </hx-popover>
        @endforeach
        <hx-popover id="menu_ni_delete" class="popover" position="top-center" style="min-width:5rem">
            <div class="container">
                <div class="itemMenu clear" data-idlabeltype="0">
                    <x-icon icon="delete">Clear</x-icon>
                </div>
            </div>
        </hx-popover>
    @else
        <hx-popover id="menu_{{$entry}}" class="popover" position="top-center">
            <div class="flex flex-wrap w-full gap-1 container">
                @if(($entry != 'lty_gf') && ($entry != 'lty_pt'))
                    <div class="itemMenu clear" data-idlabeltype="0">
                        <x-icon icon="delete">Clear</x-icon>
                    </div>
                @endif
                @foreach($idLabels as $idLabel)
                    <div
                        class="itemMenu color_{{$data['labelTypes'][$idLabel]['idColor']}}"
                        data-idlabeltype="{{$idLabel}}"
                    >
                        <x-element.generic-label
                            name="{{$data['labelTypes'][$idLabel]['label']}}"
                            idColor="{{$data['labelTypes'][$idLabel]['idColor']}}"
                        ></x-element.generic-label>
                    </div>
                @endforeach
            </div>
        </hx-popover>
    @endif
@endforeach
<script>
    window.popover = {
        idPopover: null,
        rowIndex: null,
        type: null, // fe, ni, other
        colIndex: null,
        active: false,
        data: null,
        open: (idPopover, type, rowIndex, colIndex, data) => {
            console.log("idPopover", idPopover);
            let popovers = document.getElementsByClassName("popover");
            document.dispatchEvent(new CustomEvent("popoverToggle", { detail: null }));
            for (let index = 0; index < popovers.length; index++) {
                popovers[index].open = false;
            }
            let id = "l_" + rowIndex + "_" + colIndex;
            popover.idPopover = idPopover;
            popover.rowIndex = rowIndex;
            popover.type = type;
            popover.colIndex = colIndex;
            popover.data = data || null;
            let elPopover = document.getElementById(idPopover);
            elPopover.relativeTo = id;
            elPopover.open = true;
            elPopover.focus();
            setTimeout(
                () => document.dispatchEvent(new CustomEvent("popoverToggle", { detail: idPopover })),
                20);

            popover.active = true;
        },
        close: () => {
            if (popover.active) {
                let elPopover = document.getElementById(popover.idPopover);
                document.dispatchEvent(new CustomEvent("popoverToggle", { detail: null }));
                elPopover.open = false;
                popover.idPopover = null;
                popover.rowIndex = null;
                popover.type = null;
                popover.colIndex = null;
                popover.active = false;
            }
        }
    };


    $(function() {

        let idPopover;
        document.addEventListener("popoverToggle", ({ detail }) => idPopover = detail);
        document.addEventListener("click", function(e) {
            console.log(window.popover.idPopover);
            if (!idPopover) {
                return;
            }
            const el = document.getElementById(idPopover);
            if (!el) {
                return;
            }
            if (!el.contains(e.target)) {
                console.log("aaa");
                window.popover.close();
                idPopover = null;
            }

        });

        $(".itemMenu").click((e => {
                annotation.onLabelClick($(e.currentTarget).data().idlabeltype);
            })
        );
    });
</script>
