<x-layout.edit>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['/frame','Frames'],['',$frame?->name]]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="page-content h-full">
            <div class="content-container h-full">
                <x-layout.object>
                    <x-slot:name>
                        <span class="color_frame">{{$frame?->name}}</span>
                    </x-slot:name>
                    <x-slot:description>

                    </x-slot:description>
                    <x-slot:detail>
                        @foreach ($classification as $name => $values)
                            @foreach ($values as $value)
                                <div
                                    class="sm:pb-1"
                                >
                                    <div class="ui label wt-tag-{{$name}}">
                                        {{$value}}
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                        @if(session('isAdmin'))
                            <x-button
                                label="Delete"
                                color="danger"
                                onclick="manager.confirmDelete(`Removing Frame '{{$frame?->name}}'.`, '/frame/{{$frame->idFrame}}')"
                            ></x-button>
                        @endif
                    </x-slot:detail>
                    <x-slot:description>
                        {{$frame->description}}
                    </x-slot:description>
                    <x-slot:main>
                        @include("Frame.menu")
                    </x-slot:main>
                </x-layout.object>
            </div>
        </div>
    </x-slot:main>
</x-layout.edit>

