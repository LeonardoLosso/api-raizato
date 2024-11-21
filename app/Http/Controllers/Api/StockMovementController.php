<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $this->authorize('userDeny', User::class);

        $movements = StockMovement::with('product')->get();

        return $this->response('Ok', 200, [$movements]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:entry,exit',
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'description' => 'nullable|string|max:255',
        ]);

        $movement = StockMovement::create($validated);

        $product = Product::find($request->product_id);

        if ($request->movement_type == 'entry') {
            $product->stock += $request->quantity;
        } elseif ($request->movement_type == 'exit') {
            $product->stock -= $request->quantity;
        }

        $product->save();
        return $this->response('Created', 201, [$movement]);
    }

    public function historyByProduct($productId)
    {
        $this->authorize('userDeny', User::class);

        $movements = StockMovement::where('product_id', $productId)
            ->with('product')
            ->get();

        if ($movements->isEmpty()) {
            return $this->error('Not Found', 404);
        }

        return $this->response('Ok', 200, [$movements]);
    }
}
