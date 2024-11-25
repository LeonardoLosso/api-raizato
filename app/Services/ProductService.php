<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Fornecedor;
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

            $query->whereRaw('stock <= min_stock');;

            $query->orderBy('stock', 'DESC');
        } else {
            $query->orderBy('expiry_date', 'DESC');
        }


        return $query->get();
    }

    public function getHistoryByProduct($productId)
    {
        return StockMovement::with(['product', 'product.category'])
            ->where('product_id', $productId)
            ->get();
    }

    public function createStockMovement(array $data)
    {
        DB::beginTransaction();

        try {
            $movement = StockMovement::create($data);

            $product = Product::findOrFail($data['product_id']);
            if ($data['movement_type'] == 'compras' || $data['movement_type'] == 'devolucoes') {
                $product->stock += $data['quantity'];
            } else{
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

    public function getCategory(array $filters)
    {
        $query = Category::query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
        return $query->get();
    }

    public function getFornecedores(array $filters)
    {
        $query = Fornecedor::query();

        if (isset($filters['search'])) {
            $query->where('nome', 'like', '%' . $filters['search'] . '%');
        }
        return $query->get();
    }
}
