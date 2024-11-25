<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
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

    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

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
     *             @OA\Items(ref="#/components/schemas/ProductResource")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $products = $this->productService->getProducts($request->all());

        return $this->response('Ok', 200, ProductResource::collection($products)->toArray(request()));
    }


    /**
     * @OA\Post(
     *     path="/api/produtos",
     *     summary="Criar produto",
     *     description="Cria um novo produto.",
     *     tags={"Produtos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produto criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
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
    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();

        $created = Product::create($validatedData);

        return $this->response('Produto criado com sucesso!', 201, $created);
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
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
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
    public function show(string $id)
    {
        $product = Product::find($id);
        return $this->response('Ok', 200, new ProductResource($product));
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
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produto atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ProductResource")
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
    public function update(ProductRequest $request, string $id)
    {
        $validatedData = $request->validated();

        $updated = Product::find($id)->update($validatedData);

        return $this->response('Produto atualizado com sucesso', 200, $updated);
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
    public function destroy(string $id)
    {
        $this->authorize('userDeny', User::class);

        $model = Product::findOrFail($id);
        $model->delete();

        return $this->response('Excluido com Sucesso!', 204);
    }
}
