<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CategoryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'posts' => PostResource::collection($this->posts),
            'created_at' => $this->created_at->format('d M | Y'),
            'order' => $this->order,
            'posts_nr' => $this->posts->count()
        ];
    }
}
