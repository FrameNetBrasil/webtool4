<x-layout.object :center="false">
    <x-slot:name>
        <div class="object-child">
            <x-element.fe
                name="{{$frameElement?->name}}"
                type="{{$frameElement->coreType}}"
                idColor="{{$frameElement->idColor}}"
            ></x-element.fe>
        </div>
    </x-slot:name>
    <x-slot:description>

    </x-slot:description>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$frameElement->idFrameElement}}
        </div>
        <div class="ui label tag wt-tag-en">
            {{$frameElement->nameEn}} [en]
        </div>
    </x-slot:detail>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/fe/{{$frameElement->idFrameElement}}/formEdit"
                hx-target="#objectMainArea">Edit</a>
            <a
                class="item"
                hx-get="/fe/{{$frameElement->idFrameElement}}/entries"
                hx-target="#objectMainArea">Translations</a>
            <a
                class="item"
                hx-get="/fe/{{$frameElement->idFrameElement}}/constraints"
                hx-target="#objectMainArea">Constraints</a>
            <a
                class="item"
                hx-get="/fe/{{$frameElement->idFrameElement}}/semanticTypes"
                hx-target="#objectMainArea">SemanticTypes</a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>

