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
<div id="leftNav">
    <form
        id="appSearch"
        action="/app/search"
        method="POST"
        class="pt-2"
    >
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <x-search-field
            id="frame"
            value=""
            placeholder="Search Frame/LU"
            class="w-13rem"
        ></x-search-field>
    </form>
    <nav id="nav" class="leftMenu">
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
                <span class="navTitle">{!! $menuData->label !!}</span>

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
                    <a class="navItem" href="{{$itemData->href}}">{{$itemData->label}}</a>
                @endforeach

            @endif
        @endforeach
    </nav>
</div>
