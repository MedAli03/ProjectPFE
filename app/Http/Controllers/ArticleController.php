<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    

    public function index()
    {
        $articles = Article::where('is_available', true)->orderBy('name')->get();
        return response()->json($articles);
    }

    public function getNotAvailableArticle()
    {
        $articles = Article::where('is_available', false)->orderBy('name')->get();
        return response()->json($articles);
    }

    /**
     * @OA\Get(
     *     path="/api/client/article/{id}",
     *     tags={"Client"},
     *     summary="Get an article by ID",
     *     description="Get the article with the specified ID.",
     *     operationId="getArticleById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article to get",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Article not found"
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    /**
     * Create a new article
     *
     * @OA\Post(
     *      path="api/pressing/article",
     *      summary="Create a new article",
     *      description="Create a new article with a given name and image",
     *      operationId="createArticle",
     *      tags={"Pressing"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Provide name and image for the new article",
     *          @OA\JsonContent(
     *              required={"name","img"},
     *              @OA\Property(property="name", type="string", example="Article Name"),
     *              @OA\Property(property="img", type="string", example="https://example.com/image.jpg")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Article created successfully"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable entity",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Article already exists")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Server error"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_available' => 'sometimes|boolean'
        ]);
    
        $article = new Article;
        $article->name = $validatedData['name'];
        $article->is_available = $validatedData['is_available'] ?? true;
        $article->save();
    
        return response()->json($article, 201);
    }

    public function storeFromPressing(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_available' => 'sometimes|boolean'
        ]);
    
        $articleExists = Article::where('name', $validatedData['name'])->exists();
    
        if ($articleExists) {
            return response()->json(['message' => 'Article already exists'], 422);
        }
    
        $article = new Article;
        $article->name = $validatedData['name'];
        $article->is_available = $validatedData['is_available'] ?? false;
        $article->save();
    
        return response()->json($article, 201);
    }
    

    
    /**
     * @OA\Put(
     *     path="api/pressings/article/{id}",
     *     summary="Update an article",
     *     description="Update an article by ID",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="img",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The updated article"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="error",
     *                 type="string",
     *                 example="Article not found"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data provided",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 nullable=true
     *             )
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $article = Article::findOrFail($id);
        $article->update($validatedData);

        return response()->json($article, 200);
    }

    /**
     * @OA\Delete(
     *     path="api/pressings/article/{id}",
     *     summary="Delete an article by ID",
     *     tags={"Pressing"},
     *     @OA\Parameter(
     *         name="id",
     *         description="ID of the article to delete",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *     ),
     *     security={
     *         {"Bearer": {}}
     *     }
     * )
     */

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204);
    }
}
