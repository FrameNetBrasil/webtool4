<?php

namespace App\Http\Controllers\Lexicon;

use App\Data\Lexicon\SearchData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Lemma;
use App\Repositories\Lexeme;
use App\Services\AppService;
use App\Services\LexiconService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class BrowseController extends Controller
{
    #[Get(path: '/lexicon')]
    public function browse()
    {
        $search = session('searchLexicon') ?? SearchData::from();
        return view("Lexicon.browse", [
            'search' => $search
        ]);
    }

    #[Post(path: '/lexicon/grid')]
    public function grid(SearchData $search)
    {
        return view("Lexicon.grids", [
            'search' => $search,
            'wordforms' => []
        ]);
    }

    #[Get(path: '/lexicon/lexeme/{idLexeme}')]
    public function lexeme(int $idLexeme)
    {
        $lexeme = Lexeme::byId($idLexeme);
        $wordforms = Criteria::table("wordform")
            ->where("idLexeme", $idLexeme)
            ->orderBy("form")
            ->all();
        return view("Lexicon.lexeme", [
            'lexeme' => $lexeme,
            'wordforms' => $wordforms
        ]);
    }

    #[Get(path: '/lexicon/lexeme/{idLexeme}/wordforms')]
    public function wordforms(int $idLexeme)
    {
        $lexeme = Lexeme::byId($idLexeme);
        $wordforms = Criteria::table("wordform")
            ->where("idLexeme", $idLexeme)
            ->orderBy("form")
            ->all();
        return view("Lexicon.wordforms", [
            'lexeme' => $lexeme,
            'wordforms' => $wordforms
        ]);
    }

    #[Get(path: '/lexicon/lemma/{idLemma}/lexemeentries')]
    public function lexemeentries(int $idLemma)
    {
        $lemma = Lemma::byId($idLemma);
        $lexemeentries = Criteria::table("view_lexicon")
            ->where("idLemma", $idLemma)
            ->orderBy("lexemeorder")
            ->all();
        return view("Lexicon.lexementries", [
            'lemma' => $lemma,
            'lexementries' => $lexemeentries
        ]);
    }

}
