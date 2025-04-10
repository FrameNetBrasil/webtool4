<?php

namespace App\Http\Controllers\Sentence;

use App\Data\Sentence\SearchData;
use App\Data\Sentence\UpdateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;
use Collective\Annotations\Routing\Attributes\Attributes\Put;

#[Middleware("master")]
class ResourceController extends Controller
{

    #[Get(path: '/sentence')]
    public function browse()
    {
        $search = session('searchLexicon') ?? SearchData::from();
        return view("Sentence.resource", [
            'search' => $search
        ]);
    }

    #[Get(path: '/sentence/grid/{fragment?}')]
    #[Post(path: '/sentence/grid/{fragment?}')]
    public function grid(SearchData $search, ?string $fragment = null)
    {
        $view = view("Sentence.grid", [
            'search' => $search,
            'sentences' => [],
        ]);
        return (is_null($fragment) ? $view : $view->fragment('search'));
    }

    #[Get(path: '/sentence/new')]
    public function formSentenceNew()
    {
        return view("Sentence.formNew");
    }

    #[Get(path: '/sentence/{idSentence}')]
    public function sentence(int $idSentence)
    {
        $sentence = Criteria::byId("view_sentence","idSentence",$idSentence);
        return view("Sentence.edit", [
            'sentence' => $sentence,
        ]);
    }

    #[Get(path: '/sentence/{id}/editForm')]
    public function editForm(string $id)
    {
        $sentence = Criteria::byId("view_sentence","idSentence",$id);
        $as = Criteria::table("annotationset")
            ->where("idSentence", $id)
            ->all();
        return view("Sentence.editForm",[
            'sentence' => $sentence,
            'hasAS' => !empty($as)
        ]);
    }

    #[Post(path: '/sentence/new')]
    public function newSentence(CreateLemmaData $data)
    {
        try {
            $exists = Criteria::table("lemma")
                ->where("name", $data->name)
                ->where("idPOS", $data->idPOS)
                ->where("idLanguage", $data->idLanguage)
                ->first();
            if (!is_null($exists)) {
                throw new \Exception("Lemma already exists.");
            }
            $newLemma = json_encode([
                'name' => $data->name,
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
            $view = view('Sentence.lemma', [
                'lemma' => $lemma,
                'lexemeentries' => $lexemeentries
            ]);
            return $view->fragment("content");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Put(path: '/sentence')]
    public function updateSentence(UpdateData $data)
    {
        try {
            Criteria::table("sentence")
                ->where("idSentence", $data->idSentence)
                ->update([
                    'text' => $data->text
                ]);
            return $this->renderNotify("success", "Sentence updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Delete(path: '/sentence/{idSentence}')]
    public function deleteSentence(string $idSentence)
    {
        try {
            Criteria::function("sentence_delete(?,?)", [$idSentence, AppService::getCurrentIdUser()]);
            $this->trigger('reload-gridSentence');
            return $this->renderNotify("success", "Sentence removed.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }


}
