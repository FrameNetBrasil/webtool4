@php
    use Orkester\Security\MAuth;

    $actions = config('webtool.actions');
    $isLogged = MAuth::isLogged();
    if ($isLogged) {
        $user = MAuth::getLogin();
        $userLevel = session('userLevel');
    }
    $currentLanguage = session('currentLanguage');
    $languages = config('webtool.user')[3]['language'][3];
    $profile = config('webtool.user')[3]['profile'][3];
    $hrefLogin = (env('APP_AUTH') == 'auth0') ? '/auth0Login' : '/';

@endphp
<header class="app-header">
    <div class="header-left">
        <button class="mobile-menu-toggle">
            <i class="bars icon"></i>
        </button>
        <a href="/" class="logo">
            <div>
                <img src="/images/fnbr_logo_header_alpha.png" />
            </div>
            <span>{!! config('webtool.headerTitle') !!}</span>
        </a>
        {{$breadcrumb}}
    </div>
    <div class="header-center">
        <div class="search-bar">
            <x-ui::form-search
                action="/app/search"
                id="header_frame_lu"
                placeholder="Search Frame/LU"
            ></x-ui::form-search>
        </div>
    </div>
    <div class="header-right">
        <div id="menuLanguage" class="ui dropdown pointing top left pr-3">
            {!! $currentLanguage->description !!}<i class="dropdown icon"></i>
            <div class="menu">
                @foreach($languages as $language)
                    <div class="item" hx-get="{{$language[1]}}" hx-trigger="click">{{$language[0]}}</div>
                @endforeach
            </div>
        </div>
        {{--        @if($isLogged)--}}
        {{--            <div id="menuUser" class="ui right dropdown item pl-3 pointing top left ">--}}
        {{--                <div class="user-menu">--}}
        {{--                    <div class="user-avatar">{!! strtoupper($user->email[0]) !!}</div>--}}
        {{--                </div>--}}
        {{--                <div class="menu">--}}
        {{--                    <div class="item">--}}
        {{--                        {{$user->email}}--}}
        {{--                    </div>--}}
        {{--                    <div class="item">--}}
        {{--                        Level: {{$userLevel}}--}}
        {{--                    </div>--}}
        {{--                    <div class="item">--}}
        {{--                        ID: #{{$user->idUser}}--}}
        {{--                    </div>--}}
        {{--                    <div class="divider"></div>--}}
        {{--                    <div class="item" hx-get="/user/profile" hx-trigger="click">My profile</div>--}}
        {{--                    <div class="divider"></div>--}}
        {{--                    <div class="item">--}}
        {{--                        <button--}}
        {{--                            class="ui button"--}}
        {{--                            hx-get="/logout"--}}
        {{--                        >--}}
        {{--                            Logout--}}
        {{--                        </button>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        @endif--}}
    </div>
</header>
<script>
    $(function() {
        $("#menuLanguage").dropdown();
        // $("#menuUser").dropdown();
    });
</script>
