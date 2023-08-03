<?php

namespace App\Http\Controllers;

use App\Constatns\ResponseConstants\UserResponseEnum;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Http\Resources\ArticleLikesResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\TagResource;
use App\Models\Article;
use App\Models\ArticleTag;
use App\Models\Tag;
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
            $tags = [];
            $validated = $articleStoreRequest->validated();
            $validated['image_path'] = Storage::disk('public')->put('images', $validated['image_path']);
            $articleCreate = Article::query()->create($validated);

            foreach ($validated["tag"] as $tag) {
                if (Tag::query()->where('name', '=', $validated['tag'])) {
                    $tagId = Tag::updateOrCreate(['name' => $tags[] = $tag]);
                } else {
                    Tag::query()->create([
                        'name' => $tags[] = $tag
                    ]);
                }
                ArticleTag::query()->create([
                    'article_id' => $articleCreate->id,
                    'tag_id' => $tagId->id,
                ]);
            }
            return response([
                'data' => ArticleResource::make($articleCreate),
                'tags' => $tags,
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
            event('Article_count', $article);//счетчик просмотров статьи
            $tagArray = [];
            $articleTags = ArticleTag::query()->where('article_id', '=', $article->id)->get();
            foreach ($articleTags as $tag) {
                $tagArray[] = Tag::query()->whereIn('id', [$tag->tag_id])->get();
            }
            return response([
                'data' => ArticleLikesResource::make($article),
                'tags' => $tagArray,
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
            $tags = [];
            $validate = $articleUpdateRequest->validated();
            if (isset($validate['image_path'])) {
                Storage::disk('public')->delete('images', $article->image_path);
                $validate["image_path"] = Storage::disk('public')->put('images', $validate['image_path']);
            }
            $article->update($validate);

            foreach ($validate["tag"] as $tag) {
                if (Tag::query()->where('name', '=', $validate['tag'])) {
                    $tagId = Tag::updateOrCreate(['name' => $tags[] = $tag]);
                } else {
                    $tagId = Tag::query()->create([
                        'name' => $tags[] = $tag
                    ]);
                }
            }

            return response([
                'data' => ArticleResource::make($article),
                'tags' => $tags,
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

    public function articleLike(Article $article)
    {
        try {
            event('Likes_count', $article);//счетчик лайков статьи
            $data['is_liked'] = true;
            $data['likes_count'] = $article->likes_count;
            return response([
                'data' => $data,
                'message' => UserResponseEnum::ARTICLE_LIKES,
                'success' => true,
            ]);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
                'success' => false,
            ]);
        }
    }


}
