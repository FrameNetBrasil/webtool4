<?php

namespace App\Http\Controllers\Lexicon;

use App\Data\Lexicon\CreateLemmaData;
use App\Data\Lexicon\CreateWordformData;
use App\Data\Lexicon\UpdateLexemeData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Lemma;
use App\Services\AppService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware("master")]
class ResourceController extends Controller
{

    #[Post(path: '/lexicon/wordform/new')]
    public function newWordform(CreateWordformData $data)
    {
        try {
            Criteria::table("wordform")->insert([
                'idLexeme' => $data->idLexemeWordform,
                'form' => $data->form,
                'md5' => md5($data->form),
            ]);
            $this->trigger('reload-gridWordforms');
            return $this->renderNotify("success", "Wordform created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lexicon/wordform/{idWordForm}')]
    public function deleteWordform(string $idWordForm)
    {
        try {
            Criteria::deleteById("wordform", "idWordForm", $idWordForm);
            $this->trigger('reload-gridWordforms');
            return $this->renderNotify("success", "Wordform removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/lexicon/lexeme/{idLexeme}')]
    public function updateLexeme(UpdateLexemeData $data)
    {
        try {
            Criteria::table("lexeme")
                ->where("idLexeme", $data->idLexeme)
                ->update([
                'name' => $data->name,
                'idPOS' => $data->idPOS,
            ]);
            return $this->renderNotify("success", "Lexeme updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/lexicon/lemma/new')]
    public function newLemma(CreateLemmaData $data)
    {
        try {
            $newLemma = json_encode([
                'name' => $data->name,
                'idPOS' => $data->idPOS,
                'idLanguage' => $data->idLanguage,
            ]);
            $idLemma = Criteria::function("lemma_create(?)",[$newLemma]);
            $lemma = Lemma::byId($idLemma);
            $lexemeentries = Criteria::table("lexemeentry as le")
                ->join("lexeme", "le.idLexeme", "=", "lexeme.idLexeme")
                ->where("le.idLemma", $idLemma)
                ->select("le.*","lexeme.name as lexeme")
                ->orderBy("le.lexemeorder")
                ->all();
            return response()
                ->view('Lexicon.lemma',[
                    'lemma' => $lemma,
                    'lexemeentries' => $lexemeentries
                ])
                ->header('HX-Trigger', 'reload-gridLexicon');
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lexicon/lemma/{idLemma}')]
    public function deleteLemma(string $idLemma)
    {
        try {
            Criteria::function("lemma_delete(?,?)",[$idLemma, AppService::getCurrentIdUser()]);
            $this->trigger('reload-gridLexicon');
            return $this->renderNotify("success", "Lemma removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }



//    #[Get(path: '/frame/new')]
//    public function new()
//    {
//        return view("Frame.new");
//    }
//
//    #[Post(path: '/frame')]
//    public function store(CreateData $data)
//    {
//        try {
//            $idFrame = Frame::create($data);
//            return $this->clientRedirect("/frame/{$idFrame}");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
//
//    #[Delete(path: '/frame/{idFrame}')]
//    public function delete(string $idFrame)
//    {
//        try {
//            Frame::delete($idFrame);
//            return $this->clientRedirect("/frame");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
//
//    #[Get(path: '/frame/{id}')]
//    public function get(string $id)
//    {
//        return view("Frame.edit",[
//            'frame' => Frame::byId($id),
//            'classification' => Frame::getClassificationLabels($id)
//        ]);
//    }

}
