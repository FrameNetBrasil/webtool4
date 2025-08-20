<div class="d-flex gap-2 w-full">
    <div class="w-1/2">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/classification/formFramalDomain"
        ></div>
    </div>
    <div class="w-1/2">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/classification/formFramalType"
        ></div>
    </div>
</div>


