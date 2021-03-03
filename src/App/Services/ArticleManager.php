<?php

namespace App\Services;

use App\Model\Article;
use App\Exception\NotFoundException;
use App\Model\User;

class ArticleManager
{

    public function countArticles()
    {
        return Article::where('shown', '=', 1)->count();
    }

    public function countArticlesAdmin()
    {
        return Article::where('id', '!=', 0)->count();
    }

    public function countMaxPage($chunk)
    {
        return ceil($this->countArticles()/$chunk);
    }

    public function countMaxPageAdmin($chunk)
    {
        return ceil($this->countArticlesAdmin()/$chunk);
    }

    public function loadArticlesToPage($chunk, $page)
    {
        $articles = Article::where('shown', '=', 1)->orderBy('created_at', 'desc')->skip(($page - 1) * $chunk)->take($chunk)->get();
        $articlesArr = [];
        foreach($articles as $article) {
            $tmpArr = $article->toArray();
            $tmpArr['date'] = $article->created_at->format('Y-m-d');
            $tmpArr['author'] = $article->author->username;
            $articlesArr[] = $tmpArr;
        }
        return $articlesArr;
    }

    public function loadArticlesToAdminPage($chunk, $page)
    {
        $articles = Article::where('id', '!=', 0)->orderBy('created_at', 'desc')->skip(($page - 1) * $chunk)->take($chunk)->get();
        $articlesArr = [];
        foreach($articles as $article) {
            $tmpArr = $article->toArray();
            $tmpArr['author'] = $article->author->username;
            $articlesArr[] = $tmpArr;
        }
        return $articlesArr;
    }

    public function loadSingleArticle($id)
    {
        $article = Article::where('id', '=', $id)->get();

        if ($article->isEmpty()) {
            throw new NotFoundException();
        }

        $article=$article->first();

        return $article->toArray();
    }

    public function loadArticleToView($id)
    {
        try {
            $article = Article::findOrFail($id);
        } catch(\Exception $e) {
            throw new NotFoundException();
        }

        $data['article'] = $article->toArray();
        $data['article']['date'] = $article->created_at->format('Y-m-d');
        $comments = $article->comments;
        $commentsArr = [];
        $comments->each(function ($comment) use (&$commentsArr) {
            $commentsArr[] = ['id' => $comment->id, 'text' => $comment->text, 'date' => $comment->created_at->format('Y-m-d H:i:s'),
                'username' => $comment->author->username, 'avatar' => $comment->author->avatar,
                'approved' => $comment->approved];
        });
        $data['comments'] = $commentsArr;

        return $data;
    }

    public function updateArticle($id, $data)
    {
        $article = Article::findOrFail($id);

        if (isset($data['name'])) {
            $article->name = $data['name'];
        }

        if (isset($data['description'])) {
            $article->description = $data['description'];
        }

        if (isset($data['text'])) {
            $article->text = $data['text'];
        }

        if (isset($data['img'])) {
            $article->img = $data['img'];
        }

        $article->save();

        return "Данные успшено сохранены";
    }

    public function showArticle($id) {
        $article = Article::find($id);
        $article->shown = true;
        $article->save();
    }

    public function hideArticle($id) {
        $article = Article::find($id);
        $article->shown = false;
        $article->save();
    }

    public function createNewArticle()
    {

        $article = new Article();

        $article->name = 'Новая статья';
        $article->text = 'Текст';
        $article->description = 'Описание';
        $article->img = '';
        $article->shown = false;

        $currentUser = User::find($_SESSION['userId']);
        $article->author()->associate($currentUser);

        $article->save();
        return $article->id;

    }

    public function deleteArticle($id)
    {
        Article::destroy($id);
    }

}