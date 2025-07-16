@if(isset($label))
<label for="{{$id}}">{{$label}}</label>
@endif
<x-ui::search
    name="{{$id}}"
    placeholder="{{$placeholder}}"
    search-url="/lu/list/forSelect"
    display-formatter="displayFormaterLUSearch"
    value-field="idLU"
    modal-title="{{$modalTitle}}"
/>
<script>
    function displayFormaterLUSearch(lu) {
        return `<div class="result"><span class="color_frame">${lu.frameName}</span>.${lu.name}</span></div>`;
    };
</script>
