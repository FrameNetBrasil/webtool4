<?php

namespace App\Http\Controllers\Document;

use App\Data\ComboBox\QData;
use App\Data\Document\CreateData;
use App\Data\Document\UpdateData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Repositories\Document;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware("master")]
class ResourceController extends Controller
{
//    #[Get(path: '/document')]
//    public function resource()
//    {
//        return view("Corpus.resource");
//    }
//
//    #[Get(path: '/document/grid/{fragment?}')]
//    #[Post(path: '/document/grid/{fragment?}')]
//    public function grid(SearchData $search, ?string $fragment = null)
//    {
//        $view = view("Corpus.grids",[
//            'search' => $search,
//            'sentences' => [],
//        ]);
//        return (is_null($fragment) ? $view : $view->fragment('search'));
//    }

    #[Get(path: '/document/{id}/edit')]
    public function edit(string $id)
    {
        return view("Document.edit",[
            'document' => Document::byId($id)
        ]);
    }

    #[Get(path: '/document/{id}/formCorpus')]
    public function formCorpus(string $id)
    {
        return view("Document.formCorpus",[
            'document' => Document::byId($id)
        ]);
    }

    #[Post(path: '/document')]
    public function update(UpdateData $data)
    {
        try {
            Criteria::table("document")
                ->where("idDocument", $data->idDocument)
                ->update(["idCorpus" => $data->idCorpus]);
            $this->trigger("reload-gridCorpus");
            return $this->renderNotify("success", "Document updated.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/document/new')]
    public function new()
    {
        return view("Document.formNew");
    }

    #[Post(path: '/document/new')]
    public function create(CreateData $data)
    {
        try {
            Criteria::function('document_create(?)', [$data->toJson()]);
            $this->trigger("reload-gridCorpus");
            return $this->renderNotify("success", "Document created.");
        } catch (\Exception $e) {
            return $this->renderNotify("error", $e->getMessage());
        }
    }

    #[Get(path: '/document/listForSelect')]
    public function listForSelect(QData $data)
    {
        $name = (strlen($data->q) > 2) ? $data->q : 'none';
        return ['results' => Criteria::byFilterLanguage("view_document",["name","startswith",$name])->orderby("name")->all()];
    }
//
//    #[Delete(path: '/document/{id}')]
//    public function delete(string $id)
//    {
//        try {
//            Criteria::function('dataset_delete(?, ?)', [
//                $id,
//                AppService::getCurrentIdUser()
//            ]);
//            return $this->clientRedirect("/document");
//        } catch (\Exception $e) {
//            return $this->renderNotify("error", $e->getMessage());
//        }
//    }
}