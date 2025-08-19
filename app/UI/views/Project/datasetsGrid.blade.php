<div
    class="grid"
    hx-trigger="reload-gridDatasets from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/project/{{$idProject}}/datasets/grid"
>
    @foreach($datasets as $dataset)
        <div class="col-4">
            <div
                class="ui card w-full cursor-pointer"
                hx-target="#editArea"
                hx-get="/dataset/{{$dataset->idDataset}}/edit"
                hx-swap="innerHTML"
            >
                <div class="content">
{{--                    <span class="right floated">--}}
{{--                        <x-delete--}}
{{--                            title="delete Project"--}}
{{--                            x-data \n  @click.prevent="messenger.confirmDelete(`Removing manager '{{$manager->name}}' from project.`, '/project/{{$idProject}}/users/{{$manager->idUser}}')"--}}
{{--                        ></x-delete>--}}
{{--                    </span>--}}
                    <div
                        class="header"
                    >
                        #{{$dataset->idDataset}}
                    </div>
                    <div class="description">
                        {{$dataset->name}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
