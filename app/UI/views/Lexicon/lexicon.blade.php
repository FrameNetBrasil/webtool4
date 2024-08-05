@php
    use App\Database\Criteria;
    $lemmaData = [];
    $lexemeData = [];
    $limit = 300;
    $idLanguage = \App\Services\AppService::getCurrentIdLanguage();
    if ($search->lexeme == '') {
        if ($search->lemma == '') {
            $search->lemma = '--none';
        }
        $lemmaData = Criteria::byFilter("view_lemma", [
            ["name", "startswith", $search->lemma],
            ['idLanguage', "=", $idLanguage]
        ])->select("idLemma", "name")
            ->distinct()
            ->limit($limit)
            ->orderBy("name")->get()->keyBy("idLemma")->all();
    } else {
        $lexemes = Criteria::byFilter("view_lexicon", [
            ["lexeme", "startswith", $search->lexeme],
            ['idLanguageLX', "=", $idLanguage]
        ])->select('idLexeme', 'lexeme', 'lemma', "posLX")
            ->distinct()
            ->limit($limit)
            ->orderBy("lemma")->orderBy("lexeme")->all();
        $lexemeData = array_map(fn($item) => (object)[
            'idLexeme' => $item->idLexeme,
            'name' => ($item->lemma ?? '--') . ' / ' . $item->lexeme,
        ], $lexemes);
    }
@endphp
<div
        class="wt-datagrid flex flex-column"
        style="height:100%"
        hx-trigger="reload-gridLexicon from:body"
        hx-target="#gridArea"
        hx-swap="innerHTML"
        hx-post="/lexicon/grid"
>
    <div class="datagrid-header">
        <div class="datagrid-title">
            {!! (count($lexemeData) > 0) ? 'Lemma/Lexeme' : 'Lemma' !!}
        </div>
    </div>
    <div class="table" style="position:relative;height:100%">
        <table id="lexiconTable">
            <tbody>
            @if(!empty($lemmaData))
                @foreach($lemmaData as $lemma)
                    <tr>
                        <td
                                hx-get="/lexicon/lemma/{{$lemma->idLemma}}"
                                hx-target="#lexiconEditContainer"
                                hx-swap="innerHTML"
                                class="cursor-pointer name"
                        >
                            <x-element.lemma :name="$lemma->name"></x-element.lemma>
                        </td>
                    </tr>
                @endforeach
            @endif
            @if(!empty($lexemeData))
                @foreach($lexemeData as $lexeme)
                    <tr>
                        <td
                                hx-get="/lexicon/lexeme/{{$lexeme->idLexeme}}"
                                hx-target="#lexiconEditContainer"
                                hx-swap="innerHTML"
                                class="cursor-pointer name"
                        >
                            <x-element.lexeme :name="$lexeme->name"></x-element.lexeme>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>
