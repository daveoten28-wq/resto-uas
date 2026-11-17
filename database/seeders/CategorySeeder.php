<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name_id' => 'Makanan Utama', 'name_en' => 'Main Course', 'slug' => 'main-course'],
            ['name_id' => 'Makanan Pembuka', 'name_en' => 'Appetizer', 'slug' => 'appetizer'],
            ['name_id' => 'Hidangan Penutup', 'name_en' => 'Desserts', 'slug' => 'desserts'],
            ['name_id' => 'Minuman', 'name_en' => 'Drinks', 'slug' => 'drinks'],
            ['name_id' => 'Pasta', 'name_en' => 'Pasta', 'slug' => 'pasta'],
            ['name_id' => 'Masakan Tradisional', 'name_en' => 'Traditional', 'slug' => 'traditional'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category + [
                    'name' => $category['name_id'],
                    'description' => $category['name_id'],
                ]
            );
        }
    }
}
