<h2>Projects</h2>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/dataset/{{$idDataset}}/projects/formNew"
></div>
<div
    hx-trigger="load"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/dataset/{{$idDataset}}/projects/grid"
></div>

