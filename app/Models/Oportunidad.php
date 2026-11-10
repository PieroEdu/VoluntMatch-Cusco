<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oportunidad extends Model
{
    use HasFactory;

    protected $table = 'oportunidads';

    protected $fillable = [
        'organizacion_id',
        'titulo',
        'ong',
        'distrito',
        'categoria',
        'clase_insignia',
        'modalidad',
        'horario',
        'cupos',
        'cupos_restantes',
        'imagen',
        'descripcion',
        'habilidades',
        'destacada',
    ];

    protected $casts = [
        'habilidades' => 'array',
        'destacada' => 'boolean',
        'cupos' => 'integer',
        'cupos_restantes' => 'integer',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }
}
