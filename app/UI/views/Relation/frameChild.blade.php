<div class="crud-grid with-sidebar">
    <div class="sidebar-content">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$frame->idFrame}}/relations/formNew"
        ></div>
    </div>
    <div class="main-content">
        <div class="table-container">
            <div
                hx-trigger="load"
                hx-target="this"
                hx-swap="outerHTML"
                hx-get="/frame/{{$frame->idFrame}}/relations/grid"
            ></div>
        </div>
    </div>
</div>
