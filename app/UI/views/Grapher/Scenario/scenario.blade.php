<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/grapher','Grapher'],['','Scenario']]"></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content" id="grapherApp">
                <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-title">
                            Scenario Grapher
                        </div>
                    </div>
                </div>
                <div class="grapher-controls">
                    <form>
                        <div class="flex flex-row gap-2">
                            <x-combobox.frame-scenario
                                id="idFrame"
                                label=""
                                placeholder="Scenario (min: 3 chars)"
                                value=""
                                :hasDescription="false"
                                style="width:250px"
                            ></x-combobox.frame-scenario>
                            <x-checkbox.relation
                                id="frameRelation"
                                label="Relations to show"
                                :relations="$relations"
                            ></x-checkbox.relation>
                            <div>
                                <x-button
                                    id="btnSubmit"
                                    label="Submit"
                                    hx-target="#graph"
                                    hx-post="/grapher/scenario/graph"
                                ></x-button>
                            </div>
                            <div>
                                <x-button
                                    id="btnClear"
                                    label="Clear"
                                    color="secondary"
                                    hx-target="#graph"
                                    hx-post="/grapher/scenario/graph/0"
                                ></x-button>
                            </div>
                            <div>
                                <x-button
                                    id="btnToogle"
                                    type="button"
                                    label="Grapher options"
                                    color="secondary"
                                    onclick="$('#grapherOptionsModal').modal({detachable: false}).modal('show');"
                                ></x-button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="grapher-canvas">
                    <div id="graph" class="wt-layout-grapher"></div>
                </div>
                @include('Grapher.controls')
                @include('Grapher.report')
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
