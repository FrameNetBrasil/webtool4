@php($center ??= true)
<div class="flex flex-column align-content-start">
    <div class="flex">
        <div class="hxSpan-8">
            <h1>
                {{$name}}
            </h1>
        </div>
        <div class="hxSpan-4 text-right">
            {{$detail}}
        </div>
    </div>
    <div class="object-description">
        {{$description}}
    </div>
    <nav class="object-navigation">
        {{$nav}}
    </nav>
    <div>
        {{$main}}
    </div>
</div>
