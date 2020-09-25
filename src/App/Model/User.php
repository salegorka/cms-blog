<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = 'users';
    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo('App\Model\Role', 'role_id');
    }

}