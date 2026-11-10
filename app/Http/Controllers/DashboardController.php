<?php

namespace App\Http\Controllers;

use App\Models\Oportunidad;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Panel del Voluntario (Estudiante).
     */
    public function voluntario()
    {
        $user = Auth::user();

        // Obtener postulaciones del usuario o por su correo
        $postulaciones = Postulacion::with('oportunidad')
            ->where(function ($query) use ($user) {
                if ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('correo', $user->email);
                }
            })
            ->orderBy('id', 'desc')
            ->get();

        // Métricas
        $horasAcumuladas = $postulaciones->where('estado', 'Aceptado')->sum('horas_acumuladas');
        $postulacionesActivas = $postulaciones->whereIn('estado', ['Pendiente', 'Aceptado'])->count();
        $causasCusco = Oportunidad::distinct('distrito')->count('distrito');
        $certificados = $postulaciones->where('estado', 'Aceptado')->count();

        // Recomendaciones
        $recomendaciones = Oportunidad::where('categoria', 'Sistemas')
            ->orWhere('destacada', true)
            ->take(2)
            ->get();

        return view('dashboard/voluntario', compact(
            'postulaciones',
            'horasAcumuladas',
            'postulacionesActivas',
            'causasCusco',
            'certificados',
            'recomendaciones'
        ));
    }

    /**
     * Panel de la Organización / ONG.
     */
    public function organizacion()
    {
        // Convocatorias
        $oportunidades = Oportunidad::withCount('postulaciones')
            ->orderBy('id', 'desc')
            ->get();

        // Postulantes recibidos para evaluación
        $postulaciones = Postulacion::with('oportunidad')
            ->orderBy('id', 'desc')
            ->get();

        // Métricas
        $convocatoriasActivas = $oportunidades->where('cupos_restantes', '>', 0)->count();
        $postulantesRecibidos = $postulaciones->count();
        $matchesConfirmados = $postulaciones->where('estado', 'Aceptado')->count();
        $totalCupos = $oportunidades->sum('cupos');
        $tasaCobertura = $totalCupos > 0 ? round(($matchesConfirmados / $totalCupos) * 100) : 0;

        return view('dashboard/organizacion', compact(
            'oportunidades',
            'postulaciones',
            'convocatoriasActivas',
            'postulantesRecibidos',
            'matchesConfirmados',
            'tasaCobertura'
        ));
    }
}
