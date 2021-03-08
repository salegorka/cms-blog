<?php

namespace App\Controllers;


use App\Config;
use App\Exception\BadAuthorizedException;
use App\Services\ArticleManager;
use App\Services\CommentManager;
use App\Services\UserManager;
use App\View\View;

class AdminController extends Controller {

    public static function loadAdminMainPage()
    {

        parent::checkContentManagerRights();

        $settings = Config::getInstance();
        $data['mainPageArticleCount'] = $settings->get('mainSettings.mainPageArticleCount');

        return new View('admin.main', $data);

    }

    public static function updateSettingsAdmin()
    {

        parent::checkContentManagerRights();

        $config = Config::getInstance();
        $config->updateMainArticlesCount( $_GET['count']);

        header("HTTP/1.0 200 OK");

    }

    public static function loadArticlesList()
    {

        parent::checkContentManagerRights();

        $data['chunk'] = $_GET['chunk'] ?? 20;
        $data['page'] = $_GET['page'] ?? 1;

        $articleManager = new ArticleManager();

        $data['maxPage'] = $articleManager->countMaxPageAdmin($data['chunk']);
        $data['articles'] = $articleManager->loadArticlesToAdminPage($data['chunk'], $data['page']);

        return new View('admin.article.article', $data);

    }

    public static function loadPageList()
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

    public static function loadAllCommentList()
    {

        parent::checkContentManagerRights();

        $data['chunk'] = $_GET['chunk'] ?? 20;
        $data['page'] = $_GET['page'] ?? 1;

        $commentManager = new CommentManager();
        $data['maxPage'] = $commentManager->countAllCommentsPage($data['chunk']);
        $data['comments'] = $commentManager->loadAllComments($data['chunk'], $data['page']);

        return new View('admin.comments.main', $data);

    }

    public static function loadNewCommentList()
    {

        parent::checkContentManagerRights();

        $data['chunk'] = $_GET['chunk'] ?? 20;
        $data['page'] = $_GET['page'] ?? 1;

        $commentManager = new CommentManager();
        $data['comments'] = $commentManager->loadNewComments($data['chunk'], $data['page']);
        $data['maxPage'] = $commentManager->countNewCommentsPage($data['chunk']);

        return new View('admin.comments.new', $data);

    }

    public static function loadUserList()
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