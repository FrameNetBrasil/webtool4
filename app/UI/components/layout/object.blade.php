@php($center ??= true)
<div class="flex flex-column align-content-start h-full">
    <div class="object-header flex">
        <div class="col-7">
            <h1>
                {{$name}}
            </h1>
        </div>
        <div class="col-5 text-right">
            {{$detail}}
        </div>
    </div>
    <div class="object-description">
        {{$description}}
    </div>
    <div class="flex flex-grow-1">
        <nav class="object-navigation">
            {{$nav}}
        </nav>
        <div class="object-main flex-grow-1">
            {{$main}}
        </div>
    </div>
</div>
