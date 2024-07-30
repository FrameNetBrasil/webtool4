{{--<div class="grid">--}}
{{--    <div class="col-4">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/frame/{{$idFrame}}/fes/formNew"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--    <div class="col-8">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/frame/{{$idFrame}}/fes/grid"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--</div>--}}
<h2>FrameElements</h2>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/fes/formNew"
></div>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/frame/{{$idFrame}}/fes/grid"
></div>

