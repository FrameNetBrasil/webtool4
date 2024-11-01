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

    public static function getAllowedDocsForUser(array $projects = []): array
    {
        $idUser = AppService::getCurrentIdUser();
        $user = User::byId($idUser);
        if (User::isManager($user)) {
            $criteria = Criteria::table("view_project_docs as pd")
                ->where("idLanguage", AppService::getCurrentIdLanguage())
                ->whereIn('projectName', $projects)
                ->select("idCorpus","corpusName","idDocument","documentName")
                ->orderBy("corpusName")
                ->orderBy("documentName")
                ->all();
        } else {
            $criteria = Criteria::table("view_alloweddocs as ad")
                ->join("view_project_docs as pd","pd.idCorpus","=","ad.idCorpus")
                ->where("ad.idUser", $idUser)
                ->where("ad.idLanguage", AppService::getCurrentIdLanguage())
                ->where("pd.idLanguage", AppService::getCurrentIdLanguage())
                ->whereIn('pd.projectName', $projects)
                ->select("ad.idCorpus","ad.corpusName","ad.idDocument","ad.documentName")
                ->orderBy("ad.corpusName")
                ->orderBy("ad.documentName")
                ->all();
        }
        return $criteria;
    }


}
