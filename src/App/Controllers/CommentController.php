<?php

namespace App\Controllers;

use App\Exception\NotFoundException;
use App\Services\CommentManager;
use App\View\JsonResponse;

class CommentController extends Controller
{
    public function addComment()
    {
        parent::checkUserAuth();

        $commentManager = new CommentManager();
        $commentManager->addComment($_SESSION['userId'], $_POST['article'], $_POST['text']);


        $this->redirect('/article?id=' . $_POST['article']);
    }

    public function checkNewComment()
    {
        parent::checkContentManagerRights();

        $commentManager = new CommentManager();
        $commentManager->approveComment($_GET['id']);

        return new JsonResponse(['result' => 'success']);
    }

    public function deleteNewComment()
    {
        parent::checkContentManagerRights();

        $commentManager = new CommentManager();
        $commentManager->deleteComment($_GET['id']);

        return new JsonResponse(['result' => 'success']);
    }

    public function deleteComment()
    {
        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $deletingCommentId = $_GET['id'];
        }

        $commentManager = new CommentManager();
        $commentManager->deleteComment($deletingCommentId);

        return new JsonResponse(['result' => 'success']);
    }
}
