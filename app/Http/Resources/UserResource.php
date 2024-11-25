<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     type="object",
 *     required={"firstName", "lastName", "fullName", "email"},
 *     @OA\Property(property="id", type="integer", description="ID do usuário"),
 *     @OA\Property(property="firstName", type="string", description="Primeiro nome do usuário"),
 *     @OA\Property(property="lastName", type="string", description="Último nome do usuário"),
 *     @OA\Property(property="fullName", type="string", description="Nome completo do usuário"),
 *     @OA\Property(property="phone", type="string", description="Telefone do usuário", nullable=true),
 *     @OA\Property(property="email", type="string", format="email", description="Email do usuário"),
 *     @OA\Property(property="role", type="string", enum={"user", "manager", "admin"}, description="Papel do usuário")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'fullName' => $this->firstName . ' ' . $this->lastName,
            'phone' => $this->phone,
            'email' => $this->email, 
            'role' => $this->role
        ];
    }
}
