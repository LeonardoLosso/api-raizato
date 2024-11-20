<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Eletrônicos',
            'description' => 'Produtos eletrônicos como smartphones, televisores, etc.'
        ]);

        Category::create([
            'name' => 'Alimentos',
            'description' => 'Comidas, bebidas e produtos alimentícios.'
        ]);

        Category::create([
            'name' => 'Roupas',
            'description' => 'Vestimentas de diversos estilos e tamanhos.'
        ]);

        Category::create([
            'name' => 'Móveis',
            'description' => 'Móveis para casa e escritório.'
        ]);

        Category::create([
            'name' => 'Beleza',
            'description' => 'Produtos de cuidados pessoais e beleza.'
        ]);
    }

}
