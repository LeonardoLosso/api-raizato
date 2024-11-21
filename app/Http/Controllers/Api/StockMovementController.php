<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\ProductService;
use App\Traits\HttpResponses;

/**
 * @OA\Tag(
 *     name="Movimentações",
 *     description="Endpoints relacionados à gestão de movimentações de estoque."
 * )
 */
class StockMovementController extends Controller
{
    use HttpResponses;

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/api/estoque",
     *     summary="Listar todos os movimentos de estoque",
     *     operationId="indexStockMovements",
     *     tags={"Movimentações"},
     *     security={{"apiAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de movimentos de estoque",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/StockMovement"))
     *     ),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=403, description="Proibido"),
     *     @OA\Response(response=404, description="Não encontrado")
     * )
     */

    public function index()
    {
        $this->authorize('userDeny', User::class);

        $movements = StockMovement::with('product')->get();

        return $this->response('Ok', 200, [$movements]);
    }

    /**
     * @OA\Post(
     *     path="/api/estoque",
     *     summary="Criar um novo movimento de estoque",
     *     operationId="storeStockMovement",
     *     tags={"Movimentações"},
     *     security={{"apiAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StockMovement")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movimento de estoque criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/StockMovement")
     *     ),
     *     @OA\Response(response=400, description="Erro na validação dos dados"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=403, description="Proibido")
     * )
     */
    public function store(StockMovementRequest $request)
    {
        $validatedData = $request->validate();
        $movement = $this->productService->createStockMovement($validatedData);
        return $this->response('Created', 201, [$movement]);
    }

    /**
     * @OA\Get(
     *     path="/api/estoque/historico/{productId}",
     *     summary="Histórico de movimentações de estoque por produto",
     *     operationId="historyByProduct",
     *     tags={"Movimentações"},
     *     security={{"apiAuth": {}}},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Histórico de movimentos de estoque do produto",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/StockMovement"))
     *     ),
     *     @OA\Response(response=404, description="Produto não encontrado ou sem histórico"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=403, description="Proibido")
     * )
     */
    public function historyByProduct($productId)
    {
        $this->authorize('userDeny', User::class);

        $movements = $this->productService->getHistoryByProduct($productId);

        if ($movements->isEmpty()) {
            return $this->error('Not Found', 404);
        }

        return $this->response('Ok', 200, [$movements]);
    }
}
