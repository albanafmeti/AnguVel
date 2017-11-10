<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
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
            'content' => $this->content,
            'author' => $this->author,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'created_at_humans' => Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
