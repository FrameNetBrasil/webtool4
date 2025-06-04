@php
    use Orkester\Security\MAuth;
    use App\Data\MenuData;

    $actions = config('webtool.actions');
    $isLogged = MAuth::isLogged();
    if ($isLogged) {
        $user = MAuth::getLogin();
        $userLevel = session('userLevel');
    }
    $currentLanguage = session('currentLanguage');
    $languages = config('webtool.user')[3]['language'][3];
    $profile = config('webtool.user')[3]['profile'][3];
    $hrefLogin = (env('AUTH0_CLIENT_ID') == 'auth0') ? '/auth0Login' : '/';

@endphp
<nav class="app-sidebar">
    @foreach($actions as $id => $action)
        @php
            $menuData = MenuData::from([
                'id' => $id . '_small',
                'label' => $action[0],
                'href' => $action[1],
                'group' => $action[2],
                'items' => $action[3]
            ]);
        @endphp
        @if (MAuth::checkAccess($menuData->group))
            <div class="nav-section collapsible">
                <div class="section-header">
                    <div class="section-title-content">
                        {!! $menuData->label !!}
                    </div>
                    <i class="chevron down icon collapse-icon"></i>
                </div>
                <div class="section-items">
                    @foreach($menuData->items as $idItem => $item)
                        @php
                            $itemData = MenuData::from([
                                'id' => $idItem . '_small',
                                'label' => $item[0],
                                'href' => $item[1],
                                'group' => $item[2],
                                'items' => $item[3]
                            ]);
                        @endphp
                        <a href="{{$itemData->href}}" class="nav-item">
                            <span class="nav-text">{{$itemData->label}}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
</nav>
<script>
    $(function() {

        // Initialize with custom options
        const sidebar = new CollapsibleSidebar({
            autoSave: true,           // Save state automatically
            searchDelay: 200,         // Search debounce delay
            storageKey: "my-sidebar", // Custom localStorage key

            // Custom selectors if your HTML is different
            sidebarSelector: ".app-sidebar",
            sectionSelector: ".nav-section",
            headerSelector: ".section-header"
        });
    });

</script>
