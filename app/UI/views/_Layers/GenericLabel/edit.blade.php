<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/manager','Manager'],['/layers','Layers'],['', 'GenericLabel #' . $genericLabel->idGenericLabel]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container page-edit">
                <div class="page-header-object">
                    <div class="page-object">
                        <div class="page-object-name">
                            <span class="color_genericlabel">{{$genericLabel->name}}</span>
                        </div>
                        <div class="page-object-data">
                            <div class="ui label wt-tag-id">
                                #{{$genericLabel->idGenericLabel}}
                            </div>
                        </div>
                    </div>
                    <div class="page-subtitle">
                        {{$genericLabel->definition}}
                    </div>
                </div>

                <div class="page-content">
                    <x-ui::tabs
                        id="genericLabelTabs"
                        style="secondary pointing"
                        :tabs="[
                            'edit' => ['id' => 'edit', 'label' => 'Edit', 'url' => '/genericlabel/'.$genericLabel->idGenericLabel.'/formEdit']
                        ]"
                        defaultTab="edit"
                    />
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>
