{{--<div class="grid ">--}}
{{--    <div class="col-4">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/semanticType/{{$idEntity}}/childAdd/{{$root}}"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--    <div class="col-8">--}}
{{--        <div--}}
{{--            hx-trigger="load"--}}
{{--            hx-target="this"--}}
{{--            hx-swap="outerHTML"--}}
{{--            hx-get="/semanticType/{{$idEntity}}/childGrid"--}}
{{--        ></div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div--}}
{{--    id="stChildPane"--}}
{{--    hx-trigger="reload-gridRT from:body"--}}
{{--    hx-target="this"--}}
{{--    hx-swap="innerHTML"--}}
{{--    hx-get="/empty"--}}
{{--></div>--}}

<h2>Semantic Types</h2>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/semanticType/{{$idEntity}}/childAdd/{{$root}}"
></div>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/semanticType/{{$idEntity}}/childGrid"
></div>
