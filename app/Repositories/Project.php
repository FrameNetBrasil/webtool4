<?php

namespace App\Repositories;

use App\Database\Criteria;
use App\Services\AppService;

class Project
{
    public static function byId(int $id): object
    {
        return Criteria::byFilter("project", ["idProject", "=", $id])->first();
    }

    public static function getAllowedDocsForUser(array $projects = [], string $taskGroup = ''): array
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        //debug($projects);
        if (User::isManager($user)) {
            $criteria = Criteria::table("view_project_docs as pd")
                ->join("view_project_tasks as pt", "pt.idProject", "=", "pd.idProject")
                ->where("pd.idLanguage", AppService::getCurrentIdLanguage())
                ->where("pd.idProject","<>", 1)
                ->select("pd.idCorpus","pd.corpusName","pd.idDocument","pd.documentName","pt.taskGroupName")
                ->orderBy("corpusName")
                ->orderBy("documentName");
            if (!empty($projects)) {
                $criteria = $criteria
                    ->whereIn('projectName', $projects);
            }
            if ($taskGroup != '') {
                $criteria = $criteria
                    ->where('pt.taskGroupName', $taskGroup);
            }
            $criteria = $criteria
                ->all();
        } else {
            $criteria = Criteria::table("view_alloweddocs as ad")
                ->join("view_project_docs as pd","pd.idCorpus","=","ad.idCorpus")
                ->where("ad.idUser", $idUser)
                ->where("pd.idProject","<>", 1)
                ->where("ad.idLanguage", AppService::getCurrentIdLanguage())
                ->where("pd.idLanguage", AppService::getCurrentIdLanguage())
                ->select("ad.idCorpus","ad.corpusName","ad.idDocument","ad.documentName")
                ->orderBy("ad.corpusName")
                ->orderBy("ad.documentName");
            if (!empty($projects)) {
                $criteria = $criteria
                    ->whereIn('projectName', $projects);
            }
            $criteria = $criteria
                ->all();
        }
        return $criteria;
    }


}
