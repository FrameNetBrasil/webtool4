<x-layout.object :center="false">
    <x-slot:name>
        <div class="object-child">
            <x-element.lu
                name="{{$lu?->name}}"
            ></x-element.lu>
        </div>
    </x-slot:name>
    <x-slot:description>

    </x-slot:description>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$lu->idLU}}
        </div>
        @if($mode== 'edit')
            <div class="ui label tag wt-tag-en">
                {{$lu->frame->name}}
            </div>
        @endif
    </x-slot:detail>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/lu/{{$lu->idLU}}/formEdit"
                hx-target="#objectMainArea">Edit</a>
            <a
                class="item"
                hx-get="/lu/{{$lu->idLU}}/constraints"
                hx-target="#objectMainArea">Constraints</a>
            <a
                class="item"
                hx-get="/lu/{{$lu->idLU}}/semanticTypes"
                hx-target="#objectMainArea">SemanticTypes</a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
