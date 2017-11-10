<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'path'];

    /**
     * Get the post that owns the image.
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
