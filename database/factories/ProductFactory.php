<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Fornecedor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'),
            'description' => $this->faker->sentence(10),
            'category_id' => Category::inRandomOrder()->first()->id,
            'fornecedor_id' => Fornecedor::inRandomOrder()->first()->id,
            'cost_price' => $this->faker->randomFloat(2, 10, 100),
            'sale_price' => $this->faker->randomFloat(2, 20, 200),
            'min_stock' => $this->faker->numberBetween(1, 100),
            'expiry_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ];
    }
}
