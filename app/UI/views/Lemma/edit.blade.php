<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/structure','Structure'],['/lemma','Lemmas'],['', 'Lemma #' . $lemma->idLexicon]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container h-full d-flex flex-col">
                <div class="page-header-object">
                    <div class="page-object">
                        <div class="page-object-name">
                            <span>{{$lemma->name}}</span>
                        </div>
                        <div class="page-object-data">
                            <div class="ui label wt-tag-id">
                                #{{$lemma->idLemma}}
                            </div>
                            <button
                                class="ui danger button"
                                x-data
                                @click.prevent="messenger.confirmDelete(`Removing Lemma '{{$lemma->name}}'.`, '/lemma/{{$lemma->idLemma}}')"
                            >Delete
                            </button>
                        </div>
                    </div>
                    <dic class="page-subtitle">
                        Lemma
                    </dic>
                </div>
                <div class="page-content">
                    <x-ui::tabs
                        id="lemmaTabs"
                        style="secondary pointing"
                        :tabs="[
                            'edit' => ['id' => 'edit', 'label' => 'Edit', 'url' => '/lemma/'.$lemma->idLemma.'/formEdit'],
                            'expressions' => ['id' => 'expressions', 'label' => 'Expressions', 'url' => '/lemma/'.$lemma->idLemma.'/expressions'],
                            'pos' => ['id' => 'pos', 'label' => 'POS', 'url' => '/lemma/'.$lemma->idLemma.'/formPOS'],
                        ]"
                        defaultTab="edit"
                    />
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>
