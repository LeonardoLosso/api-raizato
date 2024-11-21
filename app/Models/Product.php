<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"name", "code", "category_id", "fornecedor_id", "cost_price", "sale_price", "min_stock", "expiry_date"},
 *     @OA\Property(property="id", type="integer", description="ID do produto"),
 *     @OA\Property(property="name", type="string", description="Nome do produto", example="Produto XYZ"),
 *     @OA\Property(property="code", type="string", description="Código único do produto", example="PROD001"),
 *     @OA\Property(property="description", type="string", description="Descrição do produto", example="Descrição detalhada do produto"),
 *     @OA\Property(property="category_id", type="integer", description="ID da categoria associada", example=1),
 *     @OA\Property(property="fornecedor_id", type="integer", description="ID do fornecedor associado", example=2),
 *     @OA\Property(property="cost_price", type="number", format="float", description="Preço de custo", example=25.5),
 *     @OA\Property(property="sale_price", type="number", format="float", description="Preço de venda", example=50.0),
 *     @OA\Property(property="min_stock", type="integer", description="Quantidade mínima em estoque", example=10),
 *     @OA\Property(property="stock", type="integer", description="Quantidade atual em estoque", example=50),
 *     @OA\Property(property="expiry_date", type="string", format="date", description="Data de validade do produto", example="2025-12-31"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação do produto", example="2024-11-21T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data da última atualização do produto", example="2024-11-21T11:00:00Z")
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category_id',
        'fornecedor_id',
        'cost_price',
        'sale_price',
        'min_stock',
        'stock',
        'expiry_date'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }
}
