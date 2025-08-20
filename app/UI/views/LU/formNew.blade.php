<div class="ui card form-card w-full p-1">
    <div class="content">
        <div class="header">
            <x-icon::translate></x-icon::translate>
            LU
        </div>
        <div class="description">

        </div>
    </div>
    <div class="content">
        <form class="ui form">
            <input type="hidden" name="idFrame" value="{{$idFrame}}">
            <div class="field">
                <x-search::lemma
                    id="idLexicon"
                    label="Lemma"
                ></x-search::lemma>
{{--                <x-combobox::lexicon-lemma--}}
{{--                    id="idLexicon"--}}
{{--                    label="Lemma [min: 3 chars]"--}}
{{--                    value="0"--}}
{{--                ></x-combobox::lexicon-lemma>--}}
            </div>
            <div class="field">
                <div class="field">
                    <label for="senseDescription">Sense description</label>
                    <div class="ui small input">
                        <input
                            type="text"
                            name="senseDescription"
                            value=""
                        >
                    </div>
                </div>
            </div>
            <div class="field">
                <x-combobox::fe-frame
                    id="incorporatedFE"
                    label="Incorporated FE"
                    :value="-1"
                    :idFrame="$idFrame"
                    nullName="No incorporated FE"
                    :hasNull="true"
                ></x-combobox::fe-frame>
            </div>
        </form>
    </div>
    <div class="extra content">
        <button
            type="submit"
            class="ui primary button"
            hx-post="/lu"
        >
            Add LU
        </button>
    </div>
</div>
