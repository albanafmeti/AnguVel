<?php

namespace App\Console\Commands;

use App\Category;
use App\FetchedPost;
use App\Post;
use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;

class FetcherImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetcher:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import posts from fetcher table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fetchedPosts = FetchedPost::where('imported', '0')->get();

        foreach ($fetchedPosts as $fpost) {

            try {
                $imgFilename = basename($fpost->image_url);
                Image::make($fpost->image_url)->save(public_path('assets/images/posts/' . $imgFilename));

                $post = Post::create([
                    'slug' => str_slug($fpost->title),
                    'title' => $fpost->title,
                    'small_content' => $fpost->small_content,
                    'content' => $fpost->content,
                    'image' => "posts/$imgFilename",
                    'author' => "Admin",
                    'featured' => "0",
                    'enabled' => "1",
                    'fetched_post_id' => $fpost->id,
                    'type' => 'fetched'
                ]);

                $category = Category::where('name', $fpost->category_mapped)->first();

                if ($category) {
                    $post->categories()->attach($category->id);
                }

                $fpost->imported = '1';
                $fpost->save();
            } catch (\Exception $ex) {
                continue;
            }
        }
    }
}
