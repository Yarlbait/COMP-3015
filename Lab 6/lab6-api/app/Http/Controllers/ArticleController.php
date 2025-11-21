<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    //return 15 most recent created articles
    public function index() : JsonResponse
    {
        $articles = Article::query()
            ->orderByDesc('created_at')
            ->limit(15)
            ->get();

        return response()->json($articles);
    }

    //return single article
    public function show(int $id) : JsonResponse
    {
        $article = Article::find($id);

        if (!$article)
        {
            return response()->json([
                'message' => 'Article not found',
            ], 404);
        }

        // increment views
        $article->increment('views');

        // refresh to get updated value
        $article->refresh();

        return response()->json($article);
    }

    //Create and validate article
    public function store(Request $request) : JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:64'],
            'url'   => ['required', 'string', 'url', 'max:1024'],
        ]);

        // views will default to 0 from the migration
        $article = Article::create($validated);

        return response()->json($article, 201);
    }

    //update existing article
    public function update(Request $request, int $id) : JsonResponse
    {
        $article = Article::find($id);

        if (!$article)
        {
            return response()->json([
                'message' => 'Article not found',
            ], 404);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:64'],
            'url'   => ['required', 'string', 'url', 'max:1024'],
        ]);

        $article->update($validated);

        return response()->json($article);
    }

    //delete article
    public function destroy(int $id) : JsonResponse
    {
        $article = Article::find($id);

        if (!$article)
        {
            return response()->json([
                'message' => 'Article not found',
            ], 404);
        }

        $article->delete();

        // 204 = No Content
        return response()->json(null, 204);
    }
}

