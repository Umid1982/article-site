<?php

namespace App\Http\Controllers;

use App\Constatns\ResponseConstants\UserResponseEnum;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Article::paginate(10);

//            return ArticleResource::collection($articles);

            return response([
                'data' => ArticleResource::collection($articles),
                'message' => UserResponseEnum::ARTICLE_LIST,
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
                'success' => false,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $articleStoreRequest)
    {
        try {
            $validated = $articleStoreRequest->validated();
            $validated['image_path'] = Storage::disk('public')->put('images', $validated['image_path']);
            $articleCreate = Article::firstOrCreate($validated);
            return response([
                'data' => ArticleResource::make($articleCreate),
                'message' => UserResponseEnum::ARTICLE_CREATE,
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
                'success' => false,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        try {

            return response([
                'data' => ArticleResource::make($article),
                'message' => UserResponseEnum::ARTICLE_SHOW,
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
                'success' => false,
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleUpdateRequest $articleUpdateRequest, Article $article)
    {
        try {
            $validate = $articleUpdateRequest->validated();
            if (isset($validate['image_path'])) {
                Storage::disk('public')->delete('images', $article->image_path);
                $validate["image_path"] = Storage::disk('public')->put('images', $validate['image_path']);
            }
            $article->update($validate);

            return response([
                'data' => ArticleResource::make($article),
                'message' => UserResponseEnum::ARTICLE_UPDATE,
                'success' => true,
            ]);
        } catch (\Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
                'success' => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        try {
            $article->delete();
            Storage::disk('public')->delete('images', $article->image_path);
            return response([
                'message' => UserResponseEnum::ARTICLE_DELETE,
                'success' => true
            ]);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
                'success' => false,
            ]);
        }
    }
}
