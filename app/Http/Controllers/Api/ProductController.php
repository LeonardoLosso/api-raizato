<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->has('fornecedor_id')) {
            $query->where('fornecedor_id', $request->fornecedor_id);
        }
        if ($request->has('low_stock')) {
            $query->where('stock', '<', 'min_stock');
        }
        $products = $query->get();

        return $this->response('Ok', 200, [$products]);
    }

    public function store(Request $request)
    {
        $this->authorize('restringeUser', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|unique:products,code',
            'description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'min_stock' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        $product = Product::create($validated);

        return $this->response('Created', 201, [$product]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'fornecedor'])->find($id);

        if (!$product) {
            return $this->error('Not Found', 404);
        }

        return $this->response('Ok', 200, [$product]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('restringeUser', User::class);

        $product = Product::find($id);

        if (!$product) {
            return $this->error('Not Found', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|unique:products,code,' . $id,
            'description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'fornecedor_id' => 'required|exists:fornecedores,id',
            'cost_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'min_stock' => 'required|integer',
            'expiry_date' => 'required|date',
        ]);

        $product->update($validated);

        return $this->response('Ok', 200, [$product]);
    }

    public function destroy($id)
    {
        $this->authorize('restringeUser', User::class);

        $product = Product::find($id);

        if (!$product) {
            return $this->error('Not Found', 404);
        }

        $product->delete();

        return $this->response('No Content', 204, [$product]);
    }
}
