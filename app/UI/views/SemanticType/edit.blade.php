<x-layout.edit>
    <x-slot:title>
        SemanticType
    </x-slot:title>
    <x-slot:actions>
        <x-button label="List" color="secondary" href="/semanticType"></x-button>
    </x-slot:actions>
    <x-slot:main>
        <x-layout.object>
            <x-slot:name>
                <div class="flex">
                    <h1><span class="color_semanticType">{{$semanticType?->name}}</span></h1>
                    <hx-disclosure
                        aria-controls="showSTDefinition"
                        class="hxBtn object-show-description"
                    >
                        Description
                    </hx-disclosure>
                </div>
            </x-slot:name>
            <x-slot:detail>
                @if(session('isAdmin'))
                    <x-button
                        label="Delete"
                        color="danger"
                        onclick="manager.confirmDelete(`Removing SemanticType '{{$semanticType?->name}}'.`, '/semanticType/{{$semanticType->idSemanticType}}')"
                    ></x-button>
                @endif
            </x-slot:detail>
            <x-slot:description>
                <hx-reveal id="showSTDefinition">
                    {!! nl2br($semanticType?->description) !!}
                </hx-reveal>
            </x-slot:description>
            <x-slot:nav>
                <x-link-button
                    id="menuEntries"
                    label="Translations"
                    hx-get="/semanticType/{{$semanticType->idSemanticType}}/entries"
                    hx-target="#editMainArea"
                ></x-link-button>
                <x-link-button
                    id="menuSemanticTypes"
                    label="SemanticTypes"
                    hx-get="/semanticType/{{$semanticType->idSemanticType}}/semanticTypes"
                    hx-target="#editMainArea"
                ></x-link-button>
            </x-slot:nav>
            <x-slot:main>
                <div id="editMainArea" class="editMainArea">
                </div>
            </x-slot:main>
        </x-layout.object>
    </x-slot:main>
</x-layout.edit>
