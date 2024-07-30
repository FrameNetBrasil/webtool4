{{--<div class="grid ">--}}
{{--    <div class="col">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/frame/{{$idFrame}}/feRelations/formNew"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--    <div class="col">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/frame/{{$idFrame}}/feRelations/grid"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--</div>--}}
<h2>Intraframe FE-FE Relations</h2>
<div class="grid">
    <div
        class="col-4"
    >
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/feRelations/formNew"
        ></div>
    </div>
    <div
        class="col-8"
    >
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/feRelations/grid"
        ></div>
    </div>
</div>

