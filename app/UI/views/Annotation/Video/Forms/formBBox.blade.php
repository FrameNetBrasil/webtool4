<div class="ui card form-card w-full p-1">
    <div class="content">
        <div class="d-flex justify-between">
            <div class="d-flex">
                <div x-show="!bboxDrawn">
                    <div x-show="currentFrame === {!! $object->startFrame !!}">
                        <button
                            id="btnCreateObject"
                            class="ui button primary {!! $object->hasBBoxes ? 'disabled' : '' !!}"
                            @click="$dispatch('bbox-create')"
                        >
                            <i class="plus square outline icon"></i>
                            Create BBox
                        </button>
                    </div>
                </div>
                <div x-show="bboxDrawn">
                    <div class="d-flex">
                        <div>
                            <button
{{--                                class="ui button primary toggle {!! $object->hasBBoxes ? '' : 'disabled' !!}"--}}
                                class="ui button primary toggle"
                                @click="$dispatch('bbox-toggle-tracking')"
                            >
                                <i :class="isTracking ? 'stop icon' : 'play icon'"></i>
                                <span x-text="isTracking ? 'Stop' : 'Track'"></span>
                            </button>
                        </div>
                        <div>
                            <div
                                class="ui checkbox"
                                x-init="$($el).checkbox()"
                                @click="$dispatch('bbox-change-blocked')"
                            >
                                <input
                                    type="checkbox"
                                    tabindex="0"
                                    :checked="bboxDrawn && (bboxDrawn.blocked === 1)"
                                >
                                <label class="pl-6">is blocked?</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div x-show="bboxDrawn">
                <button
                    id="btnDeleteBBox"
                    class="ui medium icon button negative"
                    :class="isTracking && 'disabled'"
                    title="Delete BBoxes from Object"
                    @click.prevent="messenger.confirmDelete('Removing all BBoxes of object #{{$object->idObject}}.', '/annotation/dynamicMode/deleteAllBBoxes/{{$object->idDocument}}/{{$object->idObject}}')"
                >
                    <i class="trash alternate outline icon"></i>
                    Delete All BBoxes
                </button>
            </div>
        </div>
        <div class="d-flex pt-3">
            <div class="ui label">Current BBox: <span
                    x-text="bboxDrawn ? '#' + bboxDrawn.idBoundingBox : 'none' "></span></div>
            <div class="ui label" x-show="bboxDrawn" x-text="bboxDrawn && 'x: ' + bboxDrawn.x"></div>
            <div class="ui label" x-show="bboxDrawn" x-text="bboxDrawn && 'y: ' + bboxDrawn.y"></div>
            <div class="ui label" x-show="bboxDrawn" x-text="bboxDrawn && 'width: ' + bboxDrawn.width"></div>
            <div class="ui label" x-show="bboxDrawn" x-text="bboxDrawn && 'height: ' + bboxDrawn.height"></div>
        </div>
    </div>
</div>

