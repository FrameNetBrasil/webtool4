<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/report','Report'],['','Class']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <div class="page-content slide">
                <div
                    class="ui container"
                    x-init="$('.item').tab()"
                >
                    <div class="ui tab h-full" data-tab="browse">
                        <x-ui::browse-table
                            title="Class Report"
                            url="/report/class/search"
                            emptyMsg="Enter your search term above to find classes."
                            :data="$data"
                        >
                            <x-slot:fields>
                                <div class="field">
                                    <div class="ui left icon input w-full">
                                        <i class="search icon"></i>
                                        <input
                                            type="search"
                                            name="class"
                                            placeholder="Search Class"
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
                                    @foreach($data as $class)
                                        <tr>
                                            <td>
                                                <div
                                                    hx-get="/report/class/{{$class['id']}}"
                                                    hx-target=".report"
                                                    hx-on::before-request="$.tab('change tab','report')"
                                                >
                                                    {!! $class['text'] !!}
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
</x-layout::index>
