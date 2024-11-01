<?php

namespace App\Services;

use App\Database\Criteria;
use App\Repositories\Project;
use App\Repositories\User;

class AnnotationService
{
    private static function getCurrentUserTask(int $idDocument): object|null
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        // get usertask for this document
        $usertask = Criteria::table("usertask_document")
            ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
            ->where("usertask_document.idDocument", $idDocument)
            ->where("ut.idUser", $idUser)
            ->select("ut.idUserTask", "ut.idTask")
            ->first();
        if (empty($usertask)) { // usa a task -> dataset -> corpus -> document
            if (User::isManager($user) || User::isMemberOf($user, 'MASTER')) {
                $usertask = (object)[
                    "idUserTask" => 1
                ];
//                $usertask = Criteria::table("usertask_document")
//                    ->join("usertask as ut", "ut.idUserTask", "=", "usertask_document.idUserTask")
//                    ->where("usertask_document.idDocument", $idDocument)
//                    ->where("ut.idUser", -2)
//                    ->select("ut.idUserTask", "ut.idTask")
//                    ->first();
            } else {
                $usertask = null;
            }
        }
        return $usertask;
    }

    public static function browseCorpusDocumentBySearch(object $search, array $projects = []) {
        $corpusIcon = view('components.icon.corpus')->render();
        $documentIcon = view('components.icon.document')->render();
        $data = [];

        $allowed = Project::getAllowedDocsForUser($projects);
        $allowedCorpus = collect($allowed)->pluck('idCorpus')->all();
        $allowedDocuments = collect($allowed)->pluck('idDocument')->all();
        if ($search->document == '') {
            $corpus = Criteria::byFilterLanguage("view_corpus",["name","startswith", $search->corpus])
                ->whereIn("idCorpus", $allowedCorpus)
                ->orderBy("name")->get()->keyBy("idCorpus")->all();
            $ids = array_keys($corpus);
            $documents = Criteria::byFilterLanguage("view_document",["idCorpus","IN", $ids])
                ->whereIn("idDocument", $allowedDocuments)
                ->orderBy("name")
                ->get()->groupBy("idCorpus")
                ->toArray();
            foreach($corpus as $c) {
                $children = array_map(fn($item) => [
                    'id'=> $item->idDocument,
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
            $documents = Criteria::byFilterLanguage("view_document",["name","startswith", $search->document])
                ->select('idDocument','name','corpusName')
                ->whereIn("idDocuments", $allowedDocuments)
                ->orderBy("corpusName")->orderBy("name")->all();
            $data = array_map(fn($item) => [
                'id'=> $item->idDocument,
                'text' => $documentIcon . $item->corpusName . ' / ' . $item->name,
                'state' => 'closed',
                'type' => 'document'
            ], $documents);
        }
        return $data;
    }
}
