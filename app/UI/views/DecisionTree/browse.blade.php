<x-layout.browser>
    <x-slot:head>
        <x-breadcrumb :sections="[['/','Home'],['','Decision Tree']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:actions>
    </x-slot:actions>
    <x-slot:main>
        <div class="flex flex-column h-full">
            <div class="flex-grow-1 ui card w-full" style="height:50%">
                <div class="flex-grow-1 content h-full">
                    <div id="gridArea" class="h-full"
                         hx-trigger="load"
                         hx-post="/decisiontree/grid"
                    >
                    </div>
                </div>
            </div>
            <div class="flex-grow-1" style="height:50%">
                <div class="ui card w-full h-full">
                    <div class="content">
                        <div class="header">Frames</div>
                    </div>
                    <div id="gridArea" class="content h-full"
                    >
                    </div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout.browser>

