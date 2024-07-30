<div class="wt-datagrid flex flex-column h-full">
    <div class="datagrid-header">
        <div class="datagrid-title">
            Corpus/Document
        </div>
    </div>
    <div id="corpusAccordionWrapper">
        <div id="corpusAccordion" class="ui tree accordion">
            @foreach($corpus as $idCorpus => $corpusObj)
                <div
                    class="title"
                    style="display: block !important;"
                    hx-trigger="click once"
                    hx-get="/annotation/fe/grid/{{$idCorpus}}/documents"
                    hx-target="#documents_{{$idCorpus}}"
                    hx-swap="innerHTML"
                >
                    <x-icon.corpus></x-icon.corpus>
                    {{$corpusObj->name}}
                </div>
                <div class="content">
                    <div
                        id="documents_{{$idCorpus}}"
                        class="accordion"
                    >
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script>
    $(function() {
        $("#corpusAccordion")
            .accordion();
    });
</script>
