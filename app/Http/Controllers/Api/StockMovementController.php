<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovementRequest;
use App\Http\Resources\StockMovementResource;
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
        $movements = StockMovement::with(['product', 'product.category'])->get();

        return $this->response('Ok', 200, StockMovementResource::collection($movements));
    }

    /**
     * @OA\Get(
     *     path="/api/estoque/{id}",
     *     summary="Mostrar detalhes de um movimento de estoque",
     *     operationId="showStockMovement",
     *     tags={"Movimentações"},
     *     security={{"apiAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do movimento de estoque",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do movimento de estoque",
     *         @OA\JsonContent(ref="#/components/schemas/StockMovement")
     *     ),
     *     @OA\Response(response=404, description="Movimento não encontrado"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=403, description="Proibido")
     * )
     */
    public function show(string $id)
    {
        $this->authorize('userDeny', User::class);

        $movement = StockMovement::with(['product', 'product.category'])->find($id);
        if (!$movement) {
            return $this->response('Movimento não encontrado', 404);
        }

        return $this->response('Ok', 200, new StockMovementResource($movement));
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
        $validatedData = $request->validated();
        $movement = $this->productService->createStockMovement($validatedData);
        return $this->response('Created', 201, $movement);
    }

    /**
     * @OA\Put(
     *     path="/api/estoque/{id}",
     *     summary="Atualizar um movimento de estoque",
     *     operationId="updateStockMovement",
     *     tags={"Movimentações"},
     *     security={{"apiAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do movimento de estoque",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StockMovement")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Movimento de estoque atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/StockMovement")
     *     ),
     *     @OA\Response(response=400, description="Erro na validação dos dados"),
     *     @OA\Response(response=404, description="Movimento não encontrado"),
     *     @OA\Response(response=401, description="Não autorizado"),
     *     @OA\Response(response=403, description="Proibido")
     * )
     */
    public function update(StockMovementRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $updated = StockMovement::find($id)->update($validatedData);

        return $this->response('Movimento atualizado com sucesso', 200, $updated);
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
     *         description="ID do produto para buscar o histórico de movimentações",
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
    public function historyByProduct(string $productName)
    {
        $movements = $this->productService->getHistoryByProduct($productName);

        if ($movements->isEmpty()) {
            return $this->error('Not Found', 404);
        }

        return $this->response('Ok', 200, StockMovementResource::collection($movements)->toArray(request()));
    }
}
