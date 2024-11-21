<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Produtos",
 *     description="Endpoints relacionados à gestão de produtos."
 * )
 */
class ProductController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Get(
     *     path="/api/produtos",
     *     summary="Listar produtos",
     *     description="Retorna uma lista de produtos com filtros opcionais.",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Texto para buscar no nome ou descrição do produto",
     *         @OA\Schema(type="string", example="Produto")
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filtrar produtos por ID da categoria",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="fornecedor_id",
     *         in="query",
     *         description="Filtrar produtos por ID do fornecedor",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="low_stock",
     *         in="query",
     *         description="Filtrar produtos com estoque abaixo do mínimo",
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de produtos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/produtos",
     *     summary="Criar produto",
     *     description="Cria um novo produto.",
     *     tags={"Produtos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 additionalProperties={
     *                     @OA\Property(type="array", @OA\Items(type="string", example="O campo name é obrigatório."))
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('userDeny', User::class);

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

    /**
     * @OA\Get(
     *     path="/api/produtos/{id}",
     *     summary="Exibir produto",
     *     description="Retorna os detalhes de um produto específico.",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto encontrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::with(['category', 'fornecedor'])->find($id);

        if (!$product) {
            return $this->error('Not Found', 404);
        }

        return $this->response('Ok', 200, [$product]);
    }

    /**
     * @OA\Put(
     *     path="/api/produtos/{id}",
     *     summary="Atualizar produto",
     *     description="Atualiza os dados de um produto existente.",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $this->authorize('userDeny', User::class);

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

    /**
     * @OA\Delete(
     *     path="/api/produtos/{id}",
     *     summary="Deletar produto",
     *     description="Remove um produto pelo ID.",
     *     tags={"Produtos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Produto deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produto não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->authorize('userDeny', User::class);

        $product = Product::find($id);

        if (!$product) {
            return $this->error('Not Found', 404);
        }

        $product->delete();

        return $this->response('No Content', 204, [$product]);
    }
}
