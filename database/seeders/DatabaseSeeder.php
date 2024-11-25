<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            FornecedoresSeeder::class,
            CategorySeeder::class,
            ProductsSeeder::class,
            StockMovementsSeeder::class,
        ]);
    }
}
