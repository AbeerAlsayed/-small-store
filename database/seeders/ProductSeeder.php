<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name'=>'php',
            'slug'=>Str::slug('php'),
            'description'=>'test one',
            'price'=>'2.5',
            'category_id'=>'1'
        ]);
    }
}
