<?php

namespace App\Controllers;

use App\Exception\BadAuthorizedException;
use App\Exception\NotFoundException;
use App\Services\CommentManager;

class CommentController extends Controller {

    public static function addComment()
    {

        parent::checkUserAuth();

        $commentManager = new CommentManager();
        $commentManager->addComment($_SESSION['userId'], $_POST['article'], $_POST['text']);

        header('Location: /article?id=' . $_POST['article']);

    }

    public static function checkNewComment()
    {

        parent::checkContentManagerRights();

        $commentManager = new CommentManager();
        $commentManager->approveComment($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public static function deleteNewComment()
    {

        parent::checkContentManagerRights();

        $commentManager = new CommentManager();
        $commentManager->deleteComment($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public static function deleteComment()
    {

        parent::checkContentManagerRights();

        $deletingCommentId = 0;

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $deletingCommentId = $_GET['id'];
        }

        $commentManager = new CommentManager();
        $commentManager->deleteComment($deletingCommentId);

        header("HTTP/1.0 200 OK");

    }

}
