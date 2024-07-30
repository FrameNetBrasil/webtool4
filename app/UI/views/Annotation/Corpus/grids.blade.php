<div class="hxRow" style="height:100%">
    @if($display == 'corpus')
        <div id="corpusTableContainer" class="hxCol hxSpan-12 hxSpan-2-md">
            @include("Annotation.Corpus.treeCorpus")
        </div>
        <div id="documentTableContainer" class="hxCol hxSpan-12 hxSpan-2-md">
            @include("Annotation.Corpus.treeDocument")
        </div>
        <div id="sentenceTableContainer" class="hxCol hxSpan-12 hxSpan-8-md">
            @include("Annotation.Corpus.treeSentence")
        </div>
    @endif
    @if($display == 'document')
        <div id="documentTableContainer" class="hxCol hxSpan-6">
            @include("Annotation.Corpus.treeDocument")
        </div>
        <div id="sentenceTableContainer" class="hxCol hxSpan-6">
            @include("Annotation.Corpus.treeSentence")
        </div>
    @endif
    @if($display == 'sentence')
        <div id="sentenceTableContainer" class="hxCol hxSpan-12">
            @include("Annotation.Corpus.treeSentence")
        </div>
    @endif
</div>
