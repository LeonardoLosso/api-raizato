<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FornecedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fornecedores')->updateOrInsert(
            ['cnpj' => '98765432000187'],
            ['nome' => 'Fornecedor B', 'contato' => 'contato@fornecedorB.com']
        );

        DB::table('fornecedores')->updateOrInsert(
            ['cnpj' => '23456789000123'],
            ['nome' => 'Fornecedor C', 'contato' => 'contato@fornecedorC.com']
        );

        DB::table('fornecedores')->updateOrInsert(
            ['cnpj' => '34567890000144'],
            ['nome' => 'Fornecedor D', 'contato' => 'contato@fornecedorD.com']
        );

        DB::table('fornecedores')->updateOrInsert(
            ['cnpj' => '12345678000195'],
            ['nome' => 'Fornecedor A', 'contato' => 'contato@fornecedorA.com']
        );

        DB::table('fornecedores')->updateOrInsert(
            ['cnpj' => '45678901234567'],
            ['nome' => 'Fornecedor E', 'contato' => 'contato@fornecedorE.com']
        );
    }
}
