<x-layout::index>
    <div class="app-layout minimal">
        <x-layout::header></x-layout::header>
        <x-layout::breadcrumb
            :sections="[['/','Home'],['/parser','Parser'],['','Grammar Nodes']]"
        ></x-layout::breadcrumb>
        <main class="app-main">
            <x-ui::browse-table
                title="Grammar Nodes"
                url="/parser/grammar/node/search"
                emptyMsg="Enter your search term above to find nodes."
                :data="$data"
            >
                <x-slot:actions>
                    <a href="/parser/grammar/node/new"
                       rel="noopener noreferrer"
                       class="ui button secondary">
                        New Node
                    </a>
                </x-slot:actions>

                <x-slot:fields>
                    <div class="field">
                        <div class="ui left icon input w-full">
                            <i class="search icon"></i>
                            <input
                                type="search"
                                name="node"
                                placeholder="Search Node"
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
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Label</th>
                            <th>Type</th>
                            <th>Threshold</th>
                            <th>Lemma</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $node)
                            <tr>
                                                                <td>{{ $node->idGrammarNode }}</td>

                                <td>
                                    <a
                                        href="/parser/grammar/node/{{ $node->idGrammarNode }}"
                                        hx-boost="true"
                                    >
                                        {!! $node->label !!}
                                    </a>
                                </td>
                                <td>
                                     <span
                                         class="ui label node-type-label type-{{ $node->type }}"
                                         style="background-color: {{ config('parser.visualization.nodeColors.' . $node->type) }}; color: white;">
                                         {{ $node->type }}
                                     </span>
                                </td>
                                <td>{{ $node->threshold }}</td>
                                <td>
                                    @if($node->idLemma)
                                        <span class="ui tiny label">{{ $node->idLemma }}</span>
                                    @else
                                        <span class="ui tiny basic label">None</span>
                                    @endif
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        hx-delete="/parser/grammar/node/{{ $node->idGrammarNode }}/delete"
                                        class="ui tiny danger button">
                                        <i class="delete icon"></i>
                                        Delete
                                    </button>
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
