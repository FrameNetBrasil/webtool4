<x-layout.index>
    <header class="flex">
        <div class="hxSpan-8 hxSpan-6-md">
            <h1>
                {{$title}}
            </h1>
        </div>
        <div class="hxSpan-4  hxSpan-6-md text-right">
            {{$actions}}
        </div>
    </header>
    <section id="work" class="flex flex-column align-content-start">
        <div class="flex mb-1">
            <div class="hxSpan-8">
                <h1>
                    {{$name}}
                </h1>
            </div>
            <div class="hxSpan-4 text-right">
                {{$detail}}
            </div>
        </div>
        <div
            class="flex-grow-1">
            {{$pane}}
        </div>
    </section>
</x-layout.index>
