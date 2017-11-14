<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FetchedPost extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fid', 'title', 'subtitle', 'small_content', 'content', 'image_url', 'category', 'category_mapped', 'published_at', 'blog_url', 'imported'];
}
