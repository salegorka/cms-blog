<?php

namespace App\Controllers;

use App\Exception\BadAuthorizedException;
use App\Services\UserManager;
use App\Controller;

class UserController {

    public function loadUserInfoAdmin() {

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

        $controller = new Controller('admin.users.userInfo', $data);
        return $controller->render();

    }

    public function updateUserRole() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
            throw new BadAuthorizedException();
        }

        $userManager = new UserManager();
        $userManager->setUserRole($_GET['id'], $_GET['role']);

        header("HTTP/1.0 200 OK");

    }

    public function deleteUser() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
            throw new BadAuthorizedException();
        }

        $userManager = new UserManager();
        $userManager->deleteUser($_GET['id']);

        header("HTTP/1.0 200 OK");

    }

}