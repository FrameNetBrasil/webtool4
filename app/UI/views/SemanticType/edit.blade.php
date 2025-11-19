<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/semanticType','SemanticType'],['', 'SemanticType #' . $semanticType->idSemanticType]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container page-edit">
                <div class="page-header-object">
                    <div class="page-object">
                        <div class="page-object-name">
                            <span class="color_user">{{$semanticType->name}}</span>
                        </div>
                        <div class="page-object-data">
                            <div class="ui label wt-tag-id">
                                #{{$semanticType->idSemanticType}}
                            </div>
                            <button
                                class="ui danger button"
                                x-data
                                @click.prevent="messenger.confirmDelete(`Removing SemanticType '{{$semanticType->name}}'.`, '/semanticType/{{$semanticType->idSemanticType}}')"
                            >Delete</button>
                        </div>
                    </div>
                    <div class="page-subtitle">
                        {{$semanticType->description ?? ''}}
                    </div>
                </div>

                <div class="page-content">
                    <x-ui::tabs
                        id="corpusTabs"
                        style="secondary pointing"
                        :tabs="[
                            'entries' => ['id' => 'entries', 'label' => 'Translations', 'url' => '/semanticType/'.$semanticType->idSemanticType.'/entries'],
                            'parent' => ['id' => 'parent', 'label' => 'Parent', 'url' => '/semanticType/'.$semanticType->idSemanticType.'/formEdit'],
                        ]"
                        defaultTab="edit"
                    />
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>
