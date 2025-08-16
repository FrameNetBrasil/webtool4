@php
    use Illuminate\Support\Facades\Auth;

    $actions = config('webtool.actions');
    $isLogged = Auth::check();
    if ($isLogged) {
        $user = Auth::user();
        $userLevel = session('userLevel');
    }
    $currentLanguage = session('currentLanguage');
    $languages = config('webtool.user')[3]['language'][3];
    $profile = config('webtool.user')[3]['profile'][3];
    $hrefLogin = (env('APP_AUTH') == 'auth0') ? '/auth0Login' : '/';

@endphp
<header id="head" class="flex justify-content-between">
    <div class="flex align-items-center ">
        <div style="width:40px;text-align:center">
            <img src="/images/fnbr_logo_header_alpha_white.png" height="24"/>
        </div>
        <div class="border-right-1 border-white-alpha-60" style="height:20px"></div>
        <div class="headApp">
            <a href="/">
                <span>{!! config('webtool.headerTitle') !!}</span>
            </a>
        </div>
        <div id="headLargeSearch">
            <x-layout.search></x-layout.search>
        </div>
    </div>
    <div class="flex align-items-center justify-content-end pr-1 h-full">
        <div id="menuLanguage" class="ui dropdown pointing top left pr-3">
            {!! $currentLanguage->description !!}<i class="dropdown icon"></i>
            <div class="menu">
                @foreach($languages as $language)
                    <div class="item" hx-get="{{$language[1]}}" hx-trigger="click">{{$language[0]}}</div>
                @endforeach
            </div>
        </div>
        @if($isLogged)
            <div id="menuUser" class="ui right dropdown item pl-3 pointing top left ">
                <div class="ui teal circular label">{!! strtoupper($user->email[0]) !!}</div>
                <div class="menu">
                    <div class="item">
                        {{$user->email}}
                    </div>
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
                <x-link-button
                    href="{{$hrefLogin}}"
                    label="Login"
                    color="secondary"
                ></x-link-button>
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
