<div
    class="ui card option-card {{$color}}"
    hx-get="/report/frame/{{$frame['id']}}"
    hx-target=".report"
    hx-on::before-request="$.tab('change tab','report')"
    style="cursor: pointer;"
>
    <div class="content">
        <div class="header">
            <x-ui::icon.frame/>
            {{$frame['name']}}
        </div>
    </div>
</div>
