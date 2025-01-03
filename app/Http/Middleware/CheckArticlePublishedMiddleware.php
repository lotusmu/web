<?php

namespace App\Http\Middleware;

use App\Models\Content\Article;
use Closure;
use Illuminate\Http\Request;

class CheckArticlePublishedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('article');
        $article = Article::where('slug', $slug)->firstOrFail();

        if (! $article->is_published) {
            abort(404);
        }

        return $next($request);
    }
}
