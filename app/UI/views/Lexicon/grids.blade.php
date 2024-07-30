@if($display == 'lemma')
    <div class="hxRow" style="height:100%">
        <div id="lemmaTableContainer" class="hxCol hxSpan-12 hxSpan-4-md">
            @include("Lexicon.treeLemma")
        </div>
        <div id="lxwfTableContainer" class="hxCol hxSpan-12 hxSpan-8-md">
            <div class="hxRow" style="height:100%">
                <div id="lexemeTableContainer" class="hxCol hxSpan-12 hxSpan-6-md">
                    @include("Lexicon.treeLexeme")
                </div>
                <div id="wordformTableContainer" class="hxCol hxSpan-12 hxSpan-6-md">
                    @include("Lexicon.treeWordform")
                </div>
            </div>
        </div>
    </div>
@endif
@if($display == 'lxwf')
    <div class="hxRow" style="height:100%">
        <div id="lexemeTableContainer" class="hxCol hxSpan-12 hxSpan-6-md">
            @include("Lexicon.treeLexeme")
        </div>
        <div id="wordformTableContainer" class="hxCol hxSpan-12 hxSpan-6-md">
            @include("Lexicon.treeWordform")
        </div>
    </div>
@endif

@if($display == 'wordform')
    @include("Lexicon.treeWordform")
@endif
