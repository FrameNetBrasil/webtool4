<x-layout.index>
    @include('components.layout.head')
    <div id="content">
        <div class="contentContainer ui pushable">
            <div class="menuLeft ui left vertical menu sidebar">
                @include("components.layout.menu")
            </div>
            <div class="pusher closing pusher-menu">
                <main id="main" role="main" class="main">
                    {{$slot}}
                    <wt-go-top id="btnTop" label="Top" offset="64"></wt-go-top>
                </main>
            </div>
        </div>
    </div>
    <footer>
        @include("components.layout.footer")
    </footer>
    <script>
        $(".menuLeft")
            .sidebar({
                context: $(".contentContainer"),
                dimPage: false,
                transition: "push",
                mobileTransition: "overlay",
                closable: false,
                onVisible: () => {
                    console.log("on visible")
                    // $(".pusher").css("marginLeft","260px");
                },
                onHidden: () => {
                    console.log("on hidden")
                    // $(".pusher").css("marginLeft","0px");
                }
            })
            .sidebar("attach events", ".menuIcon")
        ;
        if (window.innerWidth > 1200) {
            $(".menuLeft")
                .sidebar('show');
        }
    </script>
</x-layout.index>
