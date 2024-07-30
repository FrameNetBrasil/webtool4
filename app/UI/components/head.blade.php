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
    $hrefLogin = (env('AUTH0_CLIENT_ID') == 'auth0') ? '/auth0Login' : '/';

@endphp
<header id="head">
    <nav id="topNav" class="grid grid-nogutter">
        <div class="topNavApp col-4">
            <a href="/">
                <span>{!! config('webtool.headerTitle') !!}</span>
            </a>
        </div>
        <div class="hxTopNavIconMenu col-8 flex align-items-center justify-content-end pr-2 column-gap-3">
            {{--            <div class="hxTopNavMenu ">--}}
            <div id="menuLanguage" class="ui dropdown">
                <i class="icon material">translate</i>
                {!! $currentLanguage->description !!} <i class="dropdown icon"></i>
                <div class="menu">
                    @foreach($languages as $language)
                        <div class="item" hx-get="{{$language[1]}}" hx-trigger="click">{{$language[0]}}</div>
                    @endforeach
                </div>
            </div>
            {{--                <hx-disclosure aria-controls="menuLanguage" aria-expanded="false">--}}
            {{--                    <span class="icon material-icons-outlined wt-icon-menu-translate"></span>--}}
            {{--                    <span>{!! $currentLanguage->description !!}</span>--}}
            {{--                    <hx-icon class="hxPrimary" type="angle-down"></hx-icon>--}}
            {{--                </hx-disclosure>--}}
            {{--                <hx-menu id="menuLanguage">--}}
            {{--                    <section>--}}
            {{--                        @foreach($languages as $language)--}}
            {{--                            <hx-menuitem hx-get="{{$language[1]}}" hx-trigger="click">{{$language[0]}}</hx-menuitem>--}}
            {{--                        @endforeach--}}
            {{--                    </section>--}}
            {{--                </hx-menu>--}}
            {{--            </div>--}}
            {{--            <div class="hxSpacer"></div>--}}
            {{--            <div class="hxTopNavMenu">--}}
            @if($isLogged)
                <div id="menuUser" class="ui dropdown">
                    <i class="icon material">person</i>
                    {{$user->email}}<i class="dropdown icon"></i>
                    <div class="menu">
                        <div class="item">
                            Level: {{$userLevel}}
                        </div>
                        <div class="item">
                            ID: #{{$user->idUser}}
                        </div>
                        <div class="divider"></div>
                        <div class="item" hx-get="/user/profile" hx-trigger="click">My profile</div>
                        <div class="divider"></div>
                        <div class="item">
                            <x-button label="Logout" hx-get="/logout"></x-button>
                        </div>
                    </div>
                </div>
                {{--                    <hx-disclosure aria-controls="demo-user-menu" aria-expanded="false">--}}
                {{--                        <hx-icon class="hxNavUser" type="user"></hx-icon>--}}
                {{--                        <span>{{$user->email}}</span>--}}
                {{--                        <hx-icon class="hxPrimary" type="angle-down"></hx-icon>--}}
                {{--                    </hx-disclosure>--}}
                {{--                    <hx-menu id="demo-user-menu" position="bottom-end">--}}
                {{--                        <section>--}}
                {{--                            <header>--}}
                {{--                                <hx-menuitem class="hxMenuKey">Level:</hx-menuitem>--}}
                {{--                                <hx-menuitem class="hxMenuValue">{{$userLevel}}</hx-menuitem>--}}
                {{--                            </header>--}}
                {{--                            <hr class="hxDivider">--}}
                {{--                            <hx-menuitem class="hxMenuValue">My Profile</hx-menuitem>--}}
                {{--                            <hr class="hxDivider">--}}
                {{--                            <footer>--}}
                {{--                                <button class="hxBtn" hx-get="/logout">Log Out</button>--}}
                {{--                            </footer>--}}
                {{--                        </section>--}}
                {{--                    </hx-menu>--}}
            @else
                <x-button
                    icon="menu-signin"
                    href="{{$hrefLogin}}"
                    label="Login"
{{--                    color="plain"--}}
                ></x-button>
            @endif
            {{--            </div>--}}
        </div>
    </nav>
</header>
<script>
    $(function() {
        $("#menuLanguage").dropdown();
        $("#menuUser").dropdown();
    });
</script>
