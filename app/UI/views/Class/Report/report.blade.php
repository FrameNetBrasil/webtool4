<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/report','Report'],['/report/class','Class'],['',$class->name]]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content">
                @include("Class.Report.reportPartial")
            </div>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
