<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="StockMovementResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="ID do movimento de estoque", example=1),
 *     @OA\Property(property="productId", type="integer", description="ID do produto relacionado ao movimento", example=1),
 *     @OA\Property(property="productName", type="string", description="Nome do produto relacionado ao movimento", example="Produto XYZ"),
 *     @OA\Property(property="categoryName", type="string", description="Nome da categoria do produto", example="Categoria ABC"),
 *     @OA\Property(property="movementType", type="string", description="Tipo de movimento (entrada ou saída)", example="entrada"),
 *     @OA\Property(property="quantity", type="number", format="float", description="Quantidade do movimento", example=10),
 *     @OA\Property(property="unitPrice", type="number", format="float", description="Preço unitário do produto no movimento", example=15.50),
 *     @OA\Property(property="totalPrice", type="number", format="float", description="Preço total calculado (unitPrice * quantity)", example=155.00),
 *     @OA\Property(property="description", type="string", description="Descrição ou observação sobre o movimento", example="Compra de mercadoria"),
 *     @OA\Property(property="date", type="string", format="date", description="Data do movimento", example="2024-01-01")
 * )
 */

class StockMovementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'productId' => $this->product_id,
            'productName' => $this->product->name ?? null,
            'categoryName' => $this->product->category->name ?? null,
            'movementType' => $this->movement_type,
            'quantity' => (float)$this->quantity,
            'unitPrice' => (float)$this->unit_price,
            'totalPrice' => (float)$this->unit_price * $this->quantity,
            'description' => $this->description,
            'date' => $this->date,
        ];
    }
}
