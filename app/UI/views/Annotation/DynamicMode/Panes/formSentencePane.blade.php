@php
    use App\Database\Criteria;
    $origins = Criteria::table("originmm")
        ->select("idOriginMM","origin")
        ->chunkResult("idOriginMM","origin");
@endphp
<div class="form">
    <x-form id="formSentence" title="" center="true">
        <x-slot:fields>
            <div class="field ">
                <div class="flex">
                    @if($sentence?->idSentence == 0)
                        <div class="field title">Current Sentence: #new</div>
                    @else
                        <div class="field title">Current Sentence: #{{$sentence->idSentence}}</div>
                    @endif
                </div>
            </div>
            <div class="flex flex-row flex-wrap gap-2">
                <x-hidden-field
                    id="idDocument"
                    :value="$idDocument"
                ></x-hidden-field>
                <x-hidden-field
                    id="idLanguage"
                    :value="$idLanguage"
                ></x-hidden-field>
                <x-hidden-field
                    id="idSentence"
                    :value="$sentence?->idSentence"
                ></x-hidden-field>
                <x-text-field
                    id="startTime"
                    label="Start"
                    class="mb-2"
                    :value="$sentence?->startTime ?? 0"
                ></x-text-field>
                <x-text-field
                    id="endTime"
                    label="End"
                    class="mb-2"
                    :value="$sentence?->endTime ?? 0"
                ></x-text-field>
                    <x-combobox.options
                        label="Text source"
                        id="idOriginMM"
                        :value="$sentence?->idOriginMM ?? 0"
                        :options="$origins"
                        class="w-14rem"
                    ></x-combobox.options>
            </div>
            <div class="flex flex-row flex-wrap gap-2">
                <x-multiline-field
                    id="text"
                    label="Text"
                    value="{{$sentence?->text}}"
                    class="h-4rem"
                    style="width:40rem !important"
                ></x-multiline-field>
                <div class="form-field field">
                    <label></label>
                    <x-button
                        type="button"
                        label="Save"
                        hx-post="/annotation/dynamicMode/formSentence"
                    ></x-button>
                    <x-button
                        type="button"
                        label="Reset"
                        color="secondary"
                        hx-get="/annotation/dynamicMode/formSentence/{{$idDocument}}/0"
                        hx-target="#formSentence"
                    ></x-button>
                    <x-button
                        type="button"
                        label="Split"
                        color="danger"
                        x-data @click="$store.doStore.split({{$sentence?->idSentence}})"
                    ></x-button>
                </div>
            </div>
        </x-slot:fields>
    </x-form>
</div>
