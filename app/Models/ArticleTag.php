<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id', 'article_id',];

    public function article()
    {
        return $this->hasMany(Article::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
