<?php

namespace App\Services;

use App\Exception\AuthException;
use App\Model\User;

class Auth
{
    public static function authUser($email, $password)
    {
        $user = User::where('email', '=', $email)->get();

        if ($user->isEmpty()) {
            throw new AuthException('Неверное имя пользователя или пароль');
        }

        $user = $user->first();

        if (!password_verify($password, $user->password)) {
            throw new AuthException('Неверное имя пользователя или пароль');
        }

        $role = $user->role;

        $_SESSION['isUserAuthorized'] = true;
        $_SESSION['rights'] = $role->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['userId'] = $user->id;

        return true;
    }

    public static function logout()
    {
        $_SESSION['isUserAuthorized'] = false;
        $_SESSION['rights'] = null;
        $_SESSION['username'] = null;
        $_SESSION['userId'] = null;

    }
}
