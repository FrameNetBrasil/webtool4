<?php

namespace App\Http\Controllers\Lexicon;

use App\Data\Lexicon\CreateLemmaData;
use App\Data\Lexicon\CreateLexemeData;
use App\Data\Lexicon\CreateLexemeEntryData;
use App\Data\Lexicon\CreateWordformData;
use App\Data\Lexicon\UpdateLemmaData;
use App\Data\Lexicon\UpdateLexemeData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Lemma;
use App\Repositories\Lexeme;
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

    #[Post(path: '/lexicon/lexeme/new')]
    public function newLexeme(CreateLexemeData $data)
    {
        try {
            $newLexeme = json_encode([
                'name' => $data->lexeme,
                'idPOS' => $data->idPOS,
                'idLanguage' => $data->idLanguage,
            ]);
            $idLexeme = Criteria::function("lexeme_create(?)", [$newLexeme]);
            $lexeme = Lexeme::byId($idLexeme);
            $wordforms = Criteria::table("wordform")
                ->where("idLexeme", $idLexeme)
                ->orderBy("form")
                ->all();
            return response()
                ->view('Lexicon.lexeme', [
                    'lexeme' => $lexeme,
                    'wordforms' => $wordforms
                ]);
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

    #[Delete(path: '/lexicon/lexeme/{idLexeme}')]
    public function deleteLexeme(string $idLexeme)
    {
        try {
            Criteria::function("lexeme_delete(?,?)", [$idLexeme, AppService::getCurrentIdUser()]);
            $this->trigger('reload-gridLexicon');
            return $this->renderNotify("success", "Lexeme removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/lexicon/lemma/new')]
    public function newLemma(CreateLemmaData $data)
    {
        try {
            $newLemma = json_encode([
                'name' => $data->lemma,
                'idPOS' => $data->idPOS,
                'idLanguage' => $data->idLanguage,
            ]);
            $idLemma = Criteria::function("lemma_create(?)", [$newLemma]);
            $lemma = Lemma::byId($idLemma);
            $lexemeentries = Criteria::table("lexemeentry as le")
                ->join("lexeme", "le.idLexeme", "=", "lexeme.idLexeme")
                ->where("le.idLemma", $idLemma)
                ->select("le.*", "lexeme.name as lexeme")
                ->orderBy("le.lexemeorder")
                ->all();
            return response()
                ->view('Lexicon.lemma', [
                    'lemma' => $lemma,
                    'lexemeentries' => $lexemeentries
                ]);
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/lexicon/lemma/{idLemma}')]
    public function updateLemma(UpdateLemmaData $data)
    {
        try {
            Criteria::table("lemma")
                ->where("idLemma", $data->idLemma)
                ->update([
                    'name' => $data->name,
                    'idPOS' => $data->idPOS,
                ]);
            return $this->renderNotify("success", "Lemma updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lexicon/lemma/{idLemma}')]
    public function deleteLemma(string $idLemma)
    {
        try {
            Criteria::function("lemma_delete(?,?)", [$idLemma, AppService::getCurrentIdUser()]);
            $this->trigger('reload-gridLexicon');
            return $this->renderNotify("success", "Lemma removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Post(path: '/lexicon/lexemeentry/new')]
    public function newLexemeEntry(CreateLexemeEntryData $data)
    {
        try {
            if ($data->idLexeme) {
                Criteria::table("lexemeentry")->insert([
                    'idLemma' => $data->idLemma,
                    'idLexeme' => $data->idLexeme,
                    'lexemeOrder' => $data->lexemeOrder,
                    'headWord' => $data->headWord,
                    'breakBefore' => $data->breakBefore
                ]);
                $this->trigger('reload-gridLexemeEntry');
                return $this->renderNotify("success", "Lexeme added.");
            } else {
                throw new \Exception("Lexeme not found.");
            }
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/lexicon/lexemeentries/{idLexemeEntry}')]
    public function deleteLexemeEntry(string $idLexemeEntry)
    {
        try {
            Criteria::deleteById("lexemeentry", "idLexemeEntry", $idLexemeEntry);
            $this->trigger('reload-gridLexemeEntry');
            return $this->renderNotify("success", "Lexeme removed.");
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
