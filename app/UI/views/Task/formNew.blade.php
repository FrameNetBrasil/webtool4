<x-form id="formNewTask" title="New Task" :center="false" hx-post="/task/new">
    <x-slot:fields>
        <div class="field">
            <x-text-field
                label="Name"
                id="name"
                value=""
            ></x-text-field>
        </div>
        <div class="field">
            <x-multiline-field
                label="Description"
                id="description"
                value=""
            ></x-multiline-field>
        </div>
        <div class="field">
            <x-combobox::dataset
                id="idDataset"
                label="Source Dataset"
                value="0"
            >
            </x-combobox::dataset>
        </div>
        <div class="three fields">
            <div class="field">
                <x-text-field
                    label="Type"
                    id="type"
                    value=""
                ></x-text-field>
            </div>
            <div class="field">
                <x-text-field
                    label="Size"
                    id="size"
                    value=""
                ></x-text-field>
            </div>
            <div class="form-field field">
                <label for="isActive"></label>
                <div>
                    <input type="checkbox" name="isActive" value="1"><span>Is Active?</span>
                </div>
            </div>
        </div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
