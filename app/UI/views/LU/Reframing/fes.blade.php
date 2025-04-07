<label>FE annotations mapping</label>
<div class="grid">
    @foreach($fes as $i => $fe)
        <div class="col-fixed" style="width:20rem">
            <x-hidden-field id="idEntityFE[{{$i}}]" :value="$fe->idEntity"></x-hidden-field>
            <x-element.fe
                name="{{$fe->name}}"
                type="{{$fe->coreType}}"
                idColor="{{$fe->idColor}}"
            ></x-element.fe>
        </div>
        <div class="col">
            <x-combobox.fe-frame
                    id="changeToFE_{{$i}}"
                    name="changeToFE[{{$i}}]"
                    label="change to"
                    value=""
                    :idFrame="$idNewFrame"
                    :hasNull="true"
            ></x-combobox.fe-frame>
        </div>
    @endforeach
</div>

