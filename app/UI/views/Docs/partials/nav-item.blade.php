@if($item['leaf'])
    <li class="nav-item">
        <a href="/docs/{{ $item['path'] }}"
           class="{{ isset($currentPath) && $currentPath === $item['path'] ? 'active' : '' }}">
            {{ $item['text'] }}
        </a>
    </li>
@elseif($item['type'] === 'folder')
    <li class="nav-item">
        <div class="folder-title">{{ $item['text'] }}</div>
        <ul class="nav-nested">
            @foreach(App\Services\DocsService::buildTree($item['path']) as $child)
                @include('Docs.partials.nav-item', ['item' => $child, 'currentPath' => $currentPath ?? null])
            @endforeach
        </ul>
    </li>
@endif
