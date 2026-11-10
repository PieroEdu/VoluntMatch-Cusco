<?php

namespace App\Http\Controllers;

use App\Models\Oportunidad;
use App\Models\Postulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostulacionController extends Controller
{
    /**
     * Registra una nueva postulación a una oportunidad.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'oportunidad_id' => 'required|exists:oportunidads,id',
            'estudiante' => 'required|string|min:3|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'carrera' => 'nullable|string|max:255',
            'universidad' => 'nullable|string|max:255',
            'mensaje_motivacion' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $oportunidadId = $validated['oportunidad_id'];

        // Verificar si ya existe postulación para este correo y oportunidad
        $queryExistente = Postulacion::where('oportunidad_id', $oportunidadId)
            ->where(function ($q) use ($userId, $validated) {
                if ($userId) {
                    $q->where('user_id', $userId)
                      ->orWhere('correo', $validated['correo']);
                } else {
                    $q->where('correo', $validated['correo']);
                }
            });

        if ($queryExistente->exists()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya te has postulado previamente a esta convocatoria.',
                ], 422);
            }
            return back()->with('error', 'Ya te has postulado previamente a esta convocatoria.');
        }

        $oportunidad = Oportunidad::findOrFail($oportunidadId);

        // Crear la postulación
        $postulacion = new Postulacion();
        $postulacion->oportunidad_id = $oportunidad->id;
        $postulacion->user_id = $userId;
        $postulacion->estudiante = $validated['estudiante'];
        $postulacion->correo = $validated['correo'];
        $postulacion->telefono = $validated['telefono'] ?? null;
        $postulacion->carrera = $validated['carrera'] ?? 'Estudiante Universitario';
        $postulacion->universidad = $validated['universidad'] ?? 'Universidad Continental (Sede Cusco)';
        $postulacion->mensaje_motivacion = $validated['mensaje_motivacion'] ?? null;
        $postulacion->estado = 'Pendiente';
        $postulacion->horas_acumuladas = 0;
        $postulacion->fecha = now()->toDateString();
        $postulacion->save();

        // Reducir cupos disponibles si es mayor a cero
        if ($oportunidad->cupos_restantes > 0) {
            $oportunidad->decrement('cupos_restantes');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Tu postulación a "' . $oportunidad->titulo . '" ha sido registrada exitosamente!',
                'postulacion' => $postulacion->load('oportunidad'),
            ]);
        }

        return redirect()->route('home')->with('success', '¡Postulación enviada con éxito! La organización revisará tu perfil.');
    }

    /**
     * Permite al voluntario cancelar una postulación pendiente.
     */
    public function cancelar($id)
    {
        $postulacion = Postulacion::findOrFail($id);

        // Si está autenticado, verificar propiedad
        if (Auth::check() && $postulacion->user_id && $postulacion->user_id !== Auth::id()) {
            return back()->with('error', 'No tienes autorización para cancelar esta postulación.');
        }

        if ($postulacion->estado === 'Pendiente') {
            // Reintegrar cupo
            if ($postulacion->oportunidad && $postulacion->oportunidad->cupos_restantes < $postulacion->oportunidad->cupos) {
                $postulacion->oportunidad->increment('cupos_restantes');
            }
            $postulacion->delete();
            return back()->with('success', 'Postulación cancelada correctamente.');
        }

        return back()->with('error', 'Solo puedes cancelar postulaciones en estado Pendiente.');
    }

    /**
     * Cambia el estado de una postulación (para la ONG: Aceptar / Rechazar).
     */
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Aceptado,Rechazado',
            'horas' => 'nullable|integer|min:0',
        ]);

        $postulacion = Postulacion::findOrFail($id);
        $estadoAnterior = $postulacion->estado;
        $postulacion->estado = $request->estado;

        if ($request->estado === 'Aceptado') {
            $postulacion->horas_acumuladas = $request->input('horas', 8);
        } elseif ($request->estado === 'Rechazado' && $estadoAnterior !== 'Rechazado') {
            // Reintegrar cupo si fue rechazada
            if ($postulacion->oportunidad && $postulacion->oportunidad->cupos_restantes < $postulacion->oportunidad->cupos) {
                $postulacion->oportunidad->increment('cupos_restantes');
            }
        }

        $postulacion->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'El estado de la postulación ha sido actualizado a ' . $postulacion->estado . '.',
                'postulacion' => $postulacion,
            ]);
        }

        return back()->with('success', 'Estado de la postulación actualizado a ' . $postulacion->estado . '.');
    }

    /**
     * API JSON de postulaciones para sincronización con frontend.
     */
    public function apiListar()
    {
        $postulaciones = Postulacion::with('oportunidad')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($postulaciones);
    }
}
