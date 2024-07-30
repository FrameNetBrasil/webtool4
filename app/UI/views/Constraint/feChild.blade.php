{{--<div class="grid ">--}}
{{--    <div class="col">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/fe/{{$idFrameElement}}/constraints/formNew"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--    <div class="col">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/fe/{{$idFrameElement}}/constraints/grid"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div id="feConstraintChildPane"></div>--}}
<h3>FE Constraints</h3>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fe/{{$idFrameElement}}/constraints/formNew"
></div>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/fe/{{$idFrameElement}}/constraints/grid"
></div>
