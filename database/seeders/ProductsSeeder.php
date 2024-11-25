<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('products')->insert([
            ['name' => 'Smartphone XYZ', 'code' => 'SP1234', 'description' => 'Smartphone com 128GB de memória', 'category_id' => 1, 'fornecedor_id' => 1, 'cost_price' => 1200.00, 'sale_price' => 1500.00, 'min_stock' => 10, 'stock' => 50, 'expiry_date' => null],
            ['name' => 'Café Gourmet', 'code' => 'CF5678', 'description' => 'Café de qualidade superior', 'category_id' => 2, 'fornecedor_id' => 2, 'cost_price' => 15.00, 'sale_price' => 20.00, 'min_stock' => 5, 'stock' => 30, 'expiry_date' => '2025-12-31'],
            ['name' => 'Camiseta Estilosa', 'code' => 'CT9101', 'description' => 'Camiseta de algodão com estampa moderna', 'category_id' => 3, 'fornecedor_id' => 3, 'cost_price' => 25.00, 'sale_price' => 40.00, 'min_stock' => 3, 'stock' => 15, 'expiry_date' => null],
            ['name' => 'Sofá Conforto', 'code' => 'SF1122', 'description' => 'Sofá de 3 lugares em tecido macio', 'category_id' => 4, 'fornecedor_id' => 4, 'cost_price' => 800.00, 'sale_price' => 1200.00, 'min_stock' => 2, 'stock' => 8, 'expiry_date' => null],
            ['name' => 'Shampoo Hidratante', 'code' => 'SH3344', 'description' => 'Shampoo para cabelos secos', 'category_id' => 5, 'fornecedor_id' => 5, 'cost_price' => 12.00, 'sale_price' => 18.00, 'min_stock' => 10, 'stock' => 50, 'expiry_date' => '2026-05-01']
        ]);
    }
}
