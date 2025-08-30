{{--
    LU Header - Page header for LU Report
    Shows LU name with frame reference and navigation

    Parameters:
    - $lu: LU object with name, frame info
--}}

<div class="page-header-content">
    <div class="page-header-main">
        <div class="page-title-section">
            <div class="page-title">
                <x-element::lu frame="{{$lu->frameName}}" name="{{$lu->name}}"></x-element::lu>
            </div>
            @if(!empty($lu->senseDescription))
                <div class="page-subtitle">
                    {!! Str::limit(strip_tags($lu->senseDescription), 200) !!}
                </div>
            @endif
        </div>
        <div class="page-actions">
            <a href="/report/lu">
                <button class="ui basic left labeled icon button">
                    <i class="left arrow icon"></i>
                    Back to LUs
                </button>
            </a>
        </div>
    </div>
</div>
