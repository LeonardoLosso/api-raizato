<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockMovementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('stock_movements')->insert([
            ['product_id' => 1, 'movement_type' => 'compras', 'quantity' => 20.00, 'unit_price' => 1200.00, 'description' => 'Compra de novos smartphones', 'date' => '2024-11-20', 'user_id' => 1],
            ['product_id' => 2, 'movement_type' => 'vendas', 'quantity' => 5.00, 'unit_price' => 20.00, 'description' => 'Venda de café gourmet', 'date' => '2024-11-20', 'user_id' => 2],
            ['product_id' => 1, 'movement_type' => 'devolucoes', 'quantity' => 3.00, 'unit_price' => 40.00, 'description' => 'Devolução de camisetas com defeito', 'date' => '2024-11-19', 'user_id' => 1],
            ['product_id' => 2, 'movement_type' => 'perdas', 'quantity' => 1.00, 'unit_price' => 1200.00, 'description' => 'Perda de sofá danificado', 'date' => '2024-11-18', 'user_id' => 2],
            ['product_id' => 1, 'movement_type' => 'compras', 'quantity' => 10.00, 'unit_price' => 18.00, 'description' => 'Compra de shampoo hidratante', 'date' => '2024-11-20', 'user_id' => 1],
            ['product_id' => 2, 'movement_type' => 'vendas', 'quantity' => 5.00, 'unit_price' => 1500.00, 'description' => 'Venda de smartphones', 'date' => '2024-11-19', 'user_id' => 1],
            ['product_id' => 1, 'movement_type' => 'compras', 'quantity' => 20.00, 'unit_price' => 25.00, 'description' => 'Compra de camisetas para reposição', 'date' => '2024-11-18', 'user_id' => 1],
            ['product_id' => 2, 'movement_type' => 'vendas', 'quantity' => 2.00, 'unit_price' => 1200.00, 'description' => 'Venda de sofá para cliente', 'date' => '2024-11-17', 'user_id' => 2],
            ['product_id' => 1, 'movement_type' => 'perdas', 'quantity' => 1.00, 'unit_price' => 18.00, 'description' => 'Perda de shampoo por vencimento', 'date' => '2024-11-16', 'user_id' => 1],
            ['product_id' => 2, 'movement_type' => 'vendas', 'quantity' => 15.00, 'unit_price' => 20.00, 'description' => 'Venda de café gourmet', 'date' => '2024-11-15', 'user_id' => 2]
        ]);
    }
}
