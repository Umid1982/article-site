<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image_path' => $this->image_path,
            'title' => $this->title,
            'description' => $this->description,
            'author_id' => $this->user_id,
            'date' => $this->created_at->diffForHumans(),
            'shows_count' => $this->shows_count,
        ];
    }
}
