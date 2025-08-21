<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Resource\Video\CloneData;
use App\Data\Resource\Video\CreateBBoxData;
use App\Data\Resource\Video\CreateObjectData;
use App\Data\Resource\Video\GetBBoxData;
use App\Data\Resource\Video\ObjectFrameData;
use App\Data\Resource\Video\ObjectSearchData;
use App\Data\Resource\Video\UpdateBBoxData;
use App\Database\Criteria;
use App\Http\Controllers\Controller;
use App\Services\Resource\VideoService;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class VideoController extends Controller
{
    #[Get(path: '/annotation/video/script/{folder}')]
    public function jsObjects(string $folder)
    {
        return response()
            ->view("Annotation.Video.Scripts.{$folder}")
            ->header('Content-type', 'text/javascript')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    #[Get(path: '/annotation/video/object')]
    public function getObject(ObjectSearchData $data)
    {
        if ($data->idObject == 0) {
            return view('Annotation.Video.Forms.formNewObject');
        }
        $object = VideoService::getObject($data->idObject ?? 0);
        $object->annotationType = $data->annotationType;
        if (is_null($object)) {
            return $this->renderNotify('error', 'Object not found.');
        }

        return response()
            ->view('Annotation.Video.Panes.object', [
                'object' => $object,
                'annotationType' => $data->annotationType
            ])->header('HX-Push-Url', "/annotation/{$data->annotationType}/{$object->idDocument}/{$object->idObject}");
    }

    #[Post(path: '/annotation/video/object/search')]
    public function objectSearch(ObjectSearchData $data)
    {
        $searchResults = VideoService::objectSearch($data);
        return view('Annotation.Video.Panes.search', [
            'searchResults' => $searchResults,
            'idDocument' => $data->idDocument,
        ])->fragment('search');
    }

    #[Post(path: '/annotation/video/createNewObjectAtLayer')]
    public function createNewObjectAtLayer(CreateObjectData $data)
    {
        debug($data);
        try {
            $object = VideoService::createNewObjectAtLayer($data);

            return $this->redirect("/annotation/{$data->annotationType}/{$object->idDocument}/{$object->idDynamicObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/video/cloneObject')]
    public function cloneObject(CloneData $data)
    {
        try {
            $idDynamicObjectClone = VideoService::cloneObject($data);

            return $this->redirect("/annotation/{$data->annotationType}/{$data->idDocument}/{$idDynamicObjectClone}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/video/updateObjectRange')]
    public function updateObjectRange(ObjectFrameData $data)
    {
        try {
            VideoService::updateObjectFrame($data);

            return $this->redirect("/annotation/{$data->annotationType}/{$data->idDocument}/{$data->idObject}");
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Get(path: '/annotation/video/getBBox')]
    public function getBBox(GetBBoxData $data)
    {
        try {
            return Criteria::table('view_dynamicobject_boundingbox')
                ->where('idDynamicObject', $data->idObject)
                ->where('frameNumber', $data->frameNumber)
                ->first();
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/video/createBBox')]
    public function createBBox(CreateBBoxData $data)
    {
        try {
            return VideoService::createBBox($data);
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Post(path: '/annotation/video/updateBBox')]
    public function updateBBox(UpdateBBoxData $data)
    {
        try {
            $idBoundingBox = VideoService::updateBBox($data);
            $boundingBox = Criteria::byId('boundingbox', 'idBoundingBox', $idBoundingBox);
            if (! $boundingBox) {
                return $this->renderNotify('error', 'Updated bounding box not found.');
            }

            return $boundingBox;
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }


}
