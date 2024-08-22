<div class="grids flex flex-column flex-grow-1">
    <div class="ui pointing secondary menu tabs">
        <a class="item" data-tab="objects">Objects</a>
        <a class="item" data-tab="sentences">Sentences</a>
    </div>
    <div class="gridBody">
        <div class="ui tab active objects p-2" data-tab="objects">
            <div
                id="gridObjects"
                class="grid"
                hx-post="/annotation/dynamicMode/formObject"
                hx-target="#formObject"
                hx-swap="innerHTML"
                hx-trigger="card-click"
                hx-on::config-request="
                    event.detail.parameters.order = event.detail.triggeringEvent.target.getAttribute('data-order');
                    event.detail.parameters.idDynamicObject = event.detail.triggeringEvent.target.getAttribute('data-idDynamicObject');
                    Alpine.store('doStore').selectObject(parseInt(event.detail.parameters.order));
                "
            >
                <template x-for="object,index in objects">
                    <div  class="col-4">
                        <div
                             @click="$dispatch('card-click', this)"
                             :data-order="index + 1"
                             :data-idDynamicObject="object.idDynamicObject"
                             :class="'ui card cursor-pointer w-full ' + ((object.fe === '') ? 'empty' : 'filled')"
                        >
                            <div class="content">
                            <span class="right floated">
                                <x-delete
                                    title="delete Object"
                                    onclick="console.log('a')"
                                ></x-delete>
                            </span>
                                <div
                                    class="header"
                                >
                                    <div
                                    >
                                        <div class="flex">
                                            <div class="objectId" x-text="'#' + (index + 1)"></div>
                                            <div class="frame">
                                                <span x-text="object.startFrame"></span>
                                                <span>/</span>
                                                <span x-text="object.endFrame"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="description">
                                    <template x-if="object.fe !== ''">
                                        <div><i class="icon material color_frame">dashboard</i><span
                                                x-text="object.frame + '.' + object.fe"></span></div>
                                    </template>
                                    <template x-if="object.lu != ''">
                                        <div><i class="icon material color_lu">abc</i><span x-text="object.lu"></span></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
        <div
            class="ui tab sentences p-2"
            data-tab="sentences"
            hx-trigger="load"
            hx-get="/annotation/dynamicMode/sentences/{{$idDocument}}"
        >
            sentences
        </div>

    </div>
    <script type="text/javascript">
        $(".tabs .item")
            .tab()
        ;
    </script>
</div>
