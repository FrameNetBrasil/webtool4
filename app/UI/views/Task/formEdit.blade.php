<x-form
    title="Task"
    hx-post="/task"
>
    <x-slot:fields>
        <x-hidden-field id="idTask" value="{{$task->idTask}}"></x-hidden-field>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                value="{{$task->name}}"
            ></x-text-field>
        </div>
        <div class="field">
            <x-multiline-field
                label="Description"
                id="description"
                value="{{$task->description}}"
            ></x-multiline-field>
        </div>
        <div class="three fields">
            <div class="field">
                <x-text-field
                    label="Type"
                    id="type"
                    value="{{$task->type}}"
                ></x-text-field>
            </div>
            <div class="field">
                <x-text-field
                    label="Size"
                    id="size"
                    value="{{$task->size}}"
                ></x-text-field>
            </div>
            <div class="field">
                <div class="ui checkbox">
                    <input type="checkbox" name="isActive" class="hidden"
                           value="1" {!! (($task->isActive > 0) ? 'checked' : '')  !!}>
                    <label for="isActive">Is Active?</label>
                </div>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
