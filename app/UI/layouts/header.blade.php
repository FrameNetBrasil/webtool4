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
<header class="app-header">
    <nav class="navbar is-primary" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <button class="button is-ghost navbar-burger mobile-menu-toggle" aria-label="menu" aria-expanded="false">
                <span class="icon">
                    <i class="material-symbols-outlined">menu</i>
                </span>
            </button>
            <a href="/" class="navbar-item logo">
                <img src="/images/fnbr_logo_header_alpha.png" alt="FrameNet Brasil" />
                <span class="ml-3">
                    {!! config('webtool.headerTitle') !!} 
                    <span class="header-version has-text-grey-light">{!! config('webtool.version') !!}</span>
                </span>
            </a>
        </div>

        <div class="navbar-menu">
            <div class="navbar-start">
                <div class="navbar-item">
                    <div class="field has-addons">
                        <x-ui::form-search
                            action="/app/search"
                            id="header_frame_lu"
                            placeholder="Search Frame/LU"
                        ></x-ui::form-search>
                    </div>
                </div>
            </div>

            <div class="navbar-end">
                <div class="navbar-item has-dropdown" x-data="dropdown" x-ref="dropdown">
                    <a class="navbar-link" @click="toggle" :class="{ 'is-active': isOpen }">
                        {!! $currentLanguage->description !!}
                    </a>
                    <div class="navbar-dropdown is-right" x-show="isOpen" x-transition @click.away="close">
                        @foreach($languages as $language)
                            <a class="navbar-item" 
                               hx-get="{{$language[1]}}" 
                               hx-trigger="click"
                               @click="close">
                                {{$language[0]}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>