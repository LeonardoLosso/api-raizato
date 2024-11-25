<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Eletrônicos', 'description' => 'Produtos eletrônicos diversos'],
            ['name' => 'Alimentos', 'description' => 'Alimentos e bebidas'],
            ['name' => 'Roupas', 'description' => 'Roupas para todos os estilos'],
            ['name' => 'Móveis', 'description' => 'Móveis para casa e escritório'],
            ['name' => 'Beleza', 'description' => 'Produtos de beleza e cuidados pessoais'],
        ]);
    }
}
