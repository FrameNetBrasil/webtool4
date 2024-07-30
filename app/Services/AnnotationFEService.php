<?php

namespace App\Services;

use App\Data\Annotation\FE\AnnotationData;
use App\Data\Annotation\FE\CreateASData;
use App\Data\Annotation\FE\SearchData;
use App\Data\Label\CreateData;
use App\Database\Criteria;
use App\Repositories\AnnotationSet;
use App\Repositories\Corpus;
use App\Repositories\Document;
use App\Repositories\FrameElement;
use App\Repositories\LU;
use App\Repositories\Timeline;
use App\Repositories\WordForm;
use Illuminate\Support\Facades\DB;


class AnnotationFEService
{
    public static function listSentences(int $idDocument): array
    {
        $sentences = Criteria::table("sentence")
            ->join("view_document_sentence as ds", "sentence.idSentence", "=", "ds.idSentence")
            ->join("document as d", "ds.idDocument", "=", "d.idDocument")
            ->where("d.idDocument", $idDocument)
            ->select("sentence.idSentence", "sentence.text", "ds.idDocumentSentence")
            ->orderBy("ds.idDocumentSentence")
            ->limit(1000)
            ->get()->keyBy("idDocumentSentence")->all();
        if (!empty($sentences)) {
            $targets = collect(AnnotationSet::listTargetsForDocumentSentence(array_keys($sentences)))->groupBy('idDocumentSentence')->toArray();
            foreach ($targets as $idDocumentSentence => $spans) {
                $sentences[$idDocumentSentence]->text = self::decorateSentenceTarget($sentences[$idDocumentSentence]->text, $spans);
            }
        }
        return $sentences;
    }

//    public static function listDocuments(int $idCorpus): array
//    {
//        return Criteria::byFilterLanguage("view_document",[
////            ["name","startswith", $data->document],
//            ["idCorpus","=", $idCorpus],
//        ])->orderBy("name")->all();
//    }
//
//    public static function listCorpus(SearchData $data): array
//    {
//        return Criteria::byFilterLanguage("view_corpus",["name","startswith", $data->corpus])
//            ->orderBy("name")->all();
//    }

    public static function getPrevious(int $idDocument, int $idDocumentSentence)
    {
        $i = Criteria::table("view_document_sentence")
            ->where("idDocument", "=", $idDocument)
            ->where("idDocumentSentence", "<", $idDocumentSentence)
            ->max('idDocumentSentence');
        return $i ?? null;
    }

    public static function getNext(int $idDocument, int $idDocumentSentence)
    {
        $i = Criteria::table("view_document_sentence")
            ->where("idDocument", "=", $idDocument)
            ->where("idDocumentSentence", ">", $idDocumentSentence)
            ->min('idDocumentSentence');
        return $i ?? null;
    }

    public static function getAnnotationData(int $idDocumentSentence): array
    {
        $sentence = Criteria::table("sentence as s")
            ->join("view_document_sentence as ds", "s.idSentence", "=", "ds.idSentence")
            ->where("ds.idDocumentSentence", $idDocumentSentence)
            ->select("s.idSentence", "s.text", "ds.idDocumentSentence", "ds.idDocument")
            ->first();
        $words = self::getWords($sentence);
        foreach ($words as $i => $word) {
            if (!$word['hasLU']) {
                $words[$i]['hasLU'] = WordForm::wordHasLU($word['word']);
            }
        }
//        debug($words);
        $document = Document::byId($sentence->idDocument);
        $corpus = Corpus::byId($document->idCorpus);
        return [
            'idDocumentSentence' => $idDocumentSentence,
            'idPrevious' => self::getPrevious($sentence->idDocument, $idDocumentSentence),
            'idNext' => self::getNext($sentence->idDocument, $idDocumentSentence),
            'corpus' => $corpus,
            'document' => $document,
            'sentence' => $sentence,
            'text' => $sentence->text,
            'tokens' => $words,
        ];

    }

    public static function getWords(object $sentence): array
    {
        $targets = AnnotationSet::getTargets($sentence->idDocumentSentence);
        // get words/chars
        $wordsChars = AnnotationSet::getWordsChars($sentence->text);
//        debug($wordsChars);
        $words = $wordsChars->words;
        $wordsByChar = [];
        foreach ($words as $word) {
            $wordsByChar[$word['startChar']] = $word;
        }
//        debug($wordsChars->chars);
        $wordTarget = [];
        foreach ($targets as $target) {
            $wordTarget[$target->startChar] = [
                'word' => mb_substr($sentence->text, $target->startChar, ($target->endChar - $target->startChar + 1)),
                'startChar' => $target->startChar,
                'endChar' => $target->endChar,
                'hasLU' => true,
                'idAS' => $target->idAnnotationSet
            ];
        }
        $wordList = [];
        $nextChar = 0;
        while ($nextChar < count($wordsChars->chars)) {
            if (isset($wordTarget[$nextChar])) {
                $wordList[] = $wordTarget[$nextChar];
                $nextChar = $wordTarget[$nextChar]['endChar'] + 1;
            } else {
                $wordList[] = [
                    'word' => $wordsByChar[$nextChar]['word'],
                    'startChar' => $wordsByChar[$nextChar]['startChar'],
                    'endChar' => $wordsByChar[$nextChar]['endChar'],
                    'hasLU' => false
                ];
                $nextChar = $wordsByChar[$nextChar]['endChar'] + 1;
            }
        }
        return $wordList;
    }

