<?php

namespace App\Http\Controllers\Lexicon;

use App\Data\ComboBox\QData;
use App\Data\Lexicon\CreateLemmaData;
use App\Data\Lexicon\CreateLexemeData;
use App\Data\Lexicon\CreateLexemeEntryData;
use App\Data\Lexicon\CreateWordformData;
use App\Data\Lexicon\SearchData;
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
class Resource3Controller extends Controller
{

    #[Get(path: '/lexicon3')]
    public function browse()
    {
        $search = session('searchLexicon3') ?? SearchData::from();
        return view("Lexicon3.resource", [
            'search' => $search
        ]);
    }

    #[Get(path: '/lexicon3/grid/{fragment?}')]
    #[Post(path: '/lexicon3/grid/{fragment?}')]
    public function grid(SearchData $search, ?string $fragment = null)
    {
        $view = view("Lexicon3.grid", [
            'search' => $search
        ]);
        return (is_null($fragment) ? $view : $view->fragment('search'));
    }

    /*------
      Lemma
      ------ */

    #[Get(path: '/lexicon3/lemma/listForSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::byFilterLanguage("view_lemma",["name","startswith", trim($name)])
            ->select('idLemma','fullName as name')
            ->orderby("name")->all()];
    }

    #[Get(path: '/lexicon3/lemma/new')]
    public function formNewLemma()
    {
        return view("Lexicon3.formNewLemma");
    }

    #[Get(path: '/lexicon3/lemma/{idLemma}/lexemeentries')]
    public function lexemeentries(int $idLemma)
    {
        $lemma = Lemma::byId($idLemma);
        $lexemeentries = Criteria::table("lexemeentry as le")
            ->join("lexeme", "le.idLexeme", "=", "lexeme.idLexeme")
            ->join("pos", "lexeme.idPOS", "=", "pos.idPOS")
            ->where("le.idLemma", $idLemma)
            ->select("le.*", "lexeme.name as lexeme", "pos.pos")
            ->orderBy("le.lexemeorder")
            ->all();
        return view("Lexicon3.lexemeentries", [
            'lemma' => $lemma,
            'lexemeentries' => $lexemeentries
        ]);
    }

    #[Get(path: '/lexicon3/lemma/{idLemma}/{fragment?}')]
    public function lemma(int $idLemma, string $fragment = null)
    {
        $lemma = Lemma::byId($idLemma);
        $lexemeentries = Criteria::table("lexemeentry as le")
            ->join("lexeme", "le.idLexeme", "=", "lexeme.idLexeme")
            ->join("pos", "lexeme.idPOS", "=", "pos.idPOS")
            ->where("le.idLemma", $idLemma)
            ->select("le.*", "lexeme.name as lexeme", "pos.pos")
            ->orderBy("le.lexemeorder")
            ->all();
        $view = view("Lexicon3.lemma", [
            'lemma' => $lemma,
            'lexemeentries' => $lexemeentries
        ]);
        return (is_null($fragment) ? $view : $view->fragment($fragment));
    }

    #[Post(path: '/lexicon3/lemma/new')]
    public function newLemma(CreateLemmaData $data)
    {
        try {
            $exists = Criteria::table("view_lexicon_lemma")
                ->whereRaw("name = '{$data->name}' collate 'utf8mb4_bin'")
                ->where("idUDPOS", $data->idUDPOS)
                ->where("idLanguage", $data->idLanguage)
                ->first();
            if (!is_null($exists)) {
                throw new \Exception("Lemma already exists.");
            }
            $newLemma = json_encode([
                'form' => $data->name,
                'idLexiconGroup' => 2,
                'idLanguage' => $data->idLanguage,
                'idPOS' => $data->idPOS,
                'idUDPOS' => $data->idUDPOS,
            ]);
            $idLemma = Criteria::function("lexicon_create(?)", [$newLemma]);
            $lemma = Lemma::byId($idLemma);
//            $lexemeentries = Criteria::table("lexemeentry as le")
//                ->join("lexeme", "le.idLexeme", "=", "lexeme.idLexeme")
//                ->where("le.idLemma", $idLemma)
//                ->select("le.*", "lexeme.name as lexeme")
//                ->orderBy("le.lexemeorder")
//                ->all();
            $view = view('Lexicon3.lemma', [
                'lemma' => $lemma,
                'lexemeentries' => $lexemeentries
            ]);
            return $view->fragment("content");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/lexicon3/lemma/{idLemma}')]
    public function updateLemma(UpdateLayerGroupData $data)
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

    #[Delete(path: '/lexicon3/lemma/{idLemma}')]
    public function deleteLemma(string $idLemma)
    {
        try {
            Criteria::function("lemma_delete(?,?)", [$idLemma, AppService::getCurrentIdUser()]);
            $this->trigger('reload-gridLexicon3');
            return $this->renderNotify("success", "Lemma removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


    /*------
      Lexeme
      ------ */

    #[Get(path: '/lexicon3/lexeme/new')]
    public function formNewLexeme()
    {
        return view("Lexicon3.formNewLexeme");
    }

    #[Get(path: '/lexicon3/lexeme/{idLexeme}/wordforms')]
    public function wordforms(int $idLexeme)
    {
        $lexeme = Lexeme::byId($idLexeme);
        $wordforms = Criteria::table("wordform")
            ->where("idLexeme", $idLexeme)
            ->orderBy("form")
            ->all();
        return view("Lexicon3.wordforms", [
            'lexeme' => $lexeme,
            'wordforms' => $wordforms
        ]);
    }

    #[Get(path: '/lexicon3/lexeme/{idLexeme}/{fragment?}')]
    public function lexeme(int $idLexeme, string $fragment = null)
    {
        $lexeme = Lexeme::byId($idLexeme);
        $wordforms = Criteria::table("wordform")
            ->where("idLexeme", $idLexeme)
            ->orderBy("form")
            ->all();
        $view = view("Lexicon3.lexeme", [
            'lexeme' => $lexeme,
            'wordforms' => $wordforms
        ]);
        return (is_null($fragment) ? $view : $view->fragment('content'));
    }


    #[Post(path: '/lexicon3/lexeme/new')]
    public function newLexeme(CreateLexemeData $data)
    {
        try {
            $exists = Criteria::table("lexeme")
                ->whereRaw("name = '{$data->name}' collate 'utf8mb4_bin'")
                ->where("idPOS", $data->idPOS)
                ->where("idLanguage", $data->idLanguage)
                ->first();
            if (!is_null($exists)) {
                throw new \Exception("Lexeme already exists.");
            }
            $newLexeme = json_encode([
                'name' => $data->name,
                'idPOS' => $data->idPOS,
                'idLanguage' => $data->idLanguage,
            ]);
            $idLexeme = Criteria::function("lexeme_create(?)", [$newLexeme]);
            $lexeme = Lexeme::byId($idLexeme);
            $wordforms = Criteria::table("wordform")
                ->where("idLexeme", $idLexeme)
                ->orderBy("form")
                ->all();
            $view = view('Lexicon3.lexeme', [
                    'lexeme' => $lexeme,
                    'wordforms' => $wordforms
                ]);
            return $view->fragment("content");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/lexicon3/lexeme/{idLexeme}')]
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

    #[Delete(path: '/lexicon3/lexeme/{idLexeme}')]
    public function deleteLexeme(string $idLexeme)
    {
        try {
            Criteria::function("lexeme_delete(?,?)", [$idLexeme, AppService::getCurrentIdUser()]);
            $this->trigger('reload-gridLexicon3');
            return $this->renderNotify("success", "Lexeme removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    /*-----------
      LexemeEntry
      ----------- */

    #[Post(path: '/lexicon3/lexemeentry/new')]
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

    #[Delete(path: '/lexicon3/lexemeentries/{idLexemeEntry}')]
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

    /*--------
      Wordform
      -------- */
    #[Post(path: '/lexicon3/wordform/new')]
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

    #[Delete(path: '/lexicon3/wordform/{idWordForm}')]
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
}
