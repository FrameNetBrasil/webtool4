<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/report','Report'],['','Frame']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content slide">
                <div x-data class="ui container">
                    <div class="ui secondary menu">
                        <a class="item" data-tab="browse"></a>
                        <a class="item" data-tab="report"></a>
                    </div>
                    <div class="ui tab  h-full" data-tab="browse">
                        <x-ui::browse-table
                            title="Frame Report"
                            url="/report/frame/search"
                            emptyMsg="Enter your search term above to find frames."
                            :data="$data"
                        >
                            <x-slot:fields>
                                <div class="field">
                                    <div class="ui left icon input w-full">
                                        <i class="search icon"></i>
                                        <input
                                            type="search"
                                            name="frame"
                                            placeholder="Search Frame"
                                            autocomplete="off"
                                        >
                                    </div>
                                </div>
                            </x-slot:fields>

                            <x-slot:table>
                                <table
                                    x-data
                                    class="ui selectable striped compact table"
                                >
                                    <tbody>
                                    @foreach($data as $frame)
                                        <tr>
                                            <td>
                                                <div
                                                    @click.prevent=" htmx.ajax('GET', '/report/frame/{{$frame['id']}}', '.report');$('.menu .item').tab('change tab','report')"
                                                >
                                                    {!! $frame['text'] !!}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </x-slot:table>
                        </x-ui::browse-table>

                    </div>
                    <div class="ui tab report h-full" data-tab="report">
                    </div>
                </div>
            </div>

        </main>
        <x-layout::footer></x-layout::footer>
    </div>
    <script>
        $(function() {
            $('.menu .item').tab();
        });
    </script>
</x-layout::index>
