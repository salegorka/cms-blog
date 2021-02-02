<?php

namespace App\Services;

use App\Model\Comment;
use App\Model\Article;
use App\Model\User;

class CommentManager {

    public function addComment($user_id, $article_id, $text)
    {
        $comment = new Comment();
        $comment->text = $text;

        if ($_SESSION['rights'] >= 2) {
            $comment->approved = true;
        } else {
            $comment->approved = false;
        }

        $article = Article::find($article_id);
        $user = User::find($user_id);

        $comment->author()->associate($user);
        $comment->article()->associate($article);

        $comment->save();

    }

    public function loadNewComments($chunk, $page)
    {
        $comments = Comment::where('approved', '=', 0)->orderBy('created_at', 'desc')->skip(($page - 1) * $chunk)->take($chunk)->get();
        $commentsArr = [];
        $comments->each(function ($comment) use (&$commentsArr) {
            $commentsArr[] = ['id' => $comment->id, 'text' => $comment->text, 'date' => $comment->created_at->format('Y-m-d H:i:s'),
                'username' => $comment->author->username, 'article' => $comment->article->name];
        });

        return $commentsArr;
    }

    public function approveComment($id)
    {
        $comment = Comment::find($id);
        $comment->approved = true;
        $comment->save();
    }

    public function countNewComments()
    {
        return Comment::where('approved', '=', 0)->count();
    }

    public function countNewCommentsPage($chunk)
    {
        return ceil($this->countNewComments() / $chunk);
    }

    public function countAllComments()
    {
        return Comment::where('id', '!=', 0)->count();
    }

    public function countAllCommentsPage($chunk)
    {
        return ceil($this->countAllComments() / $chunk);
    }

    public function loadAllComments($chunk, $page)
    {
        $comments = Comment::where('id', '!=', 0)->orderBy('created_at', 'desc')->skip(($page - 1) * $chunk)->take($chunk)->get();
        $commentsArr = [];
        $comments->each(function ($comment) use (&$commentsArr) {
            $commentsArr[] = ['id' => $comment->id, 'text' => $comment->text, 'date' => $comment->created_at->format('Y-m-d H:i:s'),
                'username' => $comment->author->username, 'article' => $comment->article->name];
        });

        return $commentsArr;
    }

    public function deleteComment($id) {
        Comment::destroy($id);
    }
}