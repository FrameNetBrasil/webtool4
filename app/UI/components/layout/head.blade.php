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
<header id="head" class="flex justify-content-between">
    <div class="flex align-items-center ">
        <div class="headApp">
            <i id="headMenuIcon" class="sidebar icon menuIcon cursor-pointer"></i>
            <a href="/">
                <span>{!! config('webtool.headerTitle') !!}</span>
            </a>
        </div>
        <div id="headLargeSearch">
            <x-layout.search></x-layout.search>
        </div>
    </div>
    <div class="flex align-items-center justify-content-end pr-1 h-full">
        <div id="menuLanguage" class="ui dropdown pointing top left pr-3 border-right-1 border-white-alpha-60">
            {!! $currentLanguage->description !!}<i class="dropdown icon"></i>
            <div class="menu">
                @foreach($languages as $language)
                    <div class="item" hx-get="{{$language[1]}}" hx-trigger="click">{{$language[0]}}</div>
                @endforeach
            </div>
        </div>
        @if($isLogged)
            <div id="menuUser" class="ui dropdown pl-3">
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
        @else
            <div class="pl-2">
                <x-button
                    hx-get="{{$hrefLogin}}"
                    label="Login"
                    color="secondary"
                ></x-button>
            </div>
        @endif
    </div>
</header>
<div id="headSmallSearch" class="p-1">
    <x-layout.search></x-layout.search>
</div>
<script>
    $(function() {
        $("#menuLanguage").dropdown();
        $("#menuUser").dropdown();
    });
</script>
