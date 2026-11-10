<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    use HasFactory;

    protected $table = 'organizacions';

    protected $fillable = [
        'nombre',
        'ruc',
        'distrito',
        'direccion',
        'telefono',
        'correo',
        'tipo',
        'verificada',
    ];

    protected $casts = [
        'verificada' => 'boolean',
    ];

    public function oportunidades()
    {
        return $this->hasMany(Oportunidad::class);
    }
}
