{{--<div class="grid ">--}}
{{--    <div class="col">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/lu/{{$idLU}}/constraints/formNew"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--    <div class="col">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/lu/{{$idLU}}/constraints/grid"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--</div>--}}
<h3>LU Constraints</h3>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lu/{{$idLU}}/constraints/formNew"
></div>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/lu/{{$idLU}}/constraints/grid"
></div>

