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
<script src="/scripts/app/sidebar.js"></script>
<div class="app-sidebar">
    <div class="main">

        <div class="ui secondary vertical menu">
            @if($isLogged)
                <div class="ui accordion">
                    <div class="title d-flex flex-row user-menu item">
                        <div class="user-avatar">{!! strtoupper($user->email[0]) !!}</div>
                        <div class="user-email">{{$user->email}}
                            <div class="user-level">{{$userLevel}} #{{$user->idUser}}</div>
                        </div>
                    </div>
                    <div class="content">
                        <a class="item" href="/profile">
                            Profile
                        </a>
                        <a class="item" href="/logout">
                            Logout
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
                    <a class="item" href="{{$menuData->href}}">
                        {!! $menuData->label !!}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
<div class="app-sidebar-footer">
    {!! config('webtool.footer') !!}
</div>
<script>
    $(function() {
        $(".ui.accordion")
            .accordion();
    });
</script>
