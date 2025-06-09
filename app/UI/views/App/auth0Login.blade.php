<x-layout::page>
    <x-slot:breadcrumb>
        <x-breadcrumb :sections="[]"></x-breadcrumb>
    </x-slot:breadcrumb>
    <x-slot:main>
        <div class="page-content">
            <div class=" center-container-with-sidebar content-container wide h-full">
                <div>
                    <div class="pb-4">
                        <img src="/images/fnbr_logo_alpha.png" width="240" />
                    </div>
                    <div>
                        <a class="ui button login">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {
                $(".ui.button.login").click(function(e) {
                    e.preventDefault();
                    window.location = "/auth0Login";
                });
            });
        </script>
    </x-slot:main>
</x-layout::page>



