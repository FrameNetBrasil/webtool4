<x-combobox::base
    :id="$id"
    :label="$label"
    :value="$value"
    :defaultText="$defaultText"
>
    <x-slot:menu>
        @foreach($options as $entry => $coreType)
            <div
                data-value="{{$entry}}"
                class="item"
            >{{$coreType}}
            </div>
        @endforeach
    </x-slot:menu>
</x-combobox::base>
