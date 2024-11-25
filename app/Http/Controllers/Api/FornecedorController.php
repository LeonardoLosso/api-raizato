<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FornecedoresRequest;
use App\Models\Fornecedor;
use App\Models\User;
use App\Services\ProductService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Fornecedores",
 *     description="Endpoints relacionados à gestão de fornecedores."
 * )
 */

class FornecedorController extends Controller
{
    use HttpResponses;
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * @OA\Get(
     *     path="/api/fornecedores",
     *     summary="Listar fornecedores",
     *     description="Retorna uma lista de todos os fornecedores.",
     *     tags={"Fornecedores"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de fornecedores retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Fornecedor")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $fornecedores = $this->productService->getFornecedores($request->all());

        return $this->response('Ok', 200, $fornecedores);
    }

    /**
     * @OA\Get(
     *     path="/api/fornecedores/{id}",
     *     summary="Exibir fornecedor",
     *     description="Retorna os dados de um fornecedor específico.",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do fornecedor",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor encontrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $fornecedor = Fornecedor::where('id', $id)->first();
        return $this->response('Ok', 200, $fornecedor);
    }

    /**
     * @OA\Post(
     *     path="/api/fornecedores",
     *     summary="Criar fornecedor",
     *     description="Cria um novo fornecedor.",
     *     tags={"Fornecedores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"nome", "cnpj"},
     *             @OA\Property(property="nome", type="string", description="Nome do fornecedor", example="Fornecedor ABC"),
     *             @OA\Property(property="cnpj", type="string", description="CNPJ do fornecedor (apenas números)", example="12345678000199"),
     *             @OA\Property(property="contato", type="string", description="Contato do fornecedor", example="contato@fornecedor.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Fornecedor criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
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
     *                     @OA\Property(type="array", @OA\Items(type="string", example="O campo nome é obrigatório."))
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function store(FornecedoresRequest $request)
    {
        $validatedData = $request->validated();

        $created = Fornecedor::create($validatedData);
        if (!$created) {
            return $this->error('Erro ao criar fornecedor', 400);
        }

        return $this->response('Created', 201, $created);
    }

    /**
     * @OA\Put(
     *     path="/api/fornecedores/{id}",
     *     summary="Atualizar fornecedor",
     *     description="Atualiza os dados de um fornecedor existente.",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do fornecedor",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"nome", "cnpj"},
     *             @OA\Property(property="nome", type="string", description="Nome do fornecedor", example="Fornecedor XYZ"),
     *             @OA\Property(property="cnpj", type="string", description="CNPJ do fornecedor (apenas números)", example="98765432000101"),
     *             @OA\Property(property="contato", type="string", description="Contato do fornecedor", example="suporte@fornecedor.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Fornecedor atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Fornecedor")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function update(FornecedoresRequest $request, string $id)
    {
        $validatedData  = $request->validated();

        $updated = Fornecedor::find($id)->update($validatedData);

        return $this->response('Fornecedor atualizado com sucesso', 200, $updated);
    }

    /**
     * @OA\Delete(
     *     path="/api/fornecedores/{id}",
     *     summary="Deletar fornecedor",
     *     description="Remove um fornecedor pelo ID.",
     *     tags={"Fornecedores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do fornecedor",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Fornecedor deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Fornecedor não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Not Found")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $this->authorize('userDeny', User::class);

        $model = Fornecedor::findOrFail($id);
        $model->delete();

        return $this->response('Excluido com Sucesso!', 204);
    }
}
