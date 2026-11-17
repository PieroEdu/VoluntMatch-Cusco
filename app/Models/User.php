<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos asignables masivamente en MySQL para Voluntarios y ONGs.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_usuario',
        'dni',
        'telefono',
        'carrera',
        'disponibilidad',
        'ruc',
        'distrito',
        'mision',
    ];

    /**
     * Atributos ocultos en serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}