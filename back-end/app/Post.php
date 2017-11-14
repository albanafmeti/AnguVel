<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use Noisim\Thumbnail\Facades\Thumbnail;

class Post extends Model
{
    use SearchableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'title', 'subtitle', 'small_content', 'content', 'image', 'author', 'featured', 'enabled', 'fetched_post_id', 'type'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['thumb_sm', 'thumb_md', 'thumb_lg', 'thumb_original'];

    /**
     * The categories that belong to the post.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    /**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get the images for the blog post.
     */
    public function images()
    {
        return $this->hasMany('App\PostImage');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the post's sm thumbnail.
     *
     * @param  string $value
     * @return string
     */
    public function getThumbOriginalAttribute()
    {
        return Thumbnail::thumbnail($this->attributes['image']);
    }

    /**
     * Get the post's sm thumbnail.
     *
     * @param  string $value
     * @return string
     */
    public function getThumbSmAttribute()
    {
        return Thumbnail::thumbnail($this->attributes['image'], 120, 120);
    }

    /**
     * Get the post's md thumbnail.
     *
     * @param  string $value
     * @return string
     */
    public function getThumbMdAttribute()
    {
        return Thumbnail::thumbnail($this->attributes['image'], 640, 425);
    }

    /**
     * Get the post's lg thumbnail.
     *
     * @param  string $value
     * @return string
     */
    public function getThumbLgAttribute()
    {
        return Thumbnail::thumbnail($this->attributes['image'], 1200, 630, 'background');
    }

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'posts.id' => 10,
            'posts.slug' => 10,
            'posts.title' => 9,
            'posts.subtitle' => 8,
            'posts.content' => 5
        ]
    ];
}
