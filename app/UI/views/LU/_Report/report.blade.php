<div id="luReport" class="flex flex-column h-full">
    <div class="flex flex-row align-content-start">
        <div class="col-12 sm:col-12 md:col-12 lg:col-7 xl:col-6">
            <h1>
                <x-element.lu frame="{{$lu->frameName}}" name="{{$lu->name}}"></x-element.lu>
            </h1>
        </div>
        <div class="col-12 sm:col-12 md:col-12 lg:col-5 xl:col-6 flex gap-1 flex-wrap align-items-center justify-content-end">
            <div class="ui label wt-tag-en">
                {{$language->language}}
            </div>
            <div class="ui label wt-tag-id">
                #{{$lu->idLU}}
            </div>
            <x-link-button color="secondary" href="/report/frame/{{$lu->idFrame}}"
                           label="{{$lu->frameName}}"></x-link-button>
            @if($isMaster)
                <x-link-button color="ui red" href="/lu/{{$lu->idLU}}/edit" label="Edit"></x-link-button>
            @endif
        </div>
    </div>
    <x-card title="Definition" class="luReport__card">
        {!! $lu->senseDescription !!}
        @if(isset($incorporatedFE))
            <hr>
            Incorporated FE:
            <x-element.fe name="{{$incorporatedFE->name}}" type="{{$incorporatedFE->coreType}}"
                          idColor="{{$incorporatedFE->idColor}}"></x-element.fe>
        @endif
    </x-card>
    <div class="flex-grow-1 h-full flex flex-column">
        @include("LU._Report.menu")
    </div>
</div>

