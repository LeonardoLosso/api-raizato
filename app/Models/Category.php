<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="id", type="integer", description="ID da categoria"),
 *     @OA\Property(property="name", type="string", description="Nome da categoria", example="Tecnologia"),
 *     @OA\Property(property="description", type="string", description="Descrição da categoria", example="Categoria voltada para produtos de tecnologia."),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação da categoria", example="2024-11-21T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data da última atualização da categoria", example="2024-11-21T11:00:00Z")
 * )
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
}
