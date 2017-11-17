<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FbAppResult extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['app_id', 'user_id', 'user_name', 'image_url', 'title', 'description', 'data'];
}
