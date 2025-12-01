<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb :sections="[['/','Home'],['/parser','Parser'],['/parser/result/' . $result->idParserGraph,'Result #' . $result->idParserGraph]]"></x-layout::breadcrumb>

        <main class="app-main">
            <div class="page-content">
                <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-title">Parse Result #{{ $result->idParserGraph }}</div>
                        <div class="page-subtitle">Graph-Based Predictive Parser Result</div>
                    </div>
                </div>

                @include('Parser.parserResults')
            </div>
        </main>

        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
