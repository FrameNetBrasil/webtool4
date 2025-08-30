<x-layout.index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        {{$head}}
        <main class="app-main">
            <div class="page-content h-full">
                <div class="ui container h-full">
                    {{$main}}
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout.index>
