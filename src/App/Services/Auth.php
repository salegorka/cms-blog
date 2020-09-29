<?php

namespace App\Services;

use App\Exception\AuthException;
use App\Model\User;

class Auth {

    public static function authUser($email, $password)
    {

        $user = User::where('email', '=', $email)->get();

        if ($user->isEmpty()) {
            throw new AuthException('Неверное имя пользователя или пароль.1');
        }

        $user = $user->first();

        if (!password_verify($password, $user->password)) {
            throw new AuthException('Неверное имя пользователя или пароль.2');
        }

        $role = $user->role;

        $_SESSION['isUserAuthorized'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['rights'] = $role->id;

        return true;
    }

}