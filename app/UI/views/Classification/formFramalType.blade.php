<div class="ui card form-card w-full p-1">
    <div class="content">
        <div class="header">
            <x-icon::translate></x-icon::translate>
            Type
        </div>
        <div class="description">

        </div>
    </div>
    <div class="content">
        <form class="ui form">
            <input type="hidden" name="idFrame" value="{{$idFrame}}">
            <div class="field">
                <x-checkbox::framal-type
                    id="framalType"
                    :idFrame="$idFrame"
                    label=""
                ></x-checkbox::framal-type>
            </div>
        </form>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/frame/classification/type"
        >
            Update Type
        </button>
    </div>
</div>
