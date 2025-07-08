<x-layout.report>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','LU Report']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:search>
        <x-form-search
            id="luSearch"
            hx-post="/report/lu/grid"
            hx-target="#gridArea"
        >
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <x-search-field
                id="lu"
                :value="$search->lu"
                placeholder="Search LU"
                class="w-full"
            ></x-search-field>
        </x-form-search>
    </x-slot:search>
    <x-slot:grid>
        <div
            id="gridArea"
            class="h-full"
            hx-trigger="load"
            hx-post="/report/lu/grid"
        >
        </div>
    </x-slot:grid>
    <x-slot:pane>
        <div
            id="reportArea"
            class="h-full overflow-y-auto"
            @if(isset($idLU))
                hx-trigger="load"
                hx-get="/report/lu/content/{{$idLU}}"
            @endif
        >
        </div>
        <script>
            let reportLU = {};
            window.player = {};
            document.addEventListener('alpine:init', () => {
                window.doStore = Alpine.store('doStore', {
                    idVideoJs: 'videoContainer',
                    idVideo: 'videoContainer_html5_api',
                    fps: 25, // frames por segundo
                    timeInterval: 1 / 25, // intervalo entre frames - 0.04s = 40ms
                    originalDimensions: {
                        width: 852,
                        height: 480
                    },
                    timeCount: 0,
                    currentTime: 0,
                    currentFrame: 0,
                    timeByFrame: 0,
                    frameCount: 1,
                    timeFormated: (timeSeconds) => {
                        let minute = Math.trunc(timeSeconds / 60);
                        let seconds = Math.trunc(timeSeconds - (minute * 60));
                        return minute + ':' + seconds;
                    },
                    timeFromFrame: (frame) => {
                        return (frame - 1) * 0.04;
                    },
                    setCurrentFrame(frame) {
                        this.currentFrame = frame;
                        this.currentTime = (frame - 1) * 0.04;
                    }
                })
            });

        </script>
    </x-slot:pane>
</x-layout.report>
