<?php

namespace App\Controllers;

use App\Exception\NotFoundException;
use App\Exception\BadAuthorizedException;
use App\Services\ArticleManager;
use App\Services\SubscribeManager;
use App\Controller;

class ArticleController
{

    public static function loadArticleToView() {

        $id = 0;

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $id = $_GET['id'];
        }

        $articleManager = new ArticleManager();
        $data = $articleManager->loadArticleToView($id);

        $controller = new Controller('article', $data);
        return $controller->render();

    }


    public static function loadArticleToEdit() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        if (!(isset($_GET['id']))) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();

        $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

        $controller = new Controller('admin.article.article-edit', $data);
        return $controller->render();

    }

    public static function updateArticle() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        $id = $_POST['id'];
        $data = [];

        if (isset($_POST['name'])) {
            $data['name'] = $_POST['name'];
        }

        if (isset($_POST['descr'])) {
            $data['description'] = $_POST['descr'];
        }

        if (isset($_POST['text'])) {
            $data['text'] = $_POST['text'];
        }

        if (isset($_POST['img'])) {
            $data['img'] = $_POST['img'];
        }

        $articleManager = new ArticleManager();
        $message = "";

        try {
            $message = $articleManager->updateArticle($id, $data);
        } catch (\Exception $e) {
            $message = "Статья с таким id не найдена";
        }

        //echo json_encode(['message' => $_POST]);
        echo json_encode(['message' => $message]);

    }

    public static function createArticle() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        $articleManager = new ArticleManager();
        $id = $articleManager->createNewArticle();

        header('Location: /admin/article/edit?id=' . $id);

    }

    public static function showArticle() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager= new ArticleManager();
        $articleManager->showArticle($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public static function hideArticle() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager= new ArticleManager();
        $articleManager->hideArticle($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public static function sendNotificationArticle() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();
        $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

        $subscribeManager = new SubscribeManager();
        $subscribeManager->sendNotification($data['article']['id'], $data['article']['name'], $data['article']['description']);

        echo json_encode(['ok' => 'Уведомление успешно отправлено']);

    }

    public static function deleteArticle() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

}