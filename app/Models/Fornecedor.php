<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Fornecedor",
 *     type="object",
 *     required={"nome", "cnpj"},
 *     @OA\Property(property="id", type="integer", description="ID do fornecedor"),
 *     @OA\Property(property="nome", type="string", description="Nome do fornecedor", example="Fornecedor ABC"),
 *     @OA\Property(property="cnpj", type="string", description="CNPJ do fornecedor (apenas números)", example="12345678000199"),
 *     @OA\Property(property="contato", type="string", description="Contato do fornecedor", example="contato@fornecedor.com")
 * )
 */
class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';

    protected $fillable = [
        'nome',
        'cnpj',
        'contato',
    ];
}
