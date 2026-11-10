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
        'latitud',
        'longitud',
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
        'latitud' => 'float',
        'longitud' => 'float',
    ];

    /**
     * Retorna coordenadas resueltas o las coordenadas de fallback para el distrito en Cusco.
     */
    public function getCoordenadasValidasAttribute(): array
    {
        if (!is_null($this->latitud) && !is_null($this->longitud)) {
            return [
                'lat' => (float) $this->latitud,
                'lng' => (float) $this->longitud,
            ];
        }

        $fallbacks = [
            'wanchaq' => ['lat' => -13.526315, 'lng' => -71.956621],
            'san sebastián' => ['lat' => -13.530669, 'lng' => -71.928731],
            'san sebastian' => ['lat' => -13.530669, 'lng' => -71.928731],
            'san jerónimo' => ['lat' => -13.545914, 'lng' => -71.890327],
            'san jeronimo' => ['lat' => -13.545914, 'lng' => -71.890327],
            'santiago' => ['lat' => -13.528489, 'lng' => -71.986877],
            'saylla' => ['lat' => -13.565882, 'lng' => -71.828629],
            'poroy' => ['lat' => -13.497523, 'lng' => -72.039641],
            'ccorca' => ['lat' => -13.583333, 'lng' => -72.033333],
            'cusco' => ['lat' => -13.517088, 'lng' => -71.978535],
        ];

        $distritoKey = strtolower(trim((string) $this->distrito));
        return $fallbacks[$distritoKey] ?? ['lat' => -13.517088, 'lng' => -71.978535];
    }

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class);
    }

    public function postulaciones()
    {
        return $this->hasMany(Postulacion::class);
    }
}
