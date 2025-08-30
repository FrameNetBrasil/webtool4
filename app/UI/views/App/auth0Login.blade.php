<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['','Home']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                <div class="ui container">
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
                </div>
            </div>
        </main>
    </div>
</x-layout::index>


