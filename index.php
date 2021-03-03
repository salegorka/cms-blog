<?php

error_reporting(E_ALL);
ini_set('display_errors',true);
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

$router = new \App\Router();

$router->get('/',"App\Controllers\BlogController::loadBlogMainPage");
$router->get('/page/*', "App\Controllers\PagesController::loadPageToView");
$router->get('/login', "App\Controllers\BlogController::loadLoginPage");
$router->post('/login', "App\Controllers\UserController::loginUser");
$router->get('/reg', "App\Controllers\BlogController::loadRegPage");
$router->post('/reg', "App\Controllers\UserController::regNewUser");
$router->get('/profile', "App\Controllers\UserController::loadUserProfile");
$router->get('/profile/edit', "App\Controllers\UserController::loadUserProfileToEdit");
$router->post('/profile/edit', "App\Controllers\UserController::updateUserProfile");
$router->get("/profile/subscribe/on", "App\Controllers\UserController::subscribeUserOn");
$router->get("/profile/subscribe/off", "App\Controllers\UserController::subscribeUserOff()");
$router->post("/subscriber/new", "App\Controllers\SubscriberController::createSubscriber");
$router->get("/subscriber/del", "App\Controllers\SubscriberController::deleteSubscriber");
$router->get('/article', "App\Controllers\ArticleController::loadArticleToView");
$router->post('/comment/add', "App\Controllers\CommentController::addComment");
$router->get('/logout', "App\Controllers\BlogController::logout");
$router->get('/admin', "App\Controllers\AdminController::loadAdminMainPage");
$router->get("/admin/settings", "App\Controllers\AdminController::updateSettingsAdmin");
$router->get('/admin/article', "App\Controllers\AdminController::loadArticlesList");
$router->get('/admin/article/edit', "App\Controllers\ArticleController::loadArticleToEdit");
$router->post('/admin/article/edit', "App\Controllers\ArticleController::updateArticle");
$router->get('/admin/article/new', "App\Controllers\ArticleController::createArticle");
$router->get('/admin/article/show', "App\Controllers\ArticleController::showArticle");
$router->get('/admin/article/hide', "App\Controllers\ArticleController::hideArticle");
$router->get('/admin/article/notification', "App\Controllers\SubscriberController::sendNotification");
$router->get('/admin/article/delete', "App\Controllers\ArticleController::deleteArticle");
$router->get('/admin/pages', "App\Controllers\AdminController::loadPageList");
$router->get('/admin/pages/edit', "App\Controllers\PagesController::loadPageToEdit");
$router->post('/admin/pages/edit', "App\Controllers\PagesController::updatePage");
$router->get('/admin/page/new', "App\Controllers\PagesController::createPage");
$router->get('/admin/page/delete', "App\Controllers\PagesController::deletePage");
$router->get('/admin/comments', "App\Controllers\AdminController::loadAllCommentList");
$router->get('/admin/comments/delete', "App\Controllers\CommentController::deleteComment");
$router->get('/admin/comments/new', "App\Controllers\AdminController::loadNewCommentList");
$router->get('/admin/comments/new/check', "App\Controllers\CommentController::checkNewComment");
$router->get('/admin/comments/new/delete', "App\Controllers\CommentController::deleteNewComment");
$router->get( '/admin/users', "App\Controllers\AdminController::loadUserList");
$router->get('/admin/users/find', "App\Controllers\UserController::loadUserInfoAdmin");
$router->get('/admin/users/delete', "App\Controllers\UserController::deleteUser");
$router->get('/admin/users/role', "App\Controllers\UserController::updateUserRole");

$application = new \App\Application($router);

$application->run();
