<form id="" name=""  class="ui form">
    <div class="ui card h-full w-full mb-2">
        
        <div class="flex-grow-1 content bg-white" style="border-bottom: 1px solid rgba(34, 36, 38, 0.1);">
            <input type="hidden" id="idFrame" name="idFrame" value="$idFrame">
        <div class="four fields">
            <div class="field">
                <label for="nameEn">English Name</label>
<div class="ui small input">
    <input
        type="text"
        id="nameEn"
        name="nameEn"
        value=""
        placeholder=""
        
    >
</div>
            </div>
            <div class="field">
                <x-combobox.fe-coreness
                    id="coreType"
                    label="Coreness"
                ></x-combobox.fe-coreness>
            </div>
            <div class="field">
                <x-combobox.color
                    id="idColor"
                    label="Color"
                    value=""
                ></x-combobox.color>
            </div>
        </div>
        </div>
        <div class="flex-grow-0 pl-3 pt-2 pb-2">
            <x-button
            label="Add FE"
            hx-post="/fe"
        ></x-button>
        </div>
    </div>
</form>
