<form
    class="ui form p-4 border"
    hx-post="/annotation/deixis/updateObjectAnnotation"
>
    <x-form::hidden-field id="idDocument" value="{{$object->idDocument}}"></x-form::hidden-field>
    <x-form::hidden-field id="idDynamicObject" value="{{$object?->idDynamicObject}}"></x-form::hidden-field>
    @if(!is_null($object->idGenericLabel) || ($object->layerGroup == 'Deixis'))
        <div class="field w-1/4">
            <x-form::combobox.gl
                id="idGenericLabel"
                name="idGenericLabel"
                label="Label"
                :value="$object?->idGenericLabel ?? 0"
                :idLayerType="$object?->idLayerType ?? 0"
                :hasNull="false"
            ></x-form::combobox.gl>
        </div>
    @endif

    <div class="ui two column stackable grid relative">
        <div class="column pr-8">
            <div class="two fields">
                <div class="field">
                    <label for="idFrame">Frame</label>
                    <div
                        @search-component-change="if ($event.detail.value) { htmx.ajax('GET', '/annotation/deixis/fes/' + $event.detail.value, '#fes'); }">
                        <x-ui::search
                            name="idFrame"
                            display-name="frame"
                            placeholder="Select a frame"
                            search-url="/frame/list/forSelect"
                            value="{{ old('idFrame', $object?->idFrame ?? '') }}"
                            display-value="{{ old('frame', $object->frame ?? '') }}"
                            display-field="name"
                            value-field="idFrame"
                            modal-title="Search Frame"
                        />
                    </div>
                </div>
                <div id="fes" class="field">
                    <x-form::combobox.fe-frame
                        id="idFrameElement"
                        name="idFrameElement"
                        label="FE"
                        :value="$object?->idFrameElement ?? 0"
                        :idFrame="$object?->idFrame ?? 0"
                        :hasNull="false"
                    ></x-form::combobox.fe-frame>
                </div>
            </div>
        </div>
        <div class="column pl-8">
            <div class="field w-full">
                <x-form::search.lu
                    id="idLU"
                    label="CV Name"
                    placeholder="Select a CV name"
                    search-url="/lu/list/forSelect"
                    value="{{ old('idFrame', $object?->idLU ?? '') }}"
                    display-value="{{ old('frame', $object->lu ?? '') }}"
                    modal-title="Search CV Name"
                ></x-form::search.lu>
            </div>
        </div>
        <div class="ui vertical divider">
            and
        </div>
    </div>
    <button type="submit" class="ui medium button">
        Save
    </button>
</form>


