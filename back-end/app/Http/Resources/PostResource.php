<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Jenssegers\Date\Date;

class PostResource extends Resource
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
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'small_content' => $this->small_content,
            'content' => $this->content,
            'image' => $this->image,
            'author' => $this->author,
            'categories' => $this->categories()->select('categories.id as category_id', 'name', 'slug')->get(),
            'images' => $this->images->pluck('path', 'id'),
            'comments' => CommentResource::collection($this->comments),
            'created_at' => Date::parse($this->created_at)->format('d M | Y'),
            'created_at_humans' => Carbon::parse($this->created_at)->diffForHumans(),
            'comments_nr' => $this->comments->count(),
            'thumb_sm' => $this->thumb_sm,
            'thumb_md' => $this->thumb_md,
            'thumb_lg' => $this->thumb_lg,
            'thumb_original' => $this->thumb_original,
            'featured' => $this->featured ? true : false,
            'enabled' => $this->enabled ? true : false,
        ];
    }
}
