<?php

namespace App\Controllers;

use App\Exception\JsonException;
use App\Exception\NotFoundException;
use App\Exception\RegException;
use App\Services\UserManager;
use App\Services\Auth;
use App\Services\Reg;
use App\Services\SubscribeManager;
use App\View\JsonResponse;
use App\View\View;

class UserController extends Controller
{
    public function loginUser()
    {
        try {
            Auth::authUser($_POST['loginEmailInput'], $_POST['loginPassInput']);
            $this->redirect('/');
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return new View('login', ['error' => $error]);
        }
    }

    public function regNewUser()
    {
        if (!(isset($_POST['regCheck']) && $_POST['regCheck'] == 1)) {
            return new View('reg', ['error' => ['regCheck' => 'Необходимо согласиться с правилами сайта']]);
        }

        try {
            Reg::regNewUser($_POST['regEmailInput'], $_POST['regUsernameInput'],
                $_POST['regPassInput'], $_POST['regPassControlInput']);
            $this->redirect('/');
        } catch(RegException $e) {
            $error = $e->error;
            return new View('reg', ['error' => $error]);
        }
        return 0;
    }

    public function loadUserProfile()
    {
        parent::checkUserAuth();

        $data['id'] = $_SESSION['userId'];
        try {
            $userManager = new UserManager();
            $data['user'] = $userManager->findUserById($data['id']);
        } catch (\Exception $e) {
            throw new NotFoundException();
        }

        return new View('profile', $data);
    }

    public function loadUserProfileToEdit()
    {
        parent::checkUserAuth();

        $data['id'] = $_SESSION['userId'];
        try {
            $userManager = new UserManager();
            $data['user'] = $userManager->findUserById($data['id']);
        } catch (\Exception $e) {
            throw new NotFoundException();
        }

        return new View('profile-edit', $data);
    }

    public function updateUserProfile()
    {
        parent::checkUserAuth();

        $userManager = new UserManager();

        $error = [];

        if (isset($_POST['about'])) {
            $about = clean($_POST['about']);
            if (mb_strlen($about) > 0) {
                $userManager->setUserAbout($_SESSION['userId'], $_POST['about']);
            }
        }

        if (!empty($_FILES['avatar']['name'])) {
            $fileTypes = ["image/jpeg", "image/png"];

            if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
                $error['errorAvatar'] = "Внутрення ошибка сервера. Код ошибки:" . $_FILES['avatar']['error'];
                throw new JsonException($error);
            }
            elseif (!in_array($_FILES['avatar']['type'], $fileTypes))
            {
                $error['errorAvatar'] = "Ошибка: неверный формат файла! Разрешены:" . implode(" ", $fileTypes) . ". Файл " . $_FILES['avatar']['name'] . " не картинка!";
                throw new JsonException($error);
            }
            elseif ($_FILES['avatar']['size'] > 2*1e6) {
                $error['errorAvatar'] = "Ошибка: Максимальный размер файла 2Мб. Файл " . $_FILES['avatar']['name'] . " слишком большого размера!";
                throw new JsonException($error);
            }



            $dirname = $_SERVER['DOCUMENT_ROOT'] . '/img/avatars';
            $tmpname = $_FILES['avatar']['tmp_name'];
            $name = 'avatar' . $_SESSION['userId'] . mb_substr($_FILES['avatar']['name'], -4, 4);
            $link = '/img/avatars/' . $name;
            $userManager->setUserAvatar($_SESSION['userId'], $link);
            move_uploaded_file($tmpname, "$dirname/$name");
        }

        return new JsonResponse(['result' => 'success']);
    }

    public function subscribeUserOn()
    {
        parent::checkUserAuth();

        $subscribeManager = new SubscribeManager();
        $subscribeManager->subscribeUserOn($_SESSION['userId']);

        return new JsonResponse(['result' => 'success']);
    }

    public function subscribeUserOff()
    {
        parent::checkUserAuth();

        $subscribeManager = new SubscribeManager();
        $subscribeManager->subscribeUserOff($_SESSION['userId']);

        return new JsonResponse(['result' => 'success']);
    }

    public function loadUserInfoAdmin()
    {
        parent::checkAdminRights();

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

    public function updateUserRole()
    {
        parent::checkAdminRights();

        $userManager = new UserManager();
        $userManager->setUserRole($_GET['id'], $_GET['role']);

        return new JsonResponse(['result' => 'success']);
    }

    public function deleteUser()
    {
        parent::checkAdminRights();

        $userManager = new UserManager();
        $userManager->deleteUser($_GET['id']);

        return new JsonResponse(['result' => 'success']);
    }
}
