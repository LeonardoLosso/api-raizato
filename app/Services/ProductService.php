<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getProducts(array $filters)
    {
        $query = Product::query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhere('description', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['fornecedor_id'])) {
            $query->where('fornecedor_id', $filters['fornecedor_id']);
        }

        if (isset($filters['low_stock'])) {
            $query->where('stock', '<', 'min_stock');
        }

        return $query->get();
    }

    public function getHistoryByProduct($productId)
    {
        return StockMovement::where('product_id', $productId)
            ->with('product')
            ->get();
    }

    public function createStockMovement(array $data)
    {
        DB::beginTransaction();

        try {
            $movement = StockMovement::create($data);

            $product = Product::findOrFail($data['product_id']);

            if ($data['movement_type'] == 'entry') {
                $product->stock += $data['quantity'];
            } elseif ($data['movement_type'] == 'exit') {
                $product->stock -= $data['quantity'];
            }

            $product->save();

            DB::commit();

            return $movement;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
