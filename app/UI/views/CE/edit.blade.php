<x-layout.edit>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['/cxn','Constructions'],['/cxn/' . $constructionElement->cxn->idConstruction,$constructionElement->cxn->name],['',$constructionElement->cxn->name.'.'.$constructionElement?->name]]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <x-layout.object>
            <x-slot:name>
                <x-element.ce
                    name="{{$constructionElement->cxn->name}}.{{$constructionElement?->name}}"
                    idColor="{{$constructionElement->idColor}}"
                ></x-element.ce>
            </x-slot:name>
            <x-slot:detail>
                <div class="ui label wt-tag-id">
                    #{{$constructionElement->idConstructionElement}}
                </div>
                <div class="ui label wt-tag-en">
                    {{$constructionElement->nameEn}} [en]
                </div>
                <div>
                    <x-combobox.fe-frame
                        id="idFrameElement"
                        :idFrame="$constructionElement->cxn->idFrame"
                        :defaultText="'Change FE'"
                    ></x-combobox.fe-frame>
                    <script>
                        $(function() {
                            $('#idFrameElement_dropdown').dropdown({
                                onChange: (value) => {
                                    window.location.href= `/fe/${value}/edit`;
                                }
                            });
                        });
                    </script>
                </div>
            </x-slot:detail>
            <x-slot:description>
                #{{$constructionElement->description}}
            </x-slot:description>
            <x-slot:main>
                @include("FE.menu")
            </x-slot:main>
        </x-layout.object>
    </x-slot:main>
</x-layout.edit>
