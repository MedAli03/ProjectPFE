<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string',
        'img' => 'required|string'
    ]);

    $articleExists = Article::where('name', $validatedData['name'])->exists();

    if ($articleExists) {
        return response()->json(['message' => 'Article already exists'], 422);
    }

    $article = Article::create($validatedData);

    return response()->json($article, 201);
}

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'img' => 'required|string'
        ]);

        $article = Article::findOrFail($id);
        $article->update($validatedData);

        return response()->json($article, 200);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204);
    }
}
