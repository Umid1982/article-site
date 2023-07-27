<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'article_id',
    ];
}
