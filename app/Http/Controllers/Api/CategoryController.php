<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Categorias",
 *     description="Endpoints relacionados à gestão de categorias."
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categorias",
     *     tags={"Categorias"},
     *     summary="Lista todas as categorias",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de categorias retornada com sucesso",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Category"))
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::all();
        return $this->response('Ok', 200, [$categories]);
    }

    /**
     * @OA\Post(
     *     path="/api/categorias",
     *     tags={"Categorias"},
     *     summary="Cria uma nova categoria",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Nome da categoria", example="Categoria 1"),
     *             @OA\Property(property="description", type="string", description="Descrição da categoria", example="Descrição breve.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoria criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Ação não autorizada"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('userDeny', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
        ]);

        $category = Category::create($validated);

        return $this->response('Created', 201, [$category]);
    }
    /**
     * @OA\Get(
     *     path="/api/categorias/{id}",
     *     tags={"Categorias"},
     *     summary="Busca uma categoria pelo ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da categoria",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoria não encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->response('Not Found', 404, [$category]);
        }

        return $this->response('Created', 201, [$category]);
    }

    /**
     * @OA\Put(
     *     path="/api/categorias/{id}",
     *     tags={"Categorias"},
     *     summary="Atualiza uma categoria existente",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da categoria",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", description="Nome da categoria", example="Categoria Atualizada"),
     *             @OA\Property(property="description", type="string", description="Descrição atualizada", example="Descrição atualizada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoria não encontrada"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Ação não autorizada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $this->authorize('userDeny', User::class);

        $category = Category::find($id);

        if (!$category) {
            return $this->response('Not Found', 404, [$category]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($validated);

        return $this->response('Created', 201, [$category]);
    }

    /**
     * @OA\Delete(
     *     path="/api/categorias/{id}",
     *     tags={"Categorias"},
     *     summary="Exclui uma categoria",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da categoria",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Categoria excluída com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoria não encontrada"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Ação não autorizada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->authorize('userDeny', User::class);

        $category = Category::find($id);

        if (!$category) {
            return $this->response('Not Found', 404, [$category]);
        }

        $category->delete();

        return $this->response('No Content', 204, [$category]);
    }
}
