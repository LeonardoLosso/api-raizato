<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"firstName", "lastName", "email", "role"},
 *     @OA\Property(property="id", type="integer", description="ID do usuário"),
 *     @OA\Property(property="firstName", type="string", description="Primeiro nome do usuário"),
 *     @OA\Property(property="lastName", type="string", description="Último nome do usuário"),
 *     @OA\Property(property="email", type="string", format="email", description="Email do usuário"),
 *     @OA\Property(property="phone", type="string", description="Telefone do usuário"),
 *     @OA\Property(property="role", type="string", enum={"user", "manager", "admin"}, description="Papel do usuário"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;



    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'phone',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
