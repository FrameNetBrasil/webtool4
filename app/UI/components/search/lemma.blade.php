@props([
    'id' => 'idLemma',
    'label' => null,
    'placeholder' => 'Search Lemma',
    'searchField' => 'name',
    'value' => 0,
    'displayValue' => '',
    'modalTitle' => 'Search Lemma'
])

@if(isset($label))
<label for="{{$id}}">{{$label}}</label>
@endif
<x-search::base
    name="{{$id}}"
    placeholder="{{$placeholder}}"
    search-url="/lemma/listForSearch"
{{--    display-formatter="displayFormaterLUSearch"--}}
    display-field="fullName"
    :search-fields="[$searchField]"
    search-field="{{$searchField}}"
    value="{{$value}}"
    display-value="{{$displayValue}}"
    value-field="idLemma"
    modal-title="{{$modalTitle}}"
/>
<script>
    function displayFormaterLUSearch(lu) {
        return `<div class="result"><span class="color_frame">${lu.frameName}</span>.${lu.name}</span></div>`;
    };
</script>
