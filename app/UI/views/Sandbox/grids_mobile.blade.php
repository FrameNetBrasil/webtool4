@php(debug($display))
<div class="hxRow container" style="height:100%">
    @if(($search->frame == '') && ($search->lu == ''))
        @if($display == 'domainTableContainer')
            <div id="domainTableContainer" class="hxCol hxSpan-12">
                @include("Sandbox.treeGroup")
            </div>
        @endif
        @if($display == 'frameTableContainer')
            <div id="frameTableContainer" class="hxCol hxSpan-12">
                @include("Sandbox.treeFrame")
            </div>
        @endif
        @if($display == 'feluTableContainer')
            <div id="feluTableContainer" class="hxCol hxSpan-12">
                @include("Sandbox.treeFELU")
            </div>
        @endif
    @endif
    @if(($search->frame != ''))
        <div id="frameTableContainer" class="hxCol hxSpan-12">
            @include("Sandbox.treeFrame")
        </div>
        <div id="feluTableContainer" class="hxCol hxSpan-12">
            @include("Sandbox.treeFELU")
        </div>
    @endif
    @if(($search->lu != ''))
        <div id="feluTableContainer" class="hxCol hxSpan-12">
            @include("Sandbox.treeFELU")
        </div>
    @endif
</div>
<style>
    .container {
        position: relative;
    }

    .displayBlock {
        display: block;
    }

    .displayNone {
        display: none;
    }

    #domainTableContainer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    #frameTableContainer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    #feluTableContainer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 12;
    }

</style>

