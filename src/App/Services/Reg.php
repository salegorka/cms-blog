<?php

namespace App\Services;

use App\Exception\AuthException;
use App\Model\User;
use App\Model\Role;

class Reg
{

    public static function regNewUser($email, $username, $password, $passwordCheck)
    {
        if ($password !== $passwordCheck) {
            throw new AuthException('Введенные пароли не совпадают');
        }

        $user = User::where('email', '=', $email)->get();

        if (!$user->isEmpty()) {
            throw new AuthException('Пользователь с указанной электронной почтой уже существует');
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);


        //Переписать через create
        $newUser = new User();
        $newUser->email = $email;
        $newUser->username = $username;
        $newUser->password = $password_hash;
        $role = Role::find(1);
        $newUser->role()->associate($role);
        $newUser->save();


        $_SESSION['isUserAuthorized'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['rights'] = 1;

        return true;

    }

}