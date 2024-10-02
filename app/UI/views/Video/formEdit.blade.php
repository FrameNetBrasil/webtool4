<x-form id="formEditVideo" title="Edit video" :center="false"  hx-post="/video">
    <x-slot:fields>
        <x-hidden-field
            id="idVideo"
            :value="$video->idVideo"
        ></x-hidden-field>
        <x-text-field
            label="Title"
            id="title"
            :value="$video->title"
        ></x-text-field>
        <x-text-field
            label="Original File"
            id="originalFile"
            :value="$video->originalFile"
        ></x-text-field>
        <label>SHA1 Name</label>
        <div>{{$video->sha1Name}}</div>
    </x-slot:fields>
    <x-slot:buttons>
        <x-submit label="Save"></x-submit>
    </x-slot:buttons>
</x-form>
