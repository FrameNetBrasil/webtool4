@props([
    'title' => '',
    'url' => '',
    'data' => [],
    'bordered' => false
])

<!-- Fomantic UI Tree Component with Alpine.js -->
<div class="ui tree-container"
     x-data="treeComponent()"
     x-init="init()"
     data-title="{{ $title }}"
     data-search-endpoint="{{ $url }}"
     data-items="{{ json_encode($data) }}"
>
    <!-- Header -->
    @if($title != '')
        <div class="ui tree-header">{{$title}}</div>
    @endif

    <!-- Loading State -->
    <div x-show="loading" class="ui segment">
        <div class="ui active loader"></div>
        <p>Loading tree data...</p>
    </div>

    <!-- Error State -->
    <div x-show="error && !loading" class="ui negative message">
        <i class="close icon" @click="error = null"></i>
        <div class="header">Error loading data</div>
        <p x-text="error"></p>
    </div>

    <!-- Tree Body -->
    <div class="tree-body" x-show="!loading && !error">
        <table class="ui {{ $bordered ? '' : 'very' }} basic table tree-table">
            <tbody>
            @foreach($data as $item)
                @php(debug($item))
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
                    x-show="expandedNodes && expandedNodes['{{$idNode}}'] === true"
                    x-transition
                >
                    <td></td>
                    <td colspan="100%">
                        <!-- Tree Content Container -->
                        <div id="tree_{{$idNode}}"
                             class="ui tree-content transition">

                            <!-- Content for leaf nodes or expanded nodes -->
                            <div class="ui segment">
                                <p class="ui text muted">
                                    Node content for {{ $item['type'] }} #{{ $item['id'] }}
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
