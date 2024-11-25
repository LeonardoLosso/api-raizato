<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\StockGetScope;

/**
 * @OA\Schema(
 *     schema="StockMovement",
 *     type="object",
 *     required={"product_id", "movement_type", "quantity", "unit_price", "date", "user_id"},
 *     @OA\Property(property="id", type="integer", description="ID do movimento de estoque"),
 *     @OA\Property(property="product_id", type="integer", description="ID do produto relacionado ao movimento", example=1),
 *     @OA\Property(property="movement_type", type="string", description="Tipo de movimento (entrada ou saída)", example="entrada"),
 *     @OA\Property(property="quantity", type="number", format="float", description="Quantidade do movimento", example=10),
 *     @OA\Property(property="unit_price", type="number", format="float", description="Preço unitário do produto no movimento", example=15.50),
 *     @OA\Property(property="description", type="string", description="Descrição ou observação sobre o movimento", example="Compra de mercadoria"),
 *     @OA\Property(property="date", type="string", format="date", description="Data do movimento", example="2024-01-01"),
 *     @OA\Property(property="user_id", type="integer", description="ID do usuário responsável pelo movimento", example=5)
 * )
 */
class StockMovement extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new StockGetScope);
    }

    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'unit_price',
        'description',
        'date',
        'user_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
