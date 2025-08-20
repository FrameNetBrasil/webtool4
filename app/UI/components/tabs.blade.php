<div class="tabs-component">
    <div id="{{$id}}" class="ui stackable tabs menu">
        @foreach($tabs as $idItem => $item)
            <div
                class="item"
                data-tab="{{$idItem}}"
            >
                @if(isset($item['icon']))
                    <x-dynamic-component :component="'icon::' . $item['icon']" />
                @endif
                {{$item['label']}}
            </div>
        @endforeach
    </div>
</div>
@foreach($tabs as $idItem => $item)
    <div id="{{$id}}_{{$idItem}}_tab" class="ui tab" data-tab="{{$idItem}}">
        <div class="ui segment" style="height:80px">
            <div class="ui active inverted dimmer">
                <div class="ui text loader">Loading</div>
            </div>
        </div>
    </div>
@endforeach
<script>
    $(function() {
        let {{$id}}_tabs = {!! Js::from($tabs) !!};
        $('#{{$id}} .item').tab({
            onLoad: (tabPath, parameterArray, historyEvent) => {
                let tab = "#{{$id}}_" + tabPath + "_tab";
                htmx.ajax("GET", {{$id}}_tabs[tabPath].url, tab);
            }
        });
    });
</script>
