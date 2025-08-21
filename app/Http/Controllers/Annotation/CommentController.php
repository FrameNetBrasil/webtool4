<?php

namespace App\Http\Controllers\Annotation;

use App\Data\Annotation\Comment\CommentData;
use App\Http\Controllers\Controller;
use App\Services\CommentService;
use Collective\Annotations\Routing\Attributes\Attributes\Delete;
use Collective\Annotations\Routing\Attributes\Attributes\Get;
use Collective\Annotations\Routing\Attributes\Attributes\Middleware;
use Collective\Annotations\Routing\Attributes\Attributes\Post;

#[Middleware(name: 'auth')]
class CommentController extends Controller
{
    #[Get(path: '/annotation/comment/form')]
    public function getFormComment(CommentData $data)
    {
        $object = CommentService::getComment($data);
        // Note: object can be null for new comments, which is handled by the view

        return view('Annotation.Comment.formComment', [
            'idDocument' => $data->idDocument,
            'order' => $data->order,
            'object' => $object,
        ]);
    }

    #[Post(path: '/annotation/comment/update')]
    public function updateComment(CommentData $data)
    {
        try {
            CommentService::updateComment($data);
            $this->trigger('updateObjectAnnotationEvent');

            return $this->renderNotify('success', 'Comment registered.');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

    #[Delete(path: '/annotation/comment/{idAnnotationComment}')]
    public function deleteComment(int $idAnnotationComment)
    {
        try {
            CommentService::deleteComment($idAnnotationComment);

            return $this->renderNotify('success', 'Comment removed.');
        } catch (\Exception $e) {
            return $this->renderNotify('error', $e->getMessage());
        }
    }

}