    public static function getLUs(int $idDocumentSentence, int $idWord): array
    {
        $sentence = Criteria::table("sentence as s")
            ->join("view_document_sentence as ds", "s.idSentence", "=", "ds.idSentence")
            ->where("ds.idDocumentSentence", $idDocumentSentence)
            ->select("s.idSentence", "s.text", "ds.idDocumentSentence", "ds.idDocument")
            ->first();
        $words = self::getWords($sentence);
        $wordsToShow = [];
        for ($i = $idWord - 10; $i <= $idWord + 10; $i++) {
            if (isset($words[$i])) {
                if ($words[$i]['word'] != ' ') {
                    $wordsToShow[$i] = $words[$i];
                }
            }
        }
        return [
            'lus' => WordForm::getLUs($words[$idWord]['word']),
            'words' => $wordsToShow,
        ];

    }

    public static function getASData(int $idAS): array
    {
        $it = Criteria::table("view_instantiationtype")
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->all();
        $as = Criteria::table("view_annotationset")
            ->where('idAnnotationSet', $idAS)
            ->first();
        $sentence = Criteria::table("sentence as s")
            ->join("view_document_sentence as ds", "s.idSentence", "=", "ds.idSentence")
            ->where("ds.idDocumentSentence", $as->idDocumentSentence)
            ->select("s.idSentence", "s.text", "ds.idDocumentSentence", "ds.idDocument")
            ->first();
        $wordsChars = AnnotationSet::getWordsChars($sentence->text);
        foreach ($wordsChars->words as $i => $word) {
            $wordsChars->words[$i]['hasFE'] = false;
        }
        $lu = LU::byId($as->idLU);
        $fes = Criteria::table("view_frameelement")
            ->where('idLanguage', AppService::getCurrentIdLanguage())
            ->where("idFrame", $lu->idFrame)
            ->keyBy("idEntity")
            ->all();
//        foreach ($fes as $fe) {
//            $fe->coreTypeIcon = substr($fe->coreType, 4);
//        }
        $layers = AnnotationSet::getLayers($idAS);
        $target = array_filter($layers, fn($x) => ($x->layerTypeEntry == 'lty_target'));
        foreach ($target as $tg) {
            $tg->startWord = $wordsChars->chars[$tg->startChar]['order'];
            $tg->endWord = $wordsChars->chars[$tg->endChar]['order'];
        }
        $feSpans = array_filter($layers, fn($x) => $x->layerTypeEntry == 'lty_fe');
        $spans = [];
        $nis = [];
        $firstWord = array_key_first($wordsChars->words);
        $lastWord = array_key_last($wordsChars->words);
        $spansByLayer = collect($feSpans)->groupBy('idLayer')->all();
        foreach ($spansByLayer as $idLayer => $existingSpans) {
            for ($i = $firstWord; $i <= $lastWord; $i++) {
                $spans[$i][$idLayer] = null;
            }
            foreach ($existingSpans as $span) {
                if ($span->idTextSpan != '') {
                    $span->startWord = ($span->startChar != -1) ? $wordsChars->chars[$span->startChar]['order'] : -1;
                    $span->endWord = ($span->endChar != -1) ? $wordsChars->chars[$span->endChar]['order'] : -1;
                    if ($span->layerTypeEntry == 'lty_fe') {
                        if ($span->startWord != -1) {
                            $hasLabel = false;
                            for ($i = $span->startWord; $i <= $span->endWord; $i++) {
                                $name = (!$hasLabel) ? $fes[$span->idEntity]->name : null;
                                $spans[$i][$idLayer] = [
                                    'idEntityFE' => $span->idEntity,
                                    'label' => $name
                                ];
                                $wordsChars->words[$i]['hasFE'] = true;
                                $hasLabel = true;
                            }
                        } else {
                            $name = $fes[$span->idEntity]->name;
                            $nis[$span->idInstantiationType][] = [
                                'idEntityFE' => $span->idEntity,
                                'label' => $name
                            ];
                        }
                    }
                }
            }
        }
        //debug($baseLabels, $labels);
        ksort($spans);
//        debug($labels);
//        debug($it);
//        debug($nis);
        return [
            'it' => $it,
            'words' => $wordsChars->words,
            'idAnnotationSet' => $idAS,
            'lu' => $lu,
            'target' => $target[0],
            'spans' => $spans,
            'fes' => $fes,
            'nis' => $nis
        ];

    }

