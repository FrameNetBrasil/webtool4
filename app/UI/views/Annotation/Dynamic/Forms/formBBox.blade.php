<div class="flex-container between">
    <div class="flex-container">
            <div>
                <button
                    id="btnCreateObject"
                    class="ui button primary"
                    :class="!canCreateBBox && 'disabled'"
                    @click="createBBox()"
                >
                    <i class="plus square outline icon"></i>
                    Create BBox
                </button>
            </div>
            <div>
                <button
                    class="ui button primary toggle"
                    @click="toggleTracking()"
                    :class="canCreateBBox && 'disabled'"
                >
                    <i :class="isTracking ? 'stop icon' : 'play icon'"></i>
                    <span x-text="isTracking ? 'Stop' : 'Track'"></span>
                </button>
            </div>
            <div>
                <div
                    class="ui checkbox"
                    x-init="$($el).checkbox()"
                    @click="$dispatch('change-bbox-blocked')"
                >
                    <input type="checkbox" tabindex="0" class="hidden">
                    <label class="pl-6">is blocked?</label>
                </div>
            </div>
    </div>
    <div>
        <button
            id="btnDeleteBBox"
            class="ui medium icon button negative"
            :class="isTracking && 'disabled'"
            title="Delete BBoxes from Object"
            @click.prevent="messenger.confirmDelete('Removing all BBoxes of object #{{$object->idDynamicObject}}.', '/annotation/dynamic/deleteAllBBoxes/{{$object->idDocument}}/{{$object->idDynamicObject}}')"
        >
            <i class="trash alternate outline icon"></i>
            Delete All BBoxes
        </button>
    </div>
</div>
