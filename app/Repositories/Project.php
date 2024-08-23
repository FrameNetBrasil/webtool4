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
        if (User::isManager($user) || (User::isMemberOf($user,'MASTER'))) {
            $criteria = Criteria::table("view_project_docs");
        } else {
            $criteria = Criteria::table("view_alloweddocs");
            $criteria = $criteria->where("idUser", $idUser);
        }
        return $criteria->where("idLanguage", AppService::getCurrentIdLanguage())
            ->whereIn('projectName', $projects)
            ->orderBy("projectName")
            ->orderBy("corpusName")
            ->orderBy("documentName")
            ->all();
    }


}
