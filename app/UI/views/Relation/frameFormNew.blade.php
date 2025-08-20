<div class="ui card form-card w-full p-1">
    <div class="content">
        <div class="header">
            <x-icon::translate></x-icon::translate>
            Frame Relation
        </div>
        <div class="description">

        </div>
    </div>
    <div class="content">
        <form class="ui form">
            <input type="hidden" name="idFrame" value="{{$idFrame}}">
            <div class="field">
                <x-combobox::relation
                    id="relationType"
                    group="frame"
                    label="Relation"
                ></x-combobox::relation>
            </div>
            <div class="field">
                <x-search::frame
                    id="idFrame"
                    label="Related Frame"
                ></x-search::frame>
            </div>

        </form>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/relation/frame"
        >
            Add Relation
        </button>
    </div>
</div>
