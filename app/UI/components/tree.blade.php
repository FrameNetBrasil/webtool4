<!-- AlpineJS Tree Component -->
<div class="tree-container"
     x-data="treeComponent()"
     x-init="init()"
>
    <!-- Header -->
    @if($title != '')
        <div class="tree-header">{{$title}}</div>
    @endif

    <!-- Tree Body -->
    <div class="tree-body">
        <table class="tree-table">
            <tbody>
            @foreach($data as $item)
                <tr
                    class="row-data"
                >
                    @if(!($item['leaf'] ?? false))
                        <!-- Toggle Cell -->
                        <td class="toggle"
                            @click="toggleNode({{$item['id']}})"
                        >
                            <span class="toggle-icon"
                                  :class="expandedNodes[{{$item['id']}}] ? 'expanded' : 'collapsed'"
                            >
                            </span>
                        </td>
                    @else
                        <td></td>
                    @endif

                    <!-- Content Cell -->
                    <td class="content-cell">
                            <span class="tree-item-text"
                                  @click="selectItem({{$item['id']}},'{{$item['type']}}')"
                            >
                                {!! $item['text'] !!}
                            </span>
                    </td>
                </tr>
                <tr
                    id="row_{{$item['type']}}_{{$item['id']}}"
                    :class="expandedNodes[{{$item['id']}}] ? '' : 'hidden'"
                >
                    <td></td>
                    <td>
                        <!-- Tree Content Container -->
                        <div id="tree_{{$item['id']}}"
                             class="tree-content"
                             :class="{ 'hidden': !expandedNodes[{{$item['id']}}] }"
                             x-show="expandedNodes[{{$item['id']}}]"
                             x-transition>

                            <!-- Loading indicator -->
                            <div x-show="loadingNodes[{{$item['id']}}]" class="loading">
                                Loading...
                            </div>

                            <!-- HTMX will populate this area -->
                            <div hx-post="{{$url}}"
                                 hx-vals='{"type": "{{$item['type']}}", "id" : "{{$item['id']}}"}'
                                 hx-target="#tree_{{$item['id']}}"
                                 hx-swap="innerHTML"
                                 hx-trigger="load-{{$item['id']}} from:body"
                                 @htmx:before-request="loadingNodes[{{$item['id']}}] = true"
                                 @htmx:after-request="loadingNodes[{{$item['id']}}] = false; processLoadedContent($event.target)">
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
