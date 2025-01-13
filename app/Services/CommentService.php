<?php

namespace App\Services;

use App\Data\Comment\CommentData;
use App\Database\Criteria;
use App\Repositories\Document;

class CommentService
{

    public static function getDynamicObjectComment(int $idDynamicObject): object|null
    {
        $do = Criteria::table("dynamicobject as do")
            ->leftJoin("annotationcomment as ac", "do.idDynamicObject", "=", "ac.idDynamicObject")
            ->leftJoin("user as u", "ac.idUser", "=", "u.idUser")
            ->where("do.idDynamicObject", $idDynamicObject)
            ->select("do.idDynamicObject", "do.startFrame", "do.endFrame", "ac.comment", "ac.createdAt", "ac.updatedAt", "u.email")
            ->first();
        return $do;
    }

    public static function deleteDynamicObjectComment(int $idDynamicObject): void
    {
        Criteria::deleteById("annotationcomment", "idDynamicObject", $idDynamicObject);
    }

    public static function updateDynamicObjectComment(CommentData $data): int
    {
        $idDynamicObject = $data->idDynamicObject;
        $idDocument = Criteria::table("view_annotation_dynamic as do")
            ->where("do.idDynamicObject", $idDynamicObject)
            ->first()->idDocument;
        $document = Document::byId($idDocument);
        $idProject = Criteria::table("view_project_docs as pd")
            ->where("pd.idDocument", $idDocument)
            ->first()->idProject;
        $users = Criteria::table("users as u")
            ->join("project_manager as pm", "u.idUser", "=", "pm.idUser")
            ->select("u.idUser", "u.email")
            ->where("pm.idProject", $idProject)
            ->get()->pluck("idUser")->all();
        $users[] = AppService::getCurrentIdUser();
        $comment = Criteria::byId("annotationcomment", "idDynamicObject", $idDynamicObject);
        if (is_null($comment)) {
            Criteria::create("annotationcomment", [
                "idDynamicObject" => $idDynamicObject,
                "comment" => $data->comment,
                "idUser" => $data->idUser,
                "createdAt" => $data->createdAt,
                "updatedAt" => $data->updatedAt,
            ]);
        } else {
            Criteria::table("annotationcomment")
                ->where("idDynamicObject", $idDynamicObject)
                ->update([
                    "comment" => $data->comment,
                    "updatedAt" => $data->updatedAt,
                ]);
        }
        foreach($users as $idUser) {
            MessageService::sendMessage((object)[
                'idUserFrom' => AppService::getCurrentIdUser(),
                'idUserTo' => $idUser,
                'class' => 'error',
                'text' => "Comment created/updated at document [{$document->name}] object [#{$idDynamicObject}].",
            ]);
        }
        return $idDynamicObject;
    }

}
