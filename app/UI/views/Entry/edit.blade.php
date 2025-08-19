<div class="ui card form-card w-full p-1">
    <div class="content">
        <div class="header">
            <x-icon::translate></x-icon::translate>
            Translations
        </div>
        <div class="description">

        </div>
    </div>
    <div class="content">
        <div class="ui form">
            <div id="frmEntries" class="ui secondary menu">
                @foreach($languages as $language)
                    @php
                        $idLanguage = $language->idLanguage;
                        $description = mb_ereg_replace("\r\n","\\n",$entries[$idLanguage]->description);
                    @endphp
                    <a class="item" data-tab="tab{{$idLanguage}}">{{$language->description}}</a>
                @endforeach
            </div>
            @foreach($languages as $language)
                @php
                    $idLanguage = $language->idLanguage;
                    $description = mb_ereg_replace("\r\n","\\n",$entries[$idLanguage]->description);
                @endphp
                <div class="ui tab segment p-2" data-tab="tab{{$idLanguage}}">
                    <input type="hidden" id="idEntry[{{$idLanguage}}]" name="idEntry[{{$idLanguage}}]" value="">
                    <div class="field">
                        <label for="name[{{$idLanguage}}]">Name</label>
                        <div class="ui small input">
                            <input
                                type="text"
                                id="name[{{$idLanguage}}]"
                                name="name[{{$idLanguage}}]"
                                value=""
                                placeholder=""

                            >
                        </div>
                    </div>
                    <div class="field">
                        <div class="form-field field">
                            <label for="description[{{$idLanguage}}]">Definition</label>
                            <textarea
                                id="description[{{$idLanguage}}]"
                                name="description[{{$idLanguage}}]"
                                placeholder=""
                                rows="6"
                            ></textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="extra content">
            <button
                type="submit"
                class="ui primary button"
                hx-put="/entry"
            >
                Save
            </button>
    </div>
</div>
<script>
    $(function() {
        $("#frmEntries .item")
            .tab();
    });
</script>
