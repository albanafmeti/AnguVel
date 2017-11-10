<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = \App\Category::all();
        $faker = Faker::create();

        foreach ($categories as $category) {
            $max = rand(4, 16);
            for ($i = 0; $i < $max; $i++) {
                $category->posts()->create([
                    'slug' => $faker->slug(3),
                    'title' => $faker->sentence(6),
                    'small_content' => $faker->text(),
                    'content' => $faker->paragraphs(5, true),
                    'image' => $faker->imageUrl(),
                    'author' => 'Admin'
                ]);
            }
        }
    }
}