    /**
     * @throws \Exception
     */
    public static function annotateFE(AnnotationData $data): array
    {
        DB::transaction(function () use ($data) {
            $userTask = Criteria::table("usertask as ut")
                ->join("task as t", "ut.idTask", "=", "t.idTask")
                ->where("ut.idUser", -2)
                ->where("t.name", 'Default Task')
                ->first();
            $fe = FrameElement::byId($data->idFrameElement);
            $layers = AnnotationSet::getLayers($data->idAnnotationSet);
            $spans = array_filter($layers, fn($x) => $x->layerTypeEntry == 'lty_fe');
            $idLayer = $spans[array_key_first($spans)]->idLayer;
            debug($spans);
            //$spansByLayer = collect($spans)->groupBy('idLayer')->toArray();
            // debug($labelsByLayer);
            if ($data->range->type == 'word') {
                $overlap = false;
                //verify overlap with existing labels
                foreach ($spans as $span) {
                    $idLayer = $span->idLayer;
                    if (!(($data->range->end < $span->startChar) || ($data->range->start > $span->endChar))) {
                        $overlap |= true;
                    }
                }
                if ($overlap) {
                    $idLayer = AnnotationSet::addFELayer($data->idAnnotationSet);
                }
                $it = Criteria::table("view_instantiationtype")
                    ->where('entry', 'int_normal')
                    ->first();
                debug($it);
                $data = json_encode([
                    'startChar' => (int)$data->range->start,
                    'endChar' => (int)$data->range->end,
                    'multi' => 0,
                    'idLayer' => $idLayer,
                    'idInstantiationType' => $it->idInstantiationType,
                ]);
                $idTextSpan = Criteria::function("textspan_char_create(?)", [$data]);
                $ts = Criteria::table("textspan")
                    ->where("idTextSpan", $idTextSpan)
                    ->first();
                $data = json_encode([
                    'idAnnotationObject' => $ts->idAnnotationObject,
                    'idEntity' => $fe->idEntity,
                    'relationType' => 'rel_annotation',
                    'idUserTask' => $userTask->idUserTask
                ]);
                $idAnnotation = Criteria::function("annotation_create(?)", [$data]);
            } else if ($data->range->type == 'ni') {
                $data = json_encode([
                    'startChar' => -1,
                    'endChar' => -1,
                    'multi' => 0,
                    'idLayer' => $idLayer,
                    'idInstantiationType' => (int)$data->range->id,
                ]);
                $idTextSpan = Criteria::function("textspan_char_create(?)", [$data]);
                $ts = Criteria::table("textspan")
                    ->where("idTextSpan", $idTextSpan)
                    ->first();
                $data = json_encode([
                    'idAnnotationObject' => $ts->idAnnotationObject,
                    'idEntity' => $fe->idEntity,
                    'relationType' => 'rel_annotation',
                    'idUserTask' => $userTask->idUserTask
                ]);
                $idAnnotation = Criteria::function("annotation_create(?)", [$data]);
            }
            Timeline::addTimeline("annotation", $idAnnotation, "C");
        });
        return self::getASData($data->idAnnotationSet);
    }

    public static function createAnnotationSet(CreateASData $data): ?int
    {
        $startChar = 4000;
        $endChar = -1;
        foreach ($data->wordList as $word) {
            if ($word->startChar < $startChar) {
                $startChar = $word->startChar;
            }
            if ($word->endChar > $endChar) {
                $endChar = $word->endChar;
            }
        }
        $idAnnotationSet = null;
        if (($startChar != -1) && ($endChar != 4000)) {
            $idAnnotationSet = AnnotationSet::createForLU($data->idSentence, $data->idLU, $startChar, $endChar);
        }
        return $idAnnotationSet;
    }

    public static function decorateSentenceTarget($text, $spans)
    {
        $decorated = "";
        $ni = "";
        $i = 0;
        foreach ($spans as $span) {
            //$style = 'background-color:#' . $label['rgbBg'] . ';color:#' . $label['rgbFg'] . ';';
            if ($span->startChar >= 0) {
                $decorated .= mb_substr($text, $i, $span->startChar - $i);
                $decorated .= "<span class='color_target'>" . mb_substr($text, $span->startChar, $span->endChar - $span->startChar + 1) . "</span>";
                $i = $span->endChar + 1;
            }
        }
        $decorated = $decorated . mb_substr($text, $i);
        return $decorated;
    }

}
