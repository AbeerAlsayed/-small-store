<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'order_number' => $this->faker->unique()->numerify('ORD-#####'),
            'user_id' => \App\Models\User::factory(),
            'product_id' => \App\Models\Product::factory(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'declined']),
            'grand_total' => $this->faker->randomFloat(2, 20, 500),
            'quantity' => $this->faker->numberBetween(1, 10),
            'is_paid' => $this->faker->boolean,
            'payment_method' => $this->faker->randomElement(['cash_on_delivery', 'credit_card']),
            'notes' => $this->faker->sentence,
        ];
    }
}
