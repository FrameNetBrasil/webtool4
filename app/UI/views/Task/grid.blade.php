<div
    class="wt-datagrid flex flex-column"
    style="height:100%"
    hx-trigger="reload-gridTask from:body"
    hx-target="this"
    hx-swap="outerHTML"
    hx-get="/task/grid"
>
    <div class="datagrid-header-search flex">
        <div style="padding:4px 0px 4px 4px">
            <x-search-field
                id="task"
                placeholder="Search Task"
                hx-post="/task/grid/search"
                hx-trigger="input changed delay:500ms, search"
                hx-target="#gridDataset"
                hx-swap="innerHTML"
            ></x-search-field>
        </div>
        <div style="padding:4px 0px 4px 4px">
            <x-search-field
                id="user"
                placeholder="Search User"
                hx-post="/task/grid/search"
                hx-trigger="input changed delay:500ms, search"
                hx-target="#gridDataset"
                hx-swap="innerHTML"
            ></x-search-field>
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="gridTask">
            <tbody
            >
            @fragment('search')
                @foreach($tasks as $idTask => $task)
                    <tr
                        hx-target="#editArea"
                        hx-swap="innerHTML"
                        class="subheader"
                    >
                        <td
                            hx-get="/task/{{$idTask}}/edit"
                            class="cursor-pointer"
                            style="min-width:120px"
                            colspan="3"
                        >
                            <span class="text-blue-900 font-bold">{{$task->name}}</span>
                        </td>
                    </tr>
                    @php($usersForTask = $users[$idTask] ?? [])
                    @foreach($usersForTask as $user)
                        <tr
                            hx-target="#editArea"
                            hx-swap="innerHTML"
                        >
                            <td
                                hx-get="/usertask/{{$user->idUserTask}}/edit"
                                class="cursor-pointer"
                                style="min-width:120px"
                            >
                                <span>{{$user->name}}</span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endfragment
            </tbody>
        </table>
    </div>
</div>
