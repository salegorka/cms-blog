<?php

error_reporting(E_ALL);
ini_set('display_errors',true);
session_start();

use App\Controllers\BlogController;
use App\Controllers\PagesController;
use App\Controllers\UserController;
use App\Controllers\SubscriberController;
use App\Controllers\ArticleController;
use App\Controllers\CommentController;
use App\Controllers\AdminController;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

$router = new \App\Router();

$router->get('/',BlogController::class . "@loadBlogMainPage");
$router->get('/page/*', PagesController::class . "@loadPageToView");
$router->get('/login', BlogController::class . "@loadLoginPage");
$router->post('/login', UserController::class . "@loginUser");
$router->get('/reg', BlogController::class . "@loadRegPage");
$router->post('/reg', UserController::class . "@regNewUser");
$router->get('/profile', UserController::class .  "@loadUserProfile");
$router->get('/profile/edit', UserController::class . "@loadUserProfileToEdit");
$router->post('/profile/edit', UserController::class . "@updateUserProfile");
$router->get("/profile/subscribe/on", UserController::class . "@subscribeUserOn");
$router->get("/profile/subscribe/off", UserController::class . "@subscribeUserOff");
$router->post("/subscriber/new", SubscriberController::class . "@createSubscriber");
$router->get("/subscriber/del", SubscriberController::class . "@deleteSubscriber");
$router->get('/article', ArticleController::class . "@loadArticleToView");
$router->post('/comment/add', CommentController::class . "@addComment");
$router->get('/logout', BlogController::class . "@logout");
$router->get('/admin', AdminController::class . "@loadAdminMainPage");
$router->get("/admin/settings", AdminController::class . "@updateSettingsAdmin");
$router->get('/admin/article', AdminController::class . "@loadArticlesList");
$router->get('/admin/article/edit', ArticleController::class . "@loadArticleToEdit");
$router->post('/admin/article/edit', ArticleController::class . "@updateArticle");
$router->get('/admin/article/new', ArticleController::class . "@createArticle");
$router->get('/admin/article/show', ArticleController::class . "@showArticle");
$router->get('/admin/article/hide', ArticleController::class . "@hideArticle");
$router->get('/admin/article/notification', SubscriberController::class . "@sendNotification");
$router->get('/admin/article/delete', ArticleController::class . "@deleteArticle");
$router->get('/admin/pages', AdminController::class . "@loadPageList");
$router->get('/admin/pages/edit', PagesController::class . "@loadPageToEdit");
$router->post('/admin/pages/edit', PagesController::class . "@updatePage");
$router->get('/admin/page/new', PagesController::class . "@createPage");
$router->get('/admin/page/delete', PagesController::class . "@deletePage");
$router->get('/admin/comments', AdminController::class . "@loadAllCommentList");
$router->get('/admin/comments/delete', CommentController::class . "@deleteComment");
$router->get('/admin/comments/new', AdminController::class . "@loadNewCommentList");
$router->get('/admin/comments/new/check', CommentController::class . "@checkNewComment");
$router->get('/admin/comments/new/delete', CommentController::class . "@deleteNewComment");
$router->get( '/admin/users', AdminController::class . "@loadUserList");
$router->get('/admin/users/find', UserController::class . "@loadUserInfoAdmin");
$router->get('/admin/users/delete', UserController::class . "@deleteUser");
$router->get('/admin/users/role', UserController::class . "@updateUserRole");

$application = new \App\Application($router);

$application->run();
