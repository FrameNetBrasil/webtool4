<x-layout.index>
    <header class="flex">
        <div class="col-7">
            <h1>
                {{$title}}
            </h1>
        </div>
        <div class="col-5 actions">
            {{$actions}}
        </div>
    </header>
    <section id="work" class="flex flex-column align-content-start">
        <div class="grid h-full">
            <div class="col-6 h-full">
                {{$grid}}
            </div>
            <div class="col-6">
                {{$edit}}
            </div>
        </div>
    </section>
</x-layout.index>
