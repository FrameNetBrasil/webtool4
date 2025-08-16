<!-- Fomantic UI Tree Component with Alpine.js -->
<div class="ui tree-container"
     x-data="treeComponent()"
     x-init="init()"
>
    <!-- Header -->
    @if($title != '')
        <div class="ui tree-header">{{$title}}</div>
    @endif

    <!-- Tree Body -->
    <div class="tree-body">
        <table class="ui basic table tree-table">
            <tbody>
            @foreach($data as $item)
                @php($idNode = $item['type'] . '_' . $item['id'])
                <tr class="row-data transition-enabled">
                    @if(!($item['leaf'] ?? false))
                        <!-- Toggle Cell -->
                        <td class="toggle center aligned"
                            @click="toggleNode('{{$idNode}}')"
                        >
                            <i class="toggle-icon transition"
                               :class="expandedNodes['{{$idNode}}'] ? 'expanded' : 'collapsed'"
                            ></i>
                        </td>
                    @else
                        <td class="center aligned">
                            <i class="ui icon"></i>
                        </td>
                    @endif

                    <!-- Content Cells -->
                    @if(isset($item['formatedId']))
                        <td class="content-cell">
                            <span class="ui tree-item-text clickable"
                                  @click="selectItem({{$item['id']}},'{{$item['type']}}')"
                            >
                                {!! $item['formatedId'] !!}
                            </span>
                        </td>
                    @endif
                    @if(isset($item['extra']))
                        <td class="content-cell">
                            <span class="ui tree-item-text clickable"
                                  @click="selectItem({{$item['id']}},'{{$item['type']}}')"
                            >
                                {!! $item['extra'] !!}
                            </span>
                        </td>
                    @endif
                    <td class="content-cell">
                        <span class="ui tree-item-text clickable"
                              @click="selectItem({{$item['id']}},'{{$item['type']}}')"
                        >
                            {!! $item['text'] !!}
                        </span>
                    </td>
                </tr>
                <tr id="row_{{$idNode}}"
                    class="tree-content-row"
                    :class="expandedNodes['{{$idNode}}'] ? '' : 'hidden'"
                >
                    <td></td>
                    <td colspan="100%">
                        <!-- Tree Content Container -->
                        <div id="tree_{{$idNode}}"
                             class="ui tree-content transition"
                             :class="{ 'hidden': !expandedNodes['{{$idNode}}'] }"
                             x-show="expandedNodes['{{$idNode}}']"
                             x-transition>

                            <!-- Fomantic UI Loading indicator -->
                            <div x-show="loadingNodes['{{$idNode}}']" class="ui loading">
                                <div class="ui active mini inline loader"></div>
                                <span class="ui text muted">Loading...</span>
                            </div>

                            <!-- HTMX will populate this area -->
                            <div hx-post="{{$url}}"
                                 hx-vals='{"type": "{{$item['type']}}", "id" : "{{$item['id']}}"}'
                                 hx-target="#tree_{{$idNode}}"
                                 hx-swap="innerHTML"
                                 hx-trigger="load-{{$idNode}} from:body"
                                 @htmx:before-request="loadingNodes['{{$idNode}}'] = true"
                                 @htmx:after-request="loadingNodes['{{$idNode}}'] = false; processLoadedContent($event.target)">
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
