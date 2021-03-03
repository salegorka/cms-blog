<?php

namespace App\Controllers;

use App\Exception\BadAuthorizedException;
use App\Exception\NotFoundException;
use App\Services\CommentManager;

class CommentController {

    public static function checkNewComment() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        $commentManager = new CommentManager();
        $commentManager->approveComment($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public function deleteNewComment() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        $commentManager = new CommentManager();
        $commentManager->deleteComment($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public static function deleteComment() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

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
