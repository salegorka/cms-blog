<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    public function author()
    {
        return $this->belongsTo('App\Model\User', 'created_by');
    }

    public function comments()
    {
        return $this->hasMany('App\Model\Comment', 'article_id');
    }
}
