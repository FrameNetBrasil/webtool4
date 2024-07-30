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
            {{$main}}
    </section>
</x-layout.index>
