<div class="crud-grid with-sidebar">
    <div class="sidebar-content">
        <div
            hx-trigger="load"
            hx-target="this"
            hx-swap="outerHTML"
            hx-get="/frame/{{$idFrame}}/lus/formNew"
        ></div>
    </div>
    <div class="main-content">
        <div class="table-container">
            <div
                hx-trigger="load"
                hx-target="this"
                hx-swap="outerHTML"
                hx-get="/frame/{{$idFrame}}/lus/grid"
            ></div>
        </div>
    </div>
</div>


