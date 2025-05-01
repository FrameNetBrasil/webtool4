<div class="wt-breadcrumb">
    @foreach($sections as $section)
        @if ($loop->last)
            <div class="active">{{$section[1]}}</div>
        @else
            <a href="{{$section[0]}}" class="section">{{$section[1]}}</a>
            <span class="separator">/</span>
        @endif
    @endforeach
</div>
