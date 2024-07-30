<div
    id="gridUserTaskDocuments"
    class="grid"
    hx-trigger="reload-gridUserTaskDocuments from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/usertask/{{$idUserTask}}/documents/grid"
>
    @foreach($documents as $document)
        <div class="col-4">
            <div class="ui card w-full">
                <div class="content">
                    <span class="right floated">
                        <x-delete
                            title="delete Project"
                            onclick="manager.confirmDelete(`Removing document '{{$document->name}}' from user.`, '/usertask/{{$idUserTask}}/documents/{{$document->idDocument}}')"
                        ></x-delete>
                    </span>
                    <div
                        class="header"
                    >
                        #{{$document->idDocument}}
                    </div>
                    <div class="description">
                        {{$document->name}}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
