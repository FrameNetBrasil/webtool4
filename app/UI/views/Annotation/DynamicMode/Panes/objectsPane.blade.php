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
        <template x-for="object,index in $store.doStore.objects">
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
                        <div><i class="icon material color_lu">abc</i>
                            <span x-text="object.lu"></span>
                        </div>
                    </template>
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</div>


