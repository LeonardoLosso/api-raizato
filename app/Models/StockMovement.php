<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="StockMovement",
 *     type="object",
 *     required={"product_id", "movement_type", "quantity", "unit_price"},
 *     @OA\Property(property="id", type="integer", description="ID do movimento de estoque"),
 *     @OA\Property(property="product_id", type="integer", description="ID do produto"),
 *     @OA\Property(property="movement_type", type="string", enum={"entry", "exit"}, description="Tipo de movimento ('entrada' ou 'saída')"),
 *     @OA\Property(property="quantity", type="integer", description="Quantidade movimentada"),
 *     @OA\Property(property="unit_price", type="number", format="float", description="Preço unitário do produto no movimento"),
 *     @OA\Property(property="description", type="string", description="Descrição adicional sobre o movimento"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação do movimento"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização do movimento")
 * )
 */
class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'unit_price',
        'description'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
