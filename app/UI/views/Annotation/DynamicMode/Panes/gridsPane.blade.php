<div class="grids" >
    <div class="ui pointing secondary menu tabs">
        <a class="item" data-tab="objects">Objects</a>
        <a class="item" data-tab="sentences">Sentences</a>
    </div>
    <div class="ui tab segment active" data-tab="objects"  style="height:200px">
        <div x-data="{objects: $store.doStore.ox.length}">
            <div x-text="objects"></div>
        <template x-for="object in objects">
            <div x-text="object.idDynamicObject"></div>
        </template>
        </div>
    </div>
    <div class="ui tab segment" data-tab="sentences">
        Sentences
    </div>
</div>
<script type="text/javascript">
    $('.tabs .item')
        .tab()
    ;
</script>
