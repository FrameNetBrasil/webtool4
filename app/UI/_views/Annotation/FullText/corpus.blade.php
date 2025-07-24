@php
    use App\Services\Annotation\BrowseService;
    $data = BrowseService::browseCorpusDocumentBySearch($search, [],'CorpusAnnotation');
    $id = uniqid("corpusTree");
@endphp
<div
    class="wt-box h-full mt-2"
>
    @fragment('search')
        <ul id="{{$id}}">
        </ul>
        <script>
            $(function() {
                $("#{{$id}}").treegrid({
                    data: {{Js::from($data)}},
                    fit: true,
                    showHeader: false,
                    rownumbers: false,
                    idField: "id",
                    treeField: "text",
                    showFooter: false,
                    border: false,
                    columns: [[
                        {
                            field: "text",
                            width: "100%"
                        }
                    ]],
                    onClickRow: (row) => {
                        if (row.type === "corpus") {
                            $("#corpusTree").treegrid("toggle", row.id);
                        }
                        if (row.type === "document") {
                            htmx.ajax("GET", `/annotation/fullText/grid/${row.id}/sentences`, "#sentenceTableContainer");
                        }
                    }
                });
            });
        </script>
    @endfragment
</div>
