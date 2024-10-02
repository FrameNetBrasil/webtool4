<x-form id="formNewVideo" title="New video" :center="false"  hx-encoding='multipart/form-data' hx-post="/video/new">
    <x-slot:fields>
        <x-text-field
            label="Title"
            id="title"
            value=""
        ></x-text-field>
        <x-combobox.language
            label="Language"
            id="idLanguage"
            value=""
        ></x-combobox.language>
        <x-file-field
            label="File"
            id="file"
            value=""
        ></x-file-field>
        <progress id='progress' value='0' max='100'></progress>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
<script>
    htmx.on('#formNewVideo', 'htmx:xhr:progress', function(evt) {
        htmx.find('#progress').setAttribute('value', evt.detail.loaded/evt.detail.total * 100)
    });
</script>
