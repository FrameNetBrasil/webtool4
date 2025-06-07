<form
    id="appSearch"
    action="/app/search"
    method="post"
>
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <x-form::search-field
        id="frame"
        value=""
        placeholder="Search Frame/LU"
        class="w-20rem"
    ></x-form::search-field>
</form>
