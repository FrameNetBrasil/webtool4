    <div class="ui info message m-2">
        <div class="header">
            Info
        </div>
        <p>
            {{$message}}
        </p>
        <x-button
            href="{{$goto}}"
            color="primary"
            label="{{$gotoLabel}}"
        >
        </x-button>
    </div>
