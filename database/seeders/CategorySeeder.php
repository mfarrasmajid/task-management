<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::insert([
        ['name' => 'Work', 'created_at'=>now(),'updated_at'=>now()],
        ['name' => 'Personal', 'created_at'=>now(),'updated_at'=>now()],
        ['name' => 'Learning', 'created_at'=>now(),'updated_at'=>now()],
    ]);
    }
}
