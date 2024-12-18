<x-layout.page>
    <x-slot:head>
        <x-breadcrumb :sections="[['','Home']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <section id="work" class="w-full h-full">
            <div class="wt-container-center h-full">
                <div class="auth0-login">
                    <img src="/images/fnbr_logo_alpha.png" width="240" />
                    <a class="btnLogin">Sign In</a>
                </div>
            </div>
        </section>
        <script>
            $(document).ready(function() {
                $(".btnLogin").click(function(e) {
                    e.preventDefault();
                    window.location = "/auth0Login";
                });
                $(".btnLogout").click(function(e) {
                    e.preventDefault();
                    window.location = "/auth0Logout";
                });
            });
        </script>
    </x-slot:main>
</x-layout.page>



