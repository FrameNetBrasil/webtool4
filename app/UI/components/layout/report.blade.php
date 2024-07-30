<x-layout.index-full>
    <header class="flex">
        <div class="col-8 md:col-6">
            <h1>
                {{$title}}
            </h1>
        </div>
    </header>
    <section id="work" class="flex flex-row align-content-start">
        <div class="col-2">
            <div class="flex flex-column align-content-start h-full">
                <div class="h-3rem">
                    {{$search}}
                </div>
                <div class="flex-grow-1">
                    {{$grid}}
                </div>
            </div>

        </div>
        <div class="col-10 pl-3">
            <div class="flex flex-column align-content-start h-full">
            {{$pane}}
            </div>
        </div>
    </section>
</x-layout.index-full>
