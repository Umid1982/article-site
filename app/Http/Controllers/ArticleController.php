<?php

namespace App\Http\Controllers;

use App\Constatns\ResponseConstants\UserResponseEnum;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }
}
