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
    @if($isLogged)
        <div class="nav-section collapsible" data-section="user">
            <div class="section-header">
                <div class="section-title-content">
                    <img src="avatar.jpg" alt="User" class="user-avatar" style="width: 24px; height: 24px; border-radius: 50%; margin-right: 8px;">
                    <span>John Doe</span>
                    <span class="nav-badge danger">3</span>
                </div>
                <i class="dropdown icon collapse-icon"></i>
            </div>
            <div class="section-items">
                <a href="#" class="nav-item">
                    <i class="user icon nav-icon"></i>
                    <span class="nav-text">Profile Settings</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="cog icon nav-icon"></i>
                    <span class="nav-text">Preferences</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="bell icon nav-icon"></i>
                    <span class="nav-text">Notifications</span>
                    <span class="nav-badge danger">3</span>
                </a>
                <div class="nav-divider"></div>
                <a href="#" class="nav-item">
                    <i class="sign out icon nav-icon"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </div>
    @endif
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
