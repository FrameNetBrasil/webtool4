<?php

namespace App\Services;

use App\Database\Criteria;
use App\Repositories\Document;
use App\Repositories\Project;
use App\Repositories\User;

class AnnotationService
{
    public static function browseCorpusDocumentBySearch(object $search, array $projects = [])
    {
        $corpusIcon = view('components.icon.corpus')->render();
        $documentIcon = view('components.icon.document')->render();
        $data = [];

        $allowed = Project::getAllowedDocsForUser($projects, $projectGroup);
        $allowedCorpus = collect($allowed)->pluck('idCorpus')->all();
        $allowedDocuments = collect($allowed)->pluck('idDocument')->all();
        if ($search->document == '') {
            $corpus = Criteria::byFilterLanguage("view_corpus", ["name", "startswith", $search->corpus])
                ->whereIn("idCorpus", $allowedCorpus)
                ->orderBy("name")->get()->keyBy("idCorpus")->all();
            $ids = array_keys($corpus);
            $documents = Criteria::byFilterLanguage("view_document", ["idCorpus", "IN", $ids])
                ->whereIn("idDocument", $allowedDocuments)
                ->orderBy("name")
                ->get()->groupBy("idCorpus")
                ->toArray();
            foreach ($corpus as $c) {
                $children = array_map(fn($item) => [
                    'id' => $item->idDocument,
                    'text' => $documentIcon . $item->name,
                    'state' => 'closed',
                    'type' => 'document'
                ], $documents[$c->idCorpus] ?? []);
                $data[] = [
                    'id' => $c->idCorpus,
                    'text' => $corpusIcon . $c->name,
                    'state' => 'closed',
                    'type' => 'corpus',
                    'children' => $children
                ];
            }
        } else {
            $documents = Criteria::byFilterLanguage("view_document", ["name", "startswith", $search->document])
                ->select('idDocument', 'name', 'corpusName')
                ->whereIn("idDocuments", $allowedDocuments)
                ->orderBy("corpusName")->orderBy("name")->all();
            $data = array_map(fn($item) => [
                'id' => $item->idDocument,
                'text' => $documentIcon . $item->corpusName . ' / ' . $item->name,
                'state' => 'closed',
                'type' => 'document'
            ], $documents);
        }
        return $data;
    }

    public static function browseCorpusBySearch(object $search, array $projects = [], string $taskGroupName = '')
    {
        $corpusIcon = view('components.icon.corpus')->render();
        $data = [];
        $allowed = Project::getAllowedDocsForUser($projects, $taskGroupName);
        $allowedCorpus = array_keys(collect($allowed)->groupBy('idCorpus')->toArray());
        $corpus = Criteria::byFilterLanguage("view_corpus", ["name", "startswith", $search->corpus])
            ->whereIn("idCorpus", $allowedCorpus)
            ->orderBy("name")->all();
        foreach ($corpus as $c) {
            $data[] = [
                'id' => $c->idCorpus,
                'text' => $corpusIcon . $c->name,
                'state' => 'closed',
                'type' => 'corpus',
                'children' => []
            ];
        }
        return $data;
    }

    public static function browseDocumentBySearch(object $search, array $projects = [], string $taskGroupName = '', bool $leaf = false)
    {
        $documentIcon = view('components.icon.document')->render();
        $allowed = Project::getAllowedDocsForUser($projects, $taskGroupName);
        if ($search->document != '') {
            $data = [];
            if (strlen($search->document) > 2) {
                //$allowedCorpus = array_keys(collect($allowed)->groupBy('idCorpus')->toArray());
                $allowedDocuments = array_keys(
                    collect($allowed)
                        ->groupBy('idDocument')
                        ->toArray()
                );
                $documents = Criteria::byFilterLanguage("view_document", ["name", "contains", $search->document])
                    ->select('idDocument', 'name', 'corpusName', "idCorpus")
                    ->orderBy("corpusName")->orderBy("name")->all();
                foreach ($documents as $document) {
                    if ((isset($allowedDocuments[$document->idDocument]))) {
                        $data[] = [
                            'id' => $document->idDocument,
                            'text' => $documentIcon . $document->corpusName . ' / ' . $document->name,
                            'state' => 'closed',
                            'type' => 'document',
                            'leaf' => $leaf
                        ];
                    }
                }
            }
        } else if ($search->idCorpus != '') {
            $documentsByCorpus = (collect($allowed)->groupBy('idCorpus')->toArray())[$search->idCorpus];
            $allowedDocuments = collect($documentsByCorpus)->pluck('idDocument')->all();
            $documents = Criteria::byFilterLanguage("view_document", ["name", "startswith", $search->document])
                ->select('idDocument', 'name', 'corpusName')
                ->whereIn("idDocument", $allowedDocuments)
                ->orderBy("corpusName")->orderBy("name")->all();
            $data = array_map(fn($item) => [
                'id' => $item->idDocument,
                'text' => $documentIcon . $item->name,
                'state' => 'closed',
                'type' => 'document',
                'leaf' => $leaf
            ], $documents);
        }
        return $data;
    }

    public static function browseSentences(array $sentences): array
    {
        $data = [];
        foreach ($sentences as $sentence) {
            $data[] = [
                'id' => $sentence->idDocumentSentence,
                'text' => '[#' . $sentence->idDocumentSentence . ']  ' . $sentence->text,
                'state' => 'closed',
                'type' => 'sentence',
                'leaf' => true
            ];
        }
        return $data;
    }


}
