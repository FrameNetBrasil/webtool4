<div class="ui card form-card w-full p-1">
{{--    <div class="content">--}}
{{--        <div class="header">--}}
{{--            <x-icon::annotation></x-icon::annotation>--}}
{{--            Annotation--}}
{{--        </div>--}}
{{--        <div class="description">--}}

{{--        </div>--}}
{{--    </div>--}}
    <div class="content">
        <form class="ui form">
            <input type="hidden" name="idDocument" value="{{$object->idDocument}}">
            <input type="hidden" name="idDynamicObject" value="{{$object?->idDynamicObject}}">
            <div class="ui two column stackable grid relative">
                <div class="column pr-8">
                    <x-ui::frame-fe
                        :object="$object"
                    ></x-ui::frame-fe>
                </div>
                <div class="column pl-8">
                    <div class="field w-full">
                        <x-search::lu
                            id="idLU"
                            label="CV Name"
                            placeholder="Select a CV name"
                            search-url="/lu/list/forSelect"
                            value="{{ old('idFrame', $object?->idLU ?? '') }}"
                            display-value="{{ old('frame', $object->lu ?? '') }}"
                            modal-title="Search CV Name"
                        ></x-search::lu>
                    </div>
                </div>
                <div class="ui vertical divider">
                    and
                </div>
            </div>
        </form>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/annotation/dynamicMode/updateObjectAnnotation"
            hx-target="#o{{$object?->idDynamicObject}}"
            hx-swap="innerHTML"
        >
            Save
        </button>
    </div>
</div>


{{--<form--}}
{{--    class="ui form p-4 border"--}}
{{-->--}}
{{--    <input type="hidden" name="idDocument" value="{{$object->idDocument}}">--}}
{{--    <input type="hidden" name="idDynamicObject" value="{{$object?->idDynamicObject}}">--}}
{{--    <div class="ui two column stackable grid relative">--}}
{{--        <div class="column pr-8">--}}
{{--            <x-form::frame-fe--}}
{{--                :object="$object"--}}
{{--            ></x-form::frame-fe>--}}
{{--        </div>--}}
{{--        <div class="column pl-8">--}}
{{--            <div class="field w-full">--}}
{{--                <x-form::search.lu--}}
{{--                    id="idLU"--}}
{{--                    label="CV Name"--}}
{{--                    placeholder="Select a CV name"--}}
{{--                    search-url="/lu/list/forSelect"--}}
{{--                    value="{{ old('idFrame', $object?->idLU ?? '') }}"--}}
{{--                    display-value="{{ old('frame', $object->lu ?? '') }}"--}}
{{--                    modal-title="Search CV Name"--}}
{{--                ></x-form::search.lu>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="ui vertical divider">--}}
{{--            and--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <button--}}
{{--        type="submit"--}}
{{--        class="ui medium button"--}}
{{--        hx-post="/annotation/dynamicMode/updateObjectAnnotation"--}}
{{--        hx-target="#o{{$object?->idDynamicObject}}"--}}
{{--        hx-swap="innerHTML"--}}
{{--    >--}}
{{--        Save--}}
{{--    </button>--}}
{{--</form>--}}
