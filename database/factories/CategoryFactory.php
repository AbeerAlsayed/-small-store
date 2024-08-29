<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => Str::slug($this->faker->unique()->words(3, true)),
            'description' => $this->faker->sentence,
            'parent_id' => null, // or you can set a default value or logic for parent_id
        ];
    }
}
