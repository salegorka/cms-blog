<?php

namespace App\Services;

use App\Model\User;
use App\Model\Role;

class UserManager {

    public function countUsers()
    {
        return User::where('id', '!=', 0)->count();
    }

    public function countUsersPage($chunk)
    {
        return ceil($this->countUsers() / $chunk);
    }

    public function loadUsers($chunk, $page)
    {
        $users = User::where("id", "!=", 0)->orderBy('id', 'asc')->skip(($page - 1) * $chunk)->take($chunk)->get();
        $usersArr = [];
        $users->each(function ($user) use (&$usersArr) {
            $usersArr[] = ['username' => $user->username, 'email' => $user->email, 'id' => $user->id,
                'role' => $user->role->name];
        });

        return $usersArr;
    }

    public function loadUserSubInfo($id)
    {
        $user = User::find($id);
        return ['id' => $user->id, 'email' => $user->email, 'subscribe' => $user->subscribe];
    }

    public function findUserByUsername($username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        return ['id' => $user->id, 'email' => $user->email, 'username' => $user->username, 'avatar' => $user->avatar,
            'about' => $user->about, 'role' => $user->role->name];
    }

    public function findUserById($id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();
        return ['id' => $user->id , 'email' => $user->email, 'username' => $user->username, 'avatar' => $user->avatar,
            'about' => $user->about, 'role' => $user->role->name, 'subscribe' => $user->subscribe];
    }

    public function setUserAbout($id, $about)
    {
        $user = User::find($id);
        $user->about = $about;
        $user->save();
    }

    public function setUserAvatar($id, $avatarLink)
    {
        $user = User::find($id);
        $user->avatar = $avatarLink;
        $user->save();
    }

    public function setUserRole($id, $roleId) {
        $user = User::find($id);
        $role = Role::find($roleId);

        $user->role()->associate($role);
        $user->save();
    }

    public function deleteUser($id)
    {
        User::destroy($id);
    }

}