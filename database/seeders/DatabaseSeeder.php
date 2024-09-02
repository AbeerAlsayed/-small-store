<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory()->count(10)->create();
        Category::factory()->count(4)->create()->each(function ($category) {
            Category::factory()->count(3)->create(['parent_id' => $category->id]);
        });
        User::factory()->count(50)->create();
        Order::factory()->count(50)->create();


        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,

        ]);
    }
}
