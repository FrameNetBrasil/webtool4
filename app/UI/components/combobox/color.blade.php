<x-combobox::base
    :id="$id"
    :label="$label ?? ''"
    :value="$value"
    :defaultText="$defaultText ?? ''"
>
    <x-slot:menu>
        @foreach($options as $option)
            <div
                data-value="{{$option['id']}}"
                class="item {{$option['color']}}"
            >
                <div class="{{$option['color']}} cursor-pointer">
                    {{$option['text']}}
                </div>
            </div>
        @endforeach
    </x-slot:menu>
</x-combobox::base>

