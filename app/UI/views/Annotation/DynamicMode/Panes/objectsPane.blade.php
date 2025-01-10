<div class="ui tab active objects" data-tab="objects">
    <table id="gridObjects" class="ui compact striped table">
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th class="text-right">#object</th>
            <th  class="text-right">Start</th>
            <th  class="text-right">End</th>
            <th  class="text-right">#id</th>
            <th>FrameElement</th>
            <th>CVName</th>
        </tr>
        </thead>
        <tbody
        >
        <template x-for="object,index in objects">
            <tr
                @click="Alpine.store('doStore').selectObject(index + 1)"
                :class="'cursor-pointer w-full text-color ' + (object.comment ? 'ui yellow' : (object.fe ? 'ui green' : 'ui red'))"
            >
                <td class="w-2rem">
                    <x-delete
                        title="delete Object"
                        @click.stop="annotation.objects.deleteObject(object.idDynamicObject)"
                    ></x-delete>
                </td >
                <td class="w-2rem">
                    <x-comment
                        @click.stop="Alpine.store('doStore').commentObject(object.idDynamicObject)"
                    ></x-comment>
                </td >
                <td class="text-right w-6rem">
                    <div class="objectId" x-text="'#' + (index + 1)"></div>
                </td>
                <td class="text-right w-6rem">
                    <div class="detail"><span x-text="object.startFrame"></span></div>
                </td>
                <td  class="text-right w-6rem">
                    <div class="detail"
                         @click.stop="Alpine.store('doStore').selectObject(index + 1);annotation.video.gotoFrame(object.endFrame)">
                        <span x-text="object.endFrame"></span></div>
                </td>
                <td class="text-right w-6rem">
                    #<span x-text="object.idDynamicObject"></span>
                </td>
                <td>
                    <template x-if="object.fe">
                        <div><i class="icon material color_frame">dashboard</i><span
                                x-text="object.frame + '.' + object.fe"></span></div>
                    </template>
                </td>
                <td>
                    <template x-if="object.lu">
                        <div><i class="icon material color_lu">abc</i><span
                                x-text="object.luFrameName + '.' + object.luName"></span></div>
                    </template>
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</div>


{{--<div class="ui tab active objects p-2" data-tab="objects">--}}
{{--    <div--}}
{{--        id="gridObjects"--}}
{{--        class="grid"--}}
{{--    >--}}
{{--        <template x-for="object,index in objects">--}}
{{--            <div class="col-4">--}}
{{--                <div--}}
{{--                    @click="Alpine.store('doStore').selectObject(index + 1)"--}}
{{--                    :class="'ui card cursor-pointer w-full ' + (object.fe ? 'filled' : 'empty')"--}}
{{--                >--}}
{{--                    <div class="content">--}}
{{--                            <span class="right floated">--}}
{{--                                <x-delete--}}
{{--                                    title="delete Object"--}}
{{--                                    @click.stop="annotation.objects.deleteObject(object.idDynamicObject)"--}}
{{--                                ></x-delete>--}}
{{--                            </span>--}}
{{--                        <div--}}
{{--                            class="header"--}}
{{--                        >--}}
{{--                            <div--}}
{{--                            >--}}
{{--                                <div class="flex">--}}
{{--                                    <div class="objectId" x-text="'#' + (index + 1)"></div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="description">--}}
{{--                            <div class="ui label mb-1">--}}
{{--                                Start--}}
{{--                                <div class="detail"><span x-text="object.startFrame"></span></div>--}}
{{--                            </div>--}}
{{--                            <div class="ui label mb-1">--}}
{{--                                End--}}
{{--                                <div class="detail" @click.stop="Alpine.store('doStore').selectObject(index + 1);annotation.video.gotoFrame(object.endFrame)"><span x-text="object.endFrame"></span></div>--}}
{{--                            </div>--}}
{{--                            <div class="ui label mb-1">--}}
{{--                                #<span x-text="object.idDynamicObject"></span>--}}
{{--                            </div>--}}
{{--                            <template x-if="object.fe">--}}
{{--                                <div><i class="icon material color_frame">dashboard</i><span--}}
{{--                                        x-text="object.frame + '.' + object.fe"></span></div>--}}
{{--                            </template>--}}
{{--                            <template x-if="object.lu">--}}
{{--                                <div><i class="icon material color_lu">abc</i><span x-text="object.luFrameName + '.' + object.luName"></span></div>--}}
{{--                            </template>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </template>--}}
{{--    </div>--}}
{{--</div>--}}
