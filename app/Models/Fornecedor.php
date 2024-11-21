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
 *     @OA\Property(property="contato", type="string", description="Contato do fornecedor", example="contato@fornecedor.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação do fornecedor", example="2024-11-21T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data da última atualização do fornecedor", example="2024-11-21T11:00:00Z")
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
