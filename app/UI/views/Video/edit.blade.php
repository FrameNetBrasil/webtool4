<x-layout.object :center="false">
    <x-slot:name>
        <span class="color_video">{{$video->title}}</span>
    </x-slot:name>
    <x-slot:detail>
        <div class="ui label tag wt-tag-id">
            #{{$video->idVideo}}
        </div>
        <x-button
            label="Delete"
            color="danger"
            onclick="manager.confirmDelete(`Removing Video '{{$video->title}}'.`, '/video/{{$video->idVideo}}')"
        ></x-button>
    </x-slot:detail>
    <x-slot:description>
    </x-slot:description>
    <x-slot:nav>
        <div class="ui vertical menu w-auto">
            <a
                class="item"
                hx-get="/video/{{$video->idVideo}}/formEdit"
                hx-target="#objectMainArea"
            >
                Edit
            </a>
            <a
                class="item"
                hx-get="/video/{{$video->idVideo}}/document"
                hx-target="#objectMainArea"
            >
                Documents
            </a>
        </div>
    </x-slot:nav>
    <x-slot:main>
        <div id="objectMainArea" class="objectMainArea">
        </div>
    </x-slot:main>
</x-layout.object>
