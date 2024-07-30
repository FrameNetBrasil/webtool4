<x-layout.index>
    <header class="flex">
        <div class="hxSpan-8">
            <h1>
                {{$title}}
            </h1>
        </div>
        <div class="hxSpan-4 text-right">
            {{$actions}}
        </div>
    </header>
    <section id="full" class="flex flex-column align-content-start">
        {{$main}}
    </section>
</x-layout.index>
