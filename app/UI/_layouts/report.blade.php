<x-layout.page>
    <x-slot:head>
        {{$head}}
    </x-slot:head>
    <x-slot:main>
        <section class="h-full w-full flex flex-row align-content-start ">
            <div class="col-12 sm:col-12 md:col-4 lg:col-3 xl:col-3 h-full">
                <div class="flex flex-column align-content-start gap-2 h-full">
                    <div>
                        {{$search}}
                    </div>
                    <div class="flex-grow-1">
                        {{$grid}}
                    </div>
                </div>

            </div>
            <div class="col-12 sm:col-12 md:col-8 lg:col-9 xl:col-9 h-full pl-3 pr-3 overflow-y-auto">
                 {{$pane}}
            </div>
        </section>
    </x-slot:main>
</x-layout.page>
