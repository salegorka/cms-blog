<?php

namespace App\Controllers;

use App\Config;
use App\Services\ArticleManager;
use App\Services\CommentManager;
use App\Services\UserManager;
use App\View\View;
use App\View\JsonResponse;

class AdminController extends Controller
{
    public function loadAdminMainPage()
    {
        parent::checkContentManagerRights();

        $settings = Config::getInstance();
        $data['mainPageArticleCount'] = $settings->get('mainSettings.mainPageArticleCount');

        return new View('admin.main', $data);
    }

    public function updateSettingsAdmin()
    {
        parent::checkContentManagerRights();

        $config = Config::getInstance();
        $config->updateMainArticlesCount( $_GET['count']);

        return new JsonResponse(['result' => 'success']);
    }

    public function loadArticlesList()
    {
        parent::checkContentManagerRights();

        $data['chunk'] = $_GET['chunk'] ?? 20;
        $data['page'] = $_GET['page'] ?? 1;

        $articleManager = new ArticleManager();

        $data['maxPage'] = $articleManager->countMaxPageAdmin($data['chunk']);
        $data['articles'] = $articleManager->loadArticlesToAdminPage($data['chunk'], $data['page']);

        return new View('admin.article.article', $data);
    }

    public function loadPageList()
    {
        parent::checkContentManagerRights();

        $config = Config::getInstance();
        $menu = $config->get("mainSettings.menu");

        $data = [];
        $data['pages'] = [];
        foreach($menu as $item) {
            $data['pages'][] = $item;
        }

        return new View('admin.staticPage.main', $data);
    }

    public function loadAllCommentList()
    {
        parent::checkContentManagerRights();

        $data['chunk'] = $_GET['chunk'] ?? 20;
        $data['page'] = $_GET['page'] ?? 1;

        $commentManager = new CommentManager();
        $data['maxPage'] = $commentManager->countAllCommentsPage($data['chunk']);
        $data['comments'] = $commentManager->loadAllComments($data['chunk'], $data['page']);

        return new View('admin.comments.main', $data);
    }

    public function loadNewCommentList()
    {
        parent::checkContentManagerRights();

        $data['chunk'] = $_GET['chunk'] ?? 20;
        $data['page'] = $_GET['page'] ?? 1;

        $commentManager = new CommentManager();
        $data['comments'] = $commentManager->loadNewComments($data['chunk'], $data['page']);
        $data['maxPage'] = $commentManager->countNewCommentsPage($data['chunk']);

        return new View('admin.comments.new', $data);
    }

    public function loadUserList()
    {
        parent::checkAdminRights();

        $data['chunk'] = $_GET['chunk'] ?? 20;
        $data['page'] = $_GET['page'] ?? 1;

        $userManager = new UserManager();
        $data['maxPage'] = $userManager->countUsersPage($data['chunk']);
        $data['users'] = $userManager->loadUsers($data['chunk'], $data['page']);

        return new View('admin.users.main', $data);
    }
}
