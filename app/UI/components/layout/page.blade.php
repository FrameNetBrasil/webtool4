<x-layout.index>
    <div class="wt-layout">
        @include('components.layout.head')
        <header id="header" class="wt-subheader">
            <div class="mr-2"><i class="sidebar icon menuIcon cursor-pointer"></i></div>
            {{$head}}
        </header>
        <div class="wt-content ui pushable">
            <div class="menuLeft ui left vertical menu sidebar">
                @include("components.layout.menu")
            </div>
            <div class="pusher closing pusher-full">
                <div class="wt-container">
                    <main role="main" class="wt-main relative h-full overflow-auto">
                        <div class="absolute top-0 left-0 bottom-0 right-0">
                            {{$main}}
                        </div>
                    </main>
                </div>
            </div>
        </div>
        <footer class="wt-footer">
            <div class="flex justify-content-between w-full">
                <div>
                    @include("components.layout.footer")
                </div>
                <div>
                    {!! config('webtool.version') !!}
                </div>
            </div>
        </footer>
    </div>
    <script>
        $(".menuLeft")
            .sidebar({
                context: $(".wt-content"),
                dimPage: false,
                transition: "overlay",
                mobileTransition: "overlay",
                closable: false
            })
            .sidebar("attach events", ".menuIcon")
            .sidebar("hide")
        ;
    </script>
</x-layout.index>
