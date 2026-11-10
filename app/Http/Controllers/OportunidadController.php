<?php

namespace App\Http\Controllers;

use App\Models\Oportunidad;
use App\Models\Organizacion;
use App\Services\GeocodingService;
use Illuminate\Http\Request;

class OportunidadController extends Controller
{
    protected GeocodingService $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }
    /**
     * Muestra el catálogo de oportunidades.
     */
    public function index(Request $request)
    {
        $query = Oportunidad::query();

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        if ($request->filled('distrito') && $request->distrito !== 'todos') {
            $query->where('distrito', $request->distrito);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                  ->orWhere('ong', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        $oportunidades = $query->orderBy('destacada', 'desc')->orderBy('id', 'asc')->get();

        return view('oportunidades', compact('oportunidades'));
    }

    /**
     * Muestra el detalle de una convocatoria específica.
     */
    public function show(Request $request)
    {
        $id = $request->query('id', 1);

        $oportunidad = Oportunidad::with('organizacion')->find($id);

        if (!$oportunidad) {
            $oportunidad = Oportunidad::first();
        }

        return view('detalle-oportunidad', compact('oportunidad'));
    }

    /**
     * Crea una nueva convocatoria (desde el Panel de Organización).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'ong' => 'required|string|max:255',
            'distrito' => 'required|string|max:100',
            'categoria' => 'required|string|max:100',
            'modalidad' => 'required|string|max:50',
            'horario' => 'required|string|max:255',
            'cupos' => 'required|integer|min:1|max:100',
            'descripcion' => 'required|string',
            'habilidades' => 'nullable|string',
            'imagen' => 'nullable|url',
        ]);

        $habilidadesArray = [];
        if (!empty($validated['habilidades'])) {
            $habilidadesArray = array_map('trim', explode(',', $validated['habilidades']));
        } else {
            $habilidadesArray = ['Trabajo en equipo', 'Compromiso social'];
        }

        $badgeClase = match (strtolower($validated['categoria'])) {
            'educación', 'educacion' => 'badge-educacion',
            'sistemas', 'tecnología', 'tecnologia' => 'badge-sistemas',
            'medio ambiente', 'ambiental' => 'badge-ambiente',
            'logística', 'logistica' => 'badge-logistica',
            default => 'badge-educacion',
        };

        $imagen = $validated['imagen'] ?? 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=600&q=80';

        // Geocodificar ubicación con la API de Nominatim / Fallback distrital
        $coords = $this->geocodingService->geocode($validated['distrito']);

        $organizacion = Organizacion::where('nombre', $validated['ong'])->first();

        $oportunidad = Oportunidad::create([
            'organizacion_id' => $organizacion?->id,
            'titulo' => $validated['titulo'],
            'ong' => $validated['ong'],
            'distrito' => $validated['distrito'],
            'latitud' => $coords['lat'],
            'longitud' => $coords['lng'],
            'categoria' => $validated['categoria'],
            'clase_insignia' => $badgeClase,
            'modalidad' => $validated['modalidad'],
            'horario' => $validated['horario'],
            'cupos' => $validated['cupos'],
            'cupos_restantes' => $validated['cupos'],
            'descripcion' => $validated['descripcion'],
            'habilidades' => $habilidadesArray,
            'imagen' => $imagen,
            'destacada' => true,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Convocatoria creada exitosamente!',
                'oportunidad' => $oportunidad,
            ]);
        }

        return redirect()->route('dashboard-organizacion')->with('success', '¡Convocatoria publicada con éxito!');
    }

    /**
     * API JSON para el catálogo reactivo.
     */
    public function apiListar()
    {
        $oportunidades = Oportunidad::all();
        return response()->json($oportunidades);
    }

    /**
     * API JSON para alimentar el mapa interactivo de Leaflet.
     * Retorna todas las oportunidades activas con sus coordenadas resueltas.
     */
    public function apiMapa()
    {
        $oportunidades = Oportunidad::all()->map(function ($op) {
            $coords = $op->coordenadas_validas;

            return [
                'id'              => $op->id,
                'titulo'          => $op->titulo,
                'ong'             => $op->ong,
                'distrito'        => $op->distrito,
                'categoria'       => $op->categoria,
                'clase_insignia'  => $op->clase_insignia,
                'modalidad'       => $op->modalidad,
                'horario'         => $op->horario,
                'cupos_restantes' => $op->cupos_restantes,
                'imagen'          => $op->imagen,
                'lat'             => $coords['lat'],
                'lng'             => $coords['lng'],
                'url_detalle'     => route('detalle-oportunidad', ['id' => $op->id]),
            ];
        });

        return response()->json([
            'success'       => true,
            'total'         => $oportunidades->count(),
            'centro_cusco'  => ['lat' => -13.517088, 'lng' => -71.978535],
            'oportunidades' => $oportunidades,
        ]);
    }
}
