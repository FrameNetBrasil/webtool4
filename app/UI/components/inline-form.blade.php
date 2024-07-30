<div {{$attributes->class(["wt-inline-form"])->whereDoesntStartWith('hx-')}} style="overflow:initial">
    <form id="{{$id}}" name="{{$id}}" {{$attributes}} class="ui form">
        <div id="{{$id}}_fields" class="formgroup-inline" style="overflow:initial">
            {{$fields}}
            {{$buttons}}
        </div>
    </form>
</div>

