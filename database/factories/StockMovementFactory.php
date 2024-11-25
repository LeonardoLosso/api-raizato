<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'movement_type' => $this->faker->randomElement(
                ['compras', 'devolucoes', 'vendas', 'perdas']
            ),
            'quantity' => $this->faker->numberBetween(1, 100),
            'unit_price' => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence,
            'date' => $this->now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
