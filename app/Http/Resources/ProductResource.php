<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ProductResource",
 *     type="object",
 *     required={"name", "code", "category_id", "fornecedor_id", "cost_price", "sale_price", "min_stock", "stock", "expiry_date"},
 *     @OA\Property(property="id", type="integer", description="ID do produto"),
 *     @OA\Property(property="name", type="string", description="Nome do produto"),
 *     @OA\Property(property="code", type="string", description="Código do produto"),
 *     @OA\Property(property="description", type="string", description="Descrição do produto"),
 *     @OA\Property(property="categoryId", type="integer", description="ID da categoria", example=2),
 *     @OA\Property(property="categoryName", type="string", description="Nome da categoria", example="Automoveis"),
 *     @OA\Property(property="fornecedorId", type="integer", description="ID do fornecedor", example=3),
 *     @OA\Property(property="fornecedorName", type="string", description="Nome do fornecedor", example="Simas Turbo"),
 *     @OA\Property(property="costPrice", type="number", format="float", description="Preço de custo", example=100.50),
 *     @OA\Property(property="salePrice", type="number", format="float", description="Preço de venda", example=150.00),
 *     @OA\Property(property="expiryDate", type="string", format="date", description="Data de validade", example="2024-12-31"),
 * )
 */
class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'categoryId' => $this->category_id,
            'categoryName' => $this->category->name,
            'fornecedorId' => $this->fornecedor_id,
            'fornecedorName' => $this->fornecedor->nome,
            'costPrice' => (float) $this->cost_price,
            'salePrice' => (float) $this->sale_price,
            'minStock' => (float) $this->min_stock,
            'stock' => (float) $this->stock,
            'expiryDate' => $this->expiry_date,
        ];
    }
}
