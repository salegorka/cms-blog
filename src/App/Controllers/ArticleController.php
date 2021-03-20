<?php

namespace App\Controllers;

use App\Exception\NotFoundException;
use App\Services\ArticleManager;
use App\View\JsonResponse;
use App\View\View;

class ArticleController extends Controller
{
    public function loadArticleToView()
    {
        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $id = $_GET['id'];
        }

        $articleManager = new ArticleManager();
        $data = $articleManager->loadArticleToView($id);

        return new View('article', $data);
    }

    public function loadArticleToEdit()
    {
        parent::checkContentManagerRights();

        if (!(isset($_GET['id']))) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();

        $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

        return new View('admin.article.article-edit', $data);
    }

    public function updateArticle()
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

        try {
            $message = $articleManager->updateArticle($id, $data);
            $response = ['message' => $message, 'result' => 'success'];
        } catch (\Exception $e) {
            $message = "Статья с таким id не найдена";
            $response = ['message' => $message, 'result' => 'fail'];
        }

        return new JsonResponse($response);
    }

    public function createArticle()
    {
        parent::checkContentManagerRights();

        $articleManager = new ArticleManager();
        $id = $articleManager->createNewArticle();

        $this->redirect('/admin/article/edit?id=' . $id);
    }

    public function showArticle()
    {
        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager= new ArticleManager();
        $articleManager->showArticle($_GET['id']);

        return new JsonResponse(['result' => 'success']);
    }

    public function hideArticle()
    {
        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager= new ArticleManager();
        $articleManager->hideArticle($_GET['id']);

        return new JsonResponse(['result' => 'success']);
    }

    public function deleteArticle()
    {
        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($_GET['id']);

        return new JsonResponse(['result' => 'success']);
    }
}
