<?php

namespace App\Http\Controllers\Annotation;


use App\Data\Annotation\Session\SearchData;
use App\Data\Annotation\Session\SessionData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Services\Annotation\SessionService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("auth")]
class SessionController extends Controller
{
    #[Get(path: '/annotation/session/script/{folder}')]
    public function jsObjects(string $folder)
    {
        return response()
            ->view("Annotation.Session.Scripts.{$folder}")
            ->header('Content-type', 'text/javascript');
    }
    #[Post(path: '/annotation/session/start')]
    public function sessionStart(SessionData $data) {
//        debug("start",$data);
        $session = SessionService::startSession($data);
//        return $this->renderNotify("success", "Session started.");
        return response()->json([
            'success' => true,
            'session_token' => '',
            'startedAt' => $data->timestamp->toJSON()
        ]);
    }

    #[Post(path: '/annotation/session/end')]
    public function sessionEnd(SessionData $data) {
//        debug("end",$data);
        $session = SessionService::endSession($data);
//        return $this->renderNotify("success", "Session ended.");
        return response()->json([
            'success' => true,
            'session_token' => '',
            'endedAt' => $data->timestamp->toJSON()
        ]);
    }

    #[Get(path: '/annotation/session')]
    public function report()
    {
        $annotators = Criteria::table("annotation_session as a")
            ->join("user as u", "a.idUser", "=", "u.idUser")
            ->select("u.idUser", "u.email")
            ->distinct()
            ->keyBy("idUser")
            ->all();
        $criteria = Criteria::table("annotation_session as a")
            ->join("user as u", "a.idUser", "=", "u.idUser")
            ->join("document_sentence as ds", "a.idDocumentSentence", "=", "ds.idDocumentSentence")
            ->join("sentence as s", "ds.idSentence", "=", "s.idSentence")
            ->select("u.idUser", "u.email","a.idDocumentSentence","s.text")
            ->selectRaw("TIME_FORMAT(SEC_TO_TIME(sum(endedAt - startedAt)), '%i:%s') AS time")
            ->groupBy("u.idUser", "u.email", "a.idDocumentSentence","s.text");
        $data = $criteria->all();
        return view('Annotation.Session.report', [
            'annotators' => $annotators,
            'data' => $data,
        ]);
    }

    #[Post(path: '/annotation/session/search')]
    public function search(SearchData $search)
    {
        debug($search);
        $annotators = [];
        $criteria = Criteria::table("annotation_session as a")
            ->join("user as u", "a.idUser", "=", "u.idUser")
            ->join("document_sentence as ds", "a.idDocumentSentence", "=", "ds.idDocumentSentence")
            ->join("sentence as s", "ds.idSentence", "=", "s.idSentence")
            ->select("u.idUser", "u.email","a.idDocumentSentence","s.text")
            ->selectRaw("TIME_FORMAT(SEC_TO_TIME(sum(endedAt - startedAt)), '%i:%s') AS time")
            ->groupBy("u.idUser", "u.email", "a.idDocumentSentence","s.text");
        if ($search->idUser > 0) {
            $criteria = $criteria->where("a.idUser", "=", $search->idUser);
        }
        $data = $criteria->all();
        return view('Annotation.Session.report', [
            'annotators' => $annotators,
            'data' => $data,
        ])->fragment('search');;
    }

}

