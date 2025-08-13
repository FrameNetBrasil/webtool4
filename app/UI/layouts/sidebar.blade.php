@php
    use Illuminate\Support\Facades\Auth;
    use App\Data\MenuData;
    use App\Services\AppService;

    $actions = config('webtool.actions');
    $isLogged = Auth::check();
    if ($isLogged) {
        $user = Auth::user();
        $userLevel = session('userLevel');
    }
    $currentLanguage = session('currentLanguage');
    $languages = config('webtool.user')[3]['language'][3];
    $profile = config('webtool.user')[3]['profile'][3];
    $hrefLogin = (env('AUTH0_CLIENT_ID') == 'auth0') ? '/auth0Login' : '/';

@endphp
<aside class="app-sidebar">
    <div class="sidebar-content">
        <nav class="menu">
            @if($isLogged)
                <div class="menu-item" x-data="accordion">
                    <div class="menu-item-header user-menu" @click="toggle">
                        <span class="icon">
                            <i class="material-symbols-outlined" :class="{ 'rotate-180': isOpen }">expand_more</i>
                        </span>
                        <div class="user-info">
                            <div class="user-avatar">
                                <span class="has-text-weight-bold">{!! strtoupper($user->email[0]) !!}</span>
                            </div>
                            <div class="user-details">
                                <div class="user-email has-text-weight-medium">{{$user->email}}</div>
                                <div class="user-level has-text-grey-light is-size-7">{{$userLevel}} #{{$user->idUser}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-content" x-show="isOpen" x-transition>
                        <ul class="menu-list">
                            <li>
                                <a href="/profile" class="menu-link">
                                    <span class="icon">
                                        <i class="material-symbols-outlined">person</i>
                                    </span>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a href="/logout" class="menu-link">
                                    <span class="icon">
                                        <i class="material-symbols-outlined">logout</i>
                                    </span>
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
            
            <div class="menu-section">
                <ul class="menu-list">
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
                        @if (AppService::checkAccess($menuData->group))
                            <li>
                                <a href="{{$menuData->href}}" class="menu-link">
                                    {!! $menuData->label !!}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
    
    <div class="sidebar-footer">
        <div class="content has-text-centered is-size-7 has-text-grey-light">
            {!! config('webtool.footer') !!}
        </div>
    </div>
</aside>