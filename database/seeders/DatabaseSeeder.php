<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        \App\Models\Fornecedor::factory()->count(10)->create();
        
        $this->call([
            CategorySeeder::class,
        ]);

        \App\Models\Product::factory()->count(100)->create();
    }
}
