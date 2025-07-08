<x-layout::index>
    <div class="app-layout no-tools">
        @include('layouts.header')

        @include("layouts.sidebar")

        <main class="app-main">
        {{$main}}
        </main>

{{--        <aside class="app-tools">--}}
{{--            <div class="tools-section">--}}
{{--                <h3 class="section-title">Quick Actions</h3>--}}
{{--                <!-- Tool content -->--}}
{{--            </div>--}}
{{--        </aside>--}}
    </div>
    <script>
        // Mobile sidebar functionality
        // Mobile sidebar functionality with dynamic overlay creation
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.querySelector('.mobile-menu-toggle');
            const sidebar = document.querySelector('.app-sidebar');
            let overlay = document.querySelector('.sidebar-overlay');

            // Create overlay if it doesn't exist
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                document.body.appendChild(overlay);
            }

            if (mobileToggle && sidebar) {
                mobileToggle.addEventListener('click', function() {
                    const isOpen = sidebar.classList.contains('open');

                    if (isOpen) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent body scroll
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
                document.body.style.overflow = ''; // Restore body scroll
            }

            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });

            // Close sidebar when window is resized to desktop size
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768 && sidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });
        });
    </script>

    {{--    <div class="wt-layout">--}}
    {{--        @include('components.layout.head')--}}
    {{--        <header id="header" class="wt-subheader">--}}
    {{--            <div class="mr-2"><i class="sidebar icon menuIcon cursor-pointer"></i></div>--}}
    {{--            {{$head}}--}}
    {{--        </header>--}}
    {{--        <div class="wt-content ui pushable">--}}
    {{--            <div class="menuLeft ui left vertical menu sidebar">--}}
    {{--                @include("components.layout.menu")--}}
    {{--            </div>--}}
    {{--            <div class="pusher closing pusher-full">--}}
    {{--                <div class="wt-container">--}}
    {{--                    <main role="main" class="wt-main relative h-full overflow-auto">--}}
    {{--                        <div class="absolute top-0 left-0 bottom-0 right-0">--}}
    {{--                            {{$main}}--}}
    {{--                        </div>--}}
    {{--                    </main>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <footer class="wt-footer">--}}
    {{--            <div class="flex justify-content-between w-full">--}}
    {{--                <div>--}}
    {{--                    @include("components.layout.footer")--}}
    {{--                </div>--}}
    {{--                <div>--}}
    {{--                    {!! config('webtool.version') !!}--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </footer>--}}
    {{--    </div>--}}
    {{--    <script>--}}
    {{--        $(".menuLeft")--}}
    {{--            .sidebar({--}}
    {{--                context: $(".wt-content"),--}}
    {{--                dimPage: false,--}}
    {{--                transition: "overlay",--}}
    {{--                mobileTransition: "overlay",--}}
    {{--                closable: false--}}
    {{--            })--}}
    {{--            .sidebar("attach events", ".menuIcon")--}}
    {{--            .sidebar("hide")--}}
    {{--        ;--}}
    {{--    </script>--}}
</x-layout::index>
