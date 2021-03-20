<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    public function author()
    {
        return $this->belongsTo('App\Model\User', 'created_by');
    }

    public function article()
    {
        return $this->belongsTo('App\Model\Article', 'article_id');
    }
}
