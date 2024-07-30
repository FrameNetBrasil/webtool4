<div
    class="wt-datagrid flex flex-column"
    style="height:100%"
    hx-trigger="reload-gridDataset from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/dataset/grid"
>
    <div class="datagrid-header-search flex">
        <div style="padding:4px 0px 4px 4px">
            <x-search-field
                id="project"
                placeholder="Search Project"
                hx-post="/dataset/grid/search"
                hx-trigger="input changed delay:500ms, search"
                hx-target="#gridDataset"
                hx-swap="innerHTML"
            ></x-search-field>
        </div>
        <div style="padding:4px 0px 4px 4px">
            <x-search-field
                id="dataset"
                placeholder="Search Dataset"
                hx-post="/dataset/grid/search"
                hx-trigger="input changed delay:500ms, search"
                hx-target="#gridDataset"
                hx-swap="innerHTML"
            ></x-search-field>
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="gridDataset">
            <tbody
            >
            @fragment('search')
                @foreach($projects as $idProject => $project)
                    <tr
                        hx-target="#editArea"
                        hx-swap="innerHTML"
                        class="subheader"
                    >
                        <td
                            hx-get="/project/{{$idProject}}/edit"
                            class="cursor-pointer"
                            style="min-width:120px"
                            colspan="3"
                        >
                            <span class="text-blue-900 font-bold">{{$project->name}}</span>
                        </td>
                    </tr>
                    @php($datasetForProject = $datasets[$idProject] ?? [])
                    @foreach($datasetForProject as $dataset)
                        <tr
                            hx-target="#editArea"
                            hx-swap="innerHTML"
                        >
                            <td
                                hx-get="/dataset/{{$dataset->idDataset}}/edit"
                                class="cursor-pointer"
                                style="min-width:120px"
                            >
                                <span>{{$dataset->name}}</span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endfragment
            </tbody>
        </table>
    </div>
</div>
