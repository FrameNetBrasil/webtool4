<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/structure','Structure'],['','Class']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <x-ui::browse-table
                title="Class Structure"
                url="/class/search"
                emptyMsg="Enter your search term above to find classes."
                :data="$data"
            >
{{--                <x-slot:actions>--}}
{{--                    <a href="/class/new"--}}
{{--                       rel="noopener noreferrer"--}}
{{--                       class="ui button secondary">--}}
{{--                        New Frame--}}
{{--                    </a>--}}
{{--                </x-slot:actions>--}}

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
                                    <a
                                        href="/class/{{$class['id']}}"
                                        hx-boost="true"
                                    >
                                        {!! $class['text'] !!}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </x-slot:table>
            </x-ui::browse-table>
        </main>
        <x-layout::footer></x-layout::footer>
    </div>
</x-layout::index>
