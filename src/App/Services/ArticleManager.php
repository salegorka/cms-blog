<?php

namespace App\Services;

use App\Model\Article;
use App\Exception\NotFoundException;
use App\Model\User;

class ArticleManager
{

    public function countArticles()
    {
        return Article::where('id', '!=', 0)->count();
    }

    public function countMaxPage($chunk)
    {
        return ceil($this->countArticles()/$chunk);
    }

    public function loadArticlesToPage($chunk, $page)
    {
        $articles = Article::where('id', '!=', 0)->orderBy('created_at', 'desc')->skip(($page - 1) * $chunk)->take($chunk)->get();
        //var_dump($articles->toArray())
        $articlesArr = [];
        foreach($articles as $article) {
            $tmpArr = $article->toArray();
            $tmpArr['author'] = $article->author->username;
            $articlesArr[] = $tmpArr;
        }
        //var_dump($articlesArr);
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

    public function createNewArticle() {

        $article = new Article();

        $article->name = 'Новая статья';
        $article->text = 'Текст';
        $article->description = 'Описание';
        $article->img = '';

        $currentUser = User::find($_SESSION['userId']);
        $article->author()->associate($currentUser);

        $article->save();
        return $article->id;

    }

    public function deleteArticle($id) {

        Article::destroy($id);

    }

}