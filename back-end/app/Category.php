<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Category extends Model
{
    use SearchableTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['slug', 'name', 'description', 'image', 'order'];

    /**
     * The posts that belong to the category, which are enabled.
     */
    public function posts()
    {
        return $this->allPosts()->where('enabled', '1');
    }

    /**
     * The posts that belong to the category.
     */
    public function allPosts()
    {
        return $this->belongsToMany('App\Post');
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
            'categories.id' => 10,
            'categories.slug' => 10,
            'categories.name' => 9,
            'categories.description' => 2,
        ]
    ];
}
