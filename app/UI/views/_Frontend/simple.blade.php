{{--Layout for simplest page--}}
{{--Goal: Show simple content within scrollable area --}}
<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['','Home']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="ui container overflow-y">
                <div class="page-content">
                    Page content for simplest page
                </div>
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>

