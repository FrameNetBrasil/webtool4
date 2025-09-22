<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/annotation','Annotation'],['','Session']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <x-ui::browse-table
                title="Session Report"
                url="/annotation/session/search"
                emptyMsg="Enter your search term above to find items."
                :data="$data"
            >
                <x-slot:fields>
                    <div class="fields">
                        <div class="field">
                            <div
                                class="ui floating dropdown labeled search icon button"
                                x-init="$($el).dropdown({
                                    onChange: (value, text, $selectedItem) => {
                                        console.log(value, text, $selectedItem);
                                        htmx.trigger($($el).closest('form')[0], 'submit');
                                    }
                                })"
                            >
                                <input type="hidden" name="idUser">
                                <i class="user icon"></i>
                                <span class="text">Select User</span>
                                <div class="menu">
                                    <div class="item" data-value="">all annotator</div>
                                    @foreach($annotators as $annotator)
                                        <div class="item" data-value="{{$annotator->idUser}}">{{$annotator->email}}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot:fields>

                <x-slot:table>
                    <table
                        class="ui selectable striped compact table"
                    >
                        <thead>
                        <tr
                        >
                            <th>User
                                <i @click="handleSort('name')"
                                   :class="sort === 'name' ? (order === 'asc' ? 'sort up icon' : 'sort down icon') : 'sort icon'"
                                   class="cursor-pointer"></i>
                            </th>
                            <th>#Sentence
                                <i @click="handleSort('frameName')"
                                   :class="sort === 'frameName' ? (order === 'asc' ? 'sort up icon' : 'sort down icon') : 'sort icon'"
                                   class="cursor-pointer"></i>
                            </th>
                            <th>Sentence</th>
                            <th>Total
                                <i @click="handleSort('createdAt')"
                                   :class="sort === 'createdAt' ? (order === 'asc' ? 'sort up icon' : 'sort down icon') : 'sort icon'"
                                   class="cursor-pointer"></i>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $row)
                            <tr
                            >
                                <td>
                                    {!! $row->email !!}
                                </td>
                                <td>
                                    {!! $row->idDocumentSentence !!}
                                </td>
                                <td>
                                  {!! substr($row->text,0, 120) !!}
                                </td>
                                <td>
                                    {!! $row->time !!}
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
