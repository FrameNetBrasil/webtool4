<!-- AlpineJS Tree Component -->
<div class="tree-container"
     x-data="treeComponent()"
     x-init="init()"
     data-title="{{$title}}"
     data-base-url="{{$url}}"
     data-items='{!! json_encode($data) !!}'
>

    <!-- Header -->
    <div class="tree-header" x-show="title" x-text="title"></div>

    <!-- Tree Body -->
    <div class="tree-body">
        <table class="tree-table">
            <template x-for="item in items" :key="item.id">
                <tbody>
                    <tr>
                        <!-- Toggle Cell -->
                        <td class="toggle"
                            @click="toggleNode(item.id)"
                            :style="`padding-left: ${item.level * 20}px`">
                            <span class="toggle-icon"
                                  :class="expandedNodes[item.id] ? 'expanded' : 'collapsed'"
{{--                                  :class="{ 'expanded': expandedNodes[item.id] }"--}}
                                {{--                                  x-text="expandedNodes[item.id] ? '▼' : '▶'"--}}
                            >
                            </span>
                        </td>

                        <!-- Content Cell -->
                        <td class="content-cell" :id="item.id">
                            <span class="tree-item-text"
                                  :class="{ 'selected': selectedItem === item.id }"
                                  @click="selectItem(item.id)"
                                  x-html="item.text">
                            </span>
                        </td>
                    </tr>
                    <tr
                        :id="`row_${item.type}_${item.id}`"
                        :class="expandedNodes[item.id] ? '' : 'hidden'"
                    >
                        <td></td>
                        <td>
                            <!-- Tree Content Container -->
                            <div :id="`tree_${item.id}`"
                                 class="tree-content"
                                 :class="{ 'hidden': !expandedNodes[item.id] }"
                                 x-show="expandedNodes[item.id]"
                                 x-transition>

                                <!-- Loading indicator -->
                                <div x-show="loadingNodes[item.id]" class="loading">
                                    Loading...
                                </div>

                                <!-- HTMX will populate this area -->
                                <div :hx-get="`${baseUrl}/${item.type}/${item.id}`"
                                     :hx-target="`#tree_${item.id}`"
                                     :hx-swap="'innerHTML'"
                                     :hx-trigger="`load-${item.id} from:body`"
                                     @htmx:before-request="loadingNodes[item.id] = true"
                                     @htmx:after-request="loadingNodes[item.id] = false; processLoadedContent($event.target)">
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </template>
        </table>
    </div>
</div>
