<x-form id="formNewUserTask" title="Add User to Task" :center="false"  hx-post="/task/{{$idTask}}/users/new">
    <x-slot:fields>
        <x-hidden-field id="idTask" value="{{$idTask}}"></x-hidden-field>
        <x-combobox.user
            id="idUser"
            label="User"
            value="0"
        >
        </x-combobox.user>
        <div class="three fields">
            <div class="form-field field">
                <label for="isActive"></label>
                <div>
                    <input type="checkbox" name="isActive" value="1"><span>Is Active?</span>
                </div>
            </div>
            <div class="form-field field">
                <label for="isIgnore"></label>
                <div>
                    <input type="checkbox" name="isIgnore" value="1"><span>Is Ignore?</span>
                </div>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Add"></x-submit>
    </x-slot:buttons>
</x-form>
