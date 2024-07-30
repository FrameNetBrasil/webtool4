<?php

namespace App\Http\Controllers\Lexicon;

use App\Data\Lexicon\SearchData;
use App\Http\Controllers\Controller;
use App\Repositories\Lemma;
use App\Repositories\Lexeme;
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
        return view("Lexicon.tree", [
            'search' => $search
        ]);
    }

    #[Post(path: '/lexicon/grid')]
    public function grid(SearchData $search)
    {
        debug($search);
        $display = 'lemma';
        $lemmas = [];
        $lexemes = [];
        $wordforms = [];
        $lemmaName = 'Lemmas';
        $lexemeName = '';
        if ($search->lemma != '') {
            $search->idLemma = $search->idLexeme = null;
            $lemmas = LexiconService::listLemma($search);
            if (!empty($lemmas)) {
                $key = array_key_first($lemmas);
                $search->idLemma = $lemmas[$key]->idLemma;
                $lemmaName = $lemmas[$key]->name;
                $lexemes = LexiconService::listLexeme($search);
                if (!empty($lexemes)) {
                    $key = array_key_first($lexemes);
                    $search->idLexeme = $lexemes[$key]->idLexeme;
                    $lexemeName = $lexemes[$key]->name;
                    $wordforms = LexiconService::listWordform($search);
                }
            }
        } else {
            if ($search->lexeme != '') {
                $search->idLemma = $search->idLexeme = null;
                $lemmas = LexiconService::listLemma($search);
                $lemmaName = $search->lexeme . '*';
                $lexemes = LexiconService::listLexeme($search);
                if (!empty($lexemes)) {
                    $key = array_key_first($lexemes);
                    $search->idLexeme = $lexemes[$key]->idLexeme;
                    $lexemeName = $lexemes[$key]->name;
                    $wordforms = LexiconService::listWordform($search);
                }
            } else {
                if (($search->idLemma != '') && ($search->idLemma != 0)) {
                    $display = 'lxwf';
                    $search->idLexeme = null;
                    $lemma = Lemma::getById($search->idLemma);
                    $lemmaName = $lemma->name;
                    $lexemes = LexiconService::listLexeme($search);
                    if (!empty($lexemes)) {
                        $key = array_key_first($lexemes);
                        $search->idLexeme = $lexemes[$key]->idLexeme;
                        $lexemeName = $lexemes[$key]->name;
                        $wordforms = LexiconService::listWordform($search);
                    }
                } else {
                    if (($search->idLexeme != '') && ($search->idLexeme != 0)){
                        $search->idLemma = null;
                        $lemmas = LexiconService::listLemma($search);
                        $lemmaName = '*';
                        $lexemes = LexiconService::listLexeme($search);
                        if (!empty($lexemes)) {
                            $key = array_key_first($lexemes);
                            $search->idLexeme = $lexemes[$key]->idLexeme;
                            $lexemeName = $lexemes[$key]->name;
                            $wordforms = LexiconService::listWordform($search);

                        }
                    }
                }
            }

        }

//        elseif ($search->idLexeme != '') {
////            $lexeme = Lexeme::getById($search->idLexeme);
////            $lemma = LexiconService::listLemma(new SearchData);
////            $search->idLexeme = null;
////            //$search->idLemma = $document->idCorpus;
////            $lexemes = LexiconService::listLexeme($search);
////            $search->idDocument = $document->idDocument;
////            $corpusName = Corpus::getById($document->idCorpus)->name;
////            $documentName = $document->name;
//            $wordforms = LexiconService::listWordform($search);
//        } else {
//            if (($search->lemma != '') || ($search->idLemma != '')) {
//                $wordforms = CorpusService::listSentences($search);
//                $display = 'sentence';
//            } else {
//                if (($search->document != '')) {
//                    $documents = CorpusService::listDocuments($search);
//                    if (!empty($documents)) {
//                        $key = array_key_first($documents);
//                        $search->idDocument = $documents[$key]->idDocument;
//                        $sentences = CorpusService::listSentences($search);
//                    }
//                    $corpusName = $search->document . '*';
//                    $display = 'document';
//                } else {
//                    $corpus = CorpusService::listCorpus($search);
//                    if (!empty($corpus)) {
//                        $key = array_key_first($corpus);
//                        $search->idCorpus = $corpus[$key]->idCorpus;
//                        $documents = CorpusService::listDocuments($search);
//                        if (!empty($documents)) {
//                            $key = array_key_first($documents);
//                            $search->idDocument = $documents[$key]->idDocument;
//                            $corpusName = Corpus::getById($documents[$search->idDocument]->idCorpus)->name;
//                            $documentName = $documents[$search->idDocument]->name;
//                            $sentences = CorpusService::listSentences($search);
//                        }
//                    }
//                }
//            }
//        }
        $data = [
            'search' => $search,
            'display' => $display,
            'lemmas' => $lemmas,
            'lexemes' => $lexemes,
            'wordforms' => $wordforms,
            'lemmaName' => $lemmaName,
            'lexemeName' => $lexemeName,
        ];
//        debug($data);
        return view("Lexicon.grids", $data);
    }
}
