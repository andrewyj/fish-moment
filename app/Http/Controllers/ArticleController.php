<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function show(Article $article) {
        return view('app.article.show', ['article' => $article]);
    }
}
