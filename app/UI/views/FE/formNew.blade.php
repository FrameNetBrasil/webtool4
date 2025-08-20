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
        <form class="ui form">
            <input type="hidden" name="idFrame" value="{{$idFrame}}">
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
                ></x-combobox::fe-coreness>
            </div>
            <div class="field">
                <x-combobox::color
                    id="idColor"
                ></x-combobox::color>
            </div>

        </form>
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