{{--    <div class="bg-white pt-1 pr-1 pl-1 overflow-y-auto overflow-x-hidden">--}}
{{--        <div class="flex flex-row gap-1 justify-content-between pb-1">--}}
{{--            @if(is_null($object))--}}
{{--                <div class="ui label">--}}
{{--                    <div class="detail">#none</div>--}}
{{--                </div>--}}
{{--            @else--}}
{{--                <div class="ui label wt-tag-idObject">--}}
{{--                    <div class="detail" x-text="'#' + ($store.doStore.currentObject?.idObject || 'none')"></div>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <button--}}
{{--                        class="ui button basic secondary"--}}
{{--                        @click.prevent="annotation.video.gotoFrame(Alpine.store('doStore').currentStartFrame)"--}}
{{--                    >--}}
{{--                        <span x-text="'Start: ' + currentStartFrame"></span>--}}
{{--                    </button>--}}
{{--                    <button--}}
{{--                        class="ui button basic secondary"--}}
{{--                        @click.prevent="annotation.video.gotoFrame(Alpine.store('doStore').currentEndFrame)"--}}
{{--                    >--}}
{{--                        <span x-text="'End: ' + currentEndFrame"></span>--}}
{{--                    </button>--}}
{{--                    <button--}}
{{--                        class="ui button secondary"--}}
{{--                        @click.prevent="annotation.video.playByFrameRange({{$object->startFrame}},{{$object->endFrame}},0)"--}}
{{--                    ><i class="play icon"></i> Play Range--}}
{{--                    </button>--}}
{{--                    <button--}}
{{--                        class="ui button primary"--}}
{{--                        @click.prevent="annotation.objects.updateObjectRange()"--}}
{{--                    >Update Range--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="flex h-2rem gap-2">--}}
{{--                    <div class="ui label">--}}
{{--                        <div class="detail">{{$object->nameLayerType}}</div>--}}
{{--                    </div>--}}
{{--                    <div class="ui label wt-tag-id">--}}
{{--                        #{{$object->idDynamicObject}}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <button--}}
{{--                        id="btnClose"--}}
{{--                        class="ui medium icon button"--}}
{{--                        title="Close Object"--}}
{{--                        @click.prevent="annotation.objects.closeEdition()"--}}
{{--                    >--}}
{{--                        <i class="pt-1 close icon"></i>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--        @if(!is_null($object))--}}
{{--        @endif--}}
{{--    </div>--}}
{{--    <div class="controls flex flex-row gap-1 justify-content-between">--}}
{{--        <div>--}}
{{--            @if(!is_null($object->idGenericLabel) || ($object->layerGroup == 'Deixis'))--}}
{{--                <button--}}
{{--                    type="button"--}}
{{--                    label="Save"--}}
{{--                    onclick="annotation.objects.updateObjectAnnotation({idGenericLabel: $('#idGenericLabel').attr('value'),idLU: $('#idLU').attr('value'),idFrameElement: $('#idFrameElement').attr('value')})"--}}
{{--                    title="Save Object"--}}
{{--                >Save--}}
{{--                </button>--}}
{{--            @endif--}}
{{--            @if(!is_null($object->idAnnotationLU)  || ($object->layerGroup == 'Deixis_lexical'))--}}
{{--                <button--}}
{{--                    type="button"--}}
{{--                    label="Save"--}}
{{--                    onclick="annotation.objects.updateObjectAnnotation({idLU: $('#idLU').attr('value'),idFrameElement: $('#idFrameElement').attr('value'),})"--}}
{{--                    title="Save Object"--}}
{{--                >Save--}}
{{--                </button>--}}
{{--            @endif--}}
{{--            <button--}}
{{--                class="ui medium icon button negative"--}}
{{--                @click.prevent="annotation.objects.deleteObject({{$object->idDynamicObject}})"--}}
{{--                title="Delete Object"--}}
{{--            >--}}
{{--                <i class="pt-1 trash alternate outline icon"></i>--}}
{{--            </button>--}}
{{--            <button--}}
{{--                label="Comment"--}}
{{--                color="secondary"--}}
{{--                @click.prevent="Alpine.store('doStore').commentObject({{$object->idDynamicObject}})"--}}
{{--            >Comment--}}
{{--            </button>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <button--}}
{{--                id="btnCreateObject"--}}
{{--                class="ui button primary"--}}
{{--                x-data @click="$store.doStore.createBBox()"--}}
{{--            >--}}
{{--                <i class="plus square outline icon"></i>--}}
{{--                Create BBox--}}
{{--            </button>--}}
{{--            <button--}}
{{--                id="btnStartTracking"--}}
{{--                class="ui button primary"--}}
{{--                x-data @click="$store.doStore.startTracking()"--}}
{{--            >--}}
{{--                <i class="play icon"></i>--}}
{{--                Start--}}
{{--            </button>--}}
{{--            <button--}}
{{--                id="btnPauseTracking"--}}
{{--                class="ui button primary"--}}
{{--                x-data @click="$store.doStore.pauseTracking()"--}}
{{--            >--}}
{{--                <i class="pause icon"></i>--}}
{{--                Pause--}}
{{--            </button>--}}
{{--            <button--}}
{{--                id="btnStopObject"--}}
{{--                class="ui button primary"--}}
{{--                x-data @click="$store.doStore.stopTracking()"--}}
{{--            >--}}
{{--                <i class="window stop icon"></i>--}}
{{--                <span x-data x-text="'Stop at #' + ($store.doStore.currentFrame || '')"></span>--}}
{{--            </button>--}}
{{--            <button--}}
{{--                id="btnDeleteBBox"--}}
{{--                class="ui medium icon button negative"--}}
{{--                title="Delete BBoxes from Object"--}}
{{--                @click.prevent="annotation.objects.deleteBBox({{$object->idDynamicObject}})"--}}
{{--            >--}}
{{--                <i class="pt-1 trash alternate outline icon"></i>--}}
{{--            </button>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
