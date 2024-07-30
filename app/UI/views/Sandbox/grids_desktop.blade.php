<div class="hxRow" style="height:100%">
    @if(($search->frame == '') && ($search->lu == ''))
        <div id="domainTableContainer" class="hxCol hxSpan-4">
            @include("Sandbox.treeGroup")
        </div>
        <div id="frameTableContainer" class="hxCol hxSpan-4">
            @include("Sandbox.treeFrame")
        </div>
        <div id="feluTableContainer" class="hxCol hxSpan-4">
            @include("Sandbox.treeFELU")
        </div>
    @endif
    @if(($search->frame != ''))
        <div id="frameTableContainer" class="hxCol hxSpan-6">
            @include("Sandbox.treeFrame")
        </div>
        <div id="feluTableContainer" class="hxCol hxSpan-6">
            @include("Sandbox.treeFELU")
        </div>
    @endif
    @if(($search->lu != ''))
        <div id="feluTableContainer" class="hxCol hxSpan-12">
            @include("Sandbox.treeFELU")
        </div>
    @endif
</div>
