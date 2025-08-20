<div class="ui card form-card w-full p-1">
    <div class="content">
        <div class="header">
            <x-icon::translate></x-icon::translate>
            FrameElement
        </div>
        <div class="description">

        </div>
    </div>
    <div class="content">
        <div class="ui form">
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
                <x-combobox::fe-coreness
                    id="coreType"
                    label="Coreness"
                ></x-combobox::fe-coreness>
            </div>
            <div class="field">
                <x-combobox::color
                    id="idColor"
                    label="Color"
                    value=""
                ></x-combobox::color>
            </div>

        </div>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/fe"
        >
            Add FrameElement
        </button>
    </div>
</div>
