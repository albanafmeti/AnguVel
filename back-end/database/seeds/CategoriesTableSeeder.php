<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'showbiz' => 'Showbiz',
            'lifestyle' => 'Lifestyle'
        ];

        foreach ($data as $slug => $name) {
            DB::table('categories')->insert([
                'slug' => $slug,
                'name' => $name,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
