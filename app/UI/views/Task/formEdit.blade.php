<x-form id="formEditTask" title="Task" :center="false" hx-post="/task">
    <x-slot:fields>
        <x-hidden-field id="idTask" value="{{$task->idTask}}"></x-hidden-field>
        <x-text-field
            label="Name"
            id="name"
            value="{{$task->name}}"
        ></x-text-field>
        <x-multiline-field
            label="Description"
            id="description"
            value="{{$task->description}}"
        ></x-multiline-field>
{{--        <x-combobox.project--}}
{{--            id="idProject"--}}
{{--            label="Source project"--}}
{{--            value="{{$task->idProject}}"--}}
{{--        >--}}
{{--        </x-combobox.project>--}}
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
            <div class="form-field field">
                <label for="isActive"></label>
                <div>
                    <input type="checkbox" name="isActive" value="1"  {!! (($task->isActive > 0) ? 'checked' : '')  !!}><span>Is Active?</span>
                </div>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
