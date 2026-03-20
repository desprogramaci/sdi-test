<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject; // Importante para JWT

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Añadido para la relación de roles
    ];

    /**
     * Atributos ocultos para la serialización (JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- MÉTODOS REQUERIDOS POR JWT ---

    /**
     * Obtener el identificador que se guardará en el claim del JWT.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retornar un array de claims personalizados para el JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role->name ?? 'user' // Opcional: meter el rol en el token
        ];
    }

    // --- RELACIONES ---

    /**
     * Relación con el modelo Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
