<div id="{{$id}}" class="ui secondary pointing menu">
    @foreach($items as $item)
        <div
            class="item cursor-pointer"
            data-tab="{{$item[0]}}"
        >{{$item[1]}}
        </div>
    @endforeach
</div>
@foreach($items as $item)
    <div id="{{$id}}_{{$item[0]}}_tab" class="ui tab" data-tab="{{$item[0]}}">
    </div>
@endforeach
<script>
    $(function() {
        $('#{{$id}} .item').tab({
            onFirstLoad: (tabPath, parameterArray, historyEvent) => {
                let tab = "#{{$id}}_" + tabPath + "_tab"
                htmx.ajax("GET", "{{$path}}/" + tabPath , tab);
            }
        });
    });
</script>