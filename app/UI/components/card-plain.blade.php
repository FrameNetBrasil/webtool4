<div {{ $attributes->merge(['class' => 'wt-card-plain']) }}>
    <div class="header">
        <div class="title">
            {!! $title !!}
        </div>
    </div>
    <div class="body">
        {{$slot}}
    </div>
</div>
