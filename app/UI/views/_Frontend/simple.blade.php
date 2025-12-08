{{--Layout for simplest page--}}
{{--Goal: Show simple content within scrollable area --}}
<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['','Home']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container page-simple">
                <div class="page-content">
                    <div class="content-container">
                        <div class="content-section">
                            Page content for simplest page
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>

