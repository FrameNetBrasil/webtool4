<div {{ $attributes->merge(['class' => 'wt-card']) }}
    @if(!is_null($open))
     x-data="{ open: {{$open}} }"
     @endif
>
    @if($title != '')
        <div class=" card-header">
            <div class="flex justify-content-between">
                <div>
                    {!! $title !!}
                </div>
                @if(!is_null($open))
                <div class="cursor-pointer text-right"
                         x-on:click="open = ! open"
                >
                    <a><i class="material icon">visibility</i></a>
                </div>
                @endif

            </div>
        </div>
    @endif
    <div class="card-body">
        <div
            @if(!is_null($open))
            x-show="open" x-transition
            @endif
        >
            {{$slot}}
        </div>
    </div>
</div>
