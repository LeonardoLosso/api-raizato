<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"name", "code", "cost_price", "sale_price", "stock"},
 *     @OA\Property(property="id", type="integer", description="ID do produto"),
 *     @OA\Property(property="name", type="string", description="Nome do produto", example="Produto XYZ"),
 *     @OA\Property(property="code", type="string", description="Código identificador do produto", example="12345"),
 *     @OA\Property(property="description", type="string", description="Descrição do produto", example="Um excelente produto para uso diário."),
 *     @OA\Property(property="category_id", type="integer", description="ID da categoria associada ao produto"),
 *     @OA\Property(property="fornecedor_id", type="integer", description="ID do fornecedor associado ao produto"),
 *     @OA\Property(property="cost_price", type="number", format="float", description="Preço de custo do produto", example=15.50),
 *     @OA\Property(property="sale_price", type="number", format="float", description="Preço de venda do produto", example=20.00),
 *     @OA\Property(property="min_stock", type="integer", description="Quantidade mínima no estoque", example=10),
 *     @OA\Property(property="stock", type="integer", description="Quantidade disponível no estoque", example=50),
 *     @OA\Property(property="expiry_date", type="string", format="date", description="Data de validade do produto (se aplicável)", example="2025-12-31")
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
        return $this->belongsTo(Fornecedor::class, 'fornecedor_id');
    }
}
