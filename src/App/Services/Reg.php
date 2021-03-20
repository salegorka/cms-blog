<?php

namespace App\Services;

use App\Exception\RegException;
use App\Model\User;
use App\Model\Role;

class Reg
{
    public static function regNewUser($email, $username, $password, $passwordCheck)
    {
        $error = [];

        if (empty($email)) {
            $error['regEmailInput'] = 'Поле не должно быть пустым';
        }

        if (empty($username)) {
            $error['regUsernameInput'] = 'Поле не должно быть пустым';
        }

        if (empty($password)) {
            $error['regPasswordInput'] = 'Поле не должно быть пустым';
        }


        if ($password !== $passwordCheck) {
            $error['regPassControlInput'] = 'Введенные пароли не совпадают';
        }

        $user = User::where('username', '=', $username)->get();

        if (!$user->isEmpty()) {
            $error['regUsernameInput'] = 'Указанное имя пользователя уже занято';
        }

        $user = User::where('email', '=', $email)->get();

        if (!$user->isEmpty()) {
            $error['regEmailInput'] = 'Пользователь с указанной электронной почтой уже существует';
        }

        if (!empty($error)) {
            throw new RegException($error);
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $newUser = new User();
        $newUser->email = $email;
        $newUser->username = $username;
        $newUser->password = $password_hash;
        $role = Role::find(1);
        $newUser->role()->associate($role);

        $subscriberManager = new SubscribeManager();
        $newUser->subscribe = $subscriberManager->subscriberCheck($email);
        if ($subscriberManager->subscriberCheck($email)) {
            $subscriberManager->subscriberDel($email);
        }

        $newUser->save();

        $_SESSION['isUserAuthorized'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['rights'] = 1;
        $_SESSION['userId'] = $newUser->id;

        return true;
    }
}
