<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="m-2">
                <div class="ui info attached message">
                    <div class="header">
                        Error
                    </div>
                    <p>
                        {{$message}}
                    </p>
                </div>
                <div class="ui bottom attached info message">
                    <a href="{{$goto}}">
                        <button
                            class="ui button"
                            type="button"
                        >{{$gotoLabel}}
                        </button>
                    </a>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
