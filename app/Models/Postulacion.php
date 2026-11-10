<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulacion extends Model
{
    use HasFactory;

    protected $table = 'postulacions';

    protected $fillable = [
        'oportunidad_id',
        'user_id',
        'estudiante',
        'correo',
        'telefono',
        'carrera',
        'universidad',
        'mensaje_motivacion',
        'estado',
        'horas_acumuladas',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
        'horas_acumuladas' => 'integer',
    ];

    public function oportunidad()
    {
        return $this->belongsTo(Oportunidad::class, 'oportunidad_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
