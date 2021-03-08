<?php

namespace App\Controllers;

use App\Exception\NotFoundException;
use App\Exception\BadAuthorizedException;
use App\Services\ArticleManager;
use App\View\View;

class ArticleController extends Controller
{

    public static function loadArticleToView()
    {

        $id = 0;

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $id = $_GET['id'];
        }

        $articleManager = new ArticleManager();
        $data = $articleManager->loadArticleToView($id);

        return new View('article', $data);

    }

    public static function loadArticleToEdit()
    {

        parent::checkContentManagerRights();

        if (!(isset($_GET['id']))) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();

        $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

        return new View('admin.article.article-edit', $data);

    }

    public static function updateArticle()
    {

        parent::checkContentManagerRights();

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

    public static function createArticle()
    {

        parent::checkContentManagerRights();

        $articleManager = new ArticleManager();
        $id = $articleManager->createNewArticle();

        header('Location: /admin/article/edit?id=' . $id);

    }

    public static function showArticle()
    {

        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager= new ArticleManager();
        $articleManager->showArticle($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public static function hideArticle()
    {

        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager= new ArticleManager();
        $articleManager->hideArticle($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

    public static function deleteArticle()
    {

        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

}