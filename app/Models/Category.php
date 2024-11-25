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
 *     @OA\Property(property="description", type="string", description="Descrição da categoria", example="Categoria voltada para produtos de tecnologia.")
 * )
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];
}
