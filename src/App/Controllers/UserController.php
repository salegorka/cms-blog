<?php

namespace App\Controllers;

use App\Exception\BadAuthorizedException;
use App\Exception\NotFoundException;
use App\Exception\RegException;
use App\Services\UserManager;
use App\Services\Auth;
use App\Services\Reg;
use App\Services\SubscribeManager;
use App\View\View;

class UserController {

    public static function loginUser() {

        try {
            $result = Auth::authUser($_POST['loginEmailInput'], $_POST['loginPassInput']);
            header('Location: /');
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return new View('login', ['error' => $error]);
        }

    }

    public static function regNewUser() {

        if (!(isset($_POST['regCheck']) && $_POST['regCheck'] == 1)) {
            return new View('reg', ['error' => ['regCheck' => 'Необходимо согласиться с правилами сайта']]);
        }

        try {
            $result = Reg::regNewUser($_POST['regEmailInput'], $_POST['regUsernameInput'],
                $_POST['regPassInput'], $_POST['regPassControlInput']);
            header('Location: /');
        } catch(RegException $e) {
            $error = $e->error;
            return new View('reg', ['error' => $error]);
        }
        return 0;

    }

    public static function loadUserProfile() {

        if (!$_SESSION['isUserAuthorized']) {
            throw new BadAuthorizedException();
        }

        $data['id'] = $_SESSION['userId'];
        try {
            $userManager = new UserManager();
            $data['user'] = $userManager->findUserById($data['id']);
        } catch (\Exception $e) {
            throw new NotFoundException();
        }

        return new View('profile', $data);

    }

    public static function loadUserProfileToEdit() {

        if (!$_SESSION['isUserAuthorized']) {
            throw new BadAuthorizedException();
        }

        $data['id'] = $_SESSION['userId'];
        try {
            $userManager = new UserManager();
            $data['user'] = $userManager->findUserById($data['id']);
        } catch (\Exception $e) {
            throw new NotFoundException();
        }

        return new View('profile-edit', $data);

    }

    public static function updateUserProfile() {

        if (!$_SESSION['isUserAuthorized']) {
            throw new BadAuthorizedException();
        }

        $userManager = new UserManager();

        $answer = [];

        if (isset($_POST['about'])) {
            $about = clean($_POST['about']);
            if (mb_strlen($about) > 0) {
                $userManager->setUserAbout($_SESSION['userId'], $_POST['about']);
            }
        }


        if (!empty($_FILES['avatar']['name'])) {
            $fileTypes = ["image/jpeg", "image/png"];

            if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                $answer['error']['errorAvatar'] = "Внутрення ошибка сервера. Код ошибки:" . $_FILES['avatar']['error'];
            }
            elseif (!in_array($_FILES['avatar']['type'], $fileTypes))
            {
                $answer['error']['errorAvatar'] = "Ошибка: неверный формат файла! Разрешены:" . implode(" ", $fileTypes) . ". Файл " . $_FILES['avatar']['name'] . " не картинка!";
            }
            elseif ($_FILES['avatar']['size'] > 2*1e6) {
                $answer['error']['errorAvatar'] = "Ошибка: Максимальный размер файла 2Мб. Файл " . $_FILES['avatar']['name'] . " слишком большого размера!";
            }

            if (!isset($answer['error'])) {
                $dirname = $_SERVER['DOCUMENT_ROOT'] . '/img/avatars';
                $tmpname = $_FILES['avatar']['tmp_name'];
                $name = 'avatar' . $_SESSION['userId'] . mb_substr($_FILES['avatar']['name'], -4, 4);
                move_uploaded_file($tmpname, "$dirname/$name");
                $link = '/img/avatars/' . $name;
                $userManager->setUserAvatar($_SESSION['userId'], $link);
            }
        }

        if (!isset($answer['error'])) {
            $answer['ok'] = true;
            echo json_encode($answer);
        } else {
            echo json_encode($answer);
        }

    }

    public static function subscribeUserOn() {

        if (!$_SESSION['isUserAuthorized']) {
            throw new BadAuthorizedException();
        }

        $subscribeManager = new SubscribeManager();
        $subscribeManager->subscribeUserOn($_SESSION['userId']);

        header("HTTP/1.0 200 OK");

    }

    public static function subscribeUserOff() {

        if (!$_SESSION['isUserAuthorized']) {
            throw new BadAuthorizedException();
        }

        $subscribeManager = new SubscribeManager();
        $subscribeManager->subscribeUserOff($_SESSION['userId']);

        header("HTTP/1.0 200 OK");
    }

    public static function loadUserInfoAdmin() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
            throw new BadAuthorizedException();
        }

        $data = [];
        $userManager = new UserManager();
        if (isset($_GET['username'])) {
            try {
                $data['user'] = $userManager->findUserByUsername($_GET['username']);
                $data['modelFound'] = true;
            } catch ( \Exception $e) {
                $data['modelFound'] = false;
            }
        } elseif (isset($_GET['id'])) {
            try {
                $data['user'] = $userManager->findUserById($_GET['id']);
                $data['modelFound'] = true;
            } catch ( \Exception $e) {
                $data['modelFound'] = false;
            }
        }

        return new View('admin.users.userInfo', $data);

    }

    public static function updateUserRole() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
            throw new BadAuthorizedException();
        }

        $userManager = new UserManager();
        $userManager->setUserRole($_GET['id'], $_GET['role']);

        header("HTTP/1.0 200 OK");

    }

    public static function deleteUser() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
            throw new BadAuthorizedException();
        }

        $userManager = new UserManager();
        $userManager->deleteUser($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

}