<x-layout.edit>
    <x-slot:title>
        Frame
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="secondary" href="/frame"></x-button>
    </x-slot:actions>
    <x-slot:main>
        <x-layout.object>
            <x-slot:name>
                <span class="color_frame">{{$frame?->name}}</span>
            </x-slot:name>
            <x-slot:description>

            </x-slot:description>
            <x-slot:detail>
                @foreach ($classification as $name => $values)
                    @foreach ($values as $value)
                        <div class="ui label tag wt-tag-{{$name}}">
                            {{$value}}
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
            <x-slot:nav>
                <div class="ui vertical menu w-auto">
                    <a
                        class="item"
                        hx-get="/frame/{{$frame->idFrame}}/entries"
                        hx-target="#editMainArea"
                    >
                        Translations
                    </a>
                    <a
                        class="item"
                        hx-get="/frame/{{$frame->idFrame}}/fes"
                        hx-target="#editMainArea"
                    >
                        FrameElements
                    </a>
                    <a
                        class="item"
                        hx-get="/frame/{{$frame->idFrame}}/lus"
                        hx-target="#editMainArea"
                    >
                        LUs
                    </a>
                    <a
                        class="item"
                        hx-get="/frame/{{$frame->idFrame}}/classification"
                        hx-target="#editMainArea"
                    >
                        Classification
                    </a>
                    <a
                        class="item"
                        hx-get="/frame/{{$frame->idFrame}}/relations"
                        hx-target="#editMainArea"
                    >
                        F-F Relations
                    </a>
                    <a
                        class="item"
                        hx-get="/frame/{{$frame->idFrame}}/feRelations"
                        hx-target="#editMainArea"
                    >
                        FE-FE Relations
                    </a>
                    <a
                        class="item"
                        hx-get="/frame/{{$frame->idFrame}}/semanticTypes"
                        hx-target="#editMainArea"
                    >
                        SemanticTypes
                    </a>
                </div>
            </x-slot:nav>
            <x-slot:main>
                <div id="editMainArea" class="editMainArea">
                </div>
            </x-slot:main>
        </x-layout.object>
    </x-slot:main>
</x-layout.edit>
