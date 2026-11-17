<?php

namespace App\Http\Controllers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OportunidadController extends Controller
{
    private function asegurarTablasDonaciones()
    {
        if (Schema::hasTable('oportunidades')) {
            Schema::table('oportunidades', function (Blueprint $table) {
                if (!Schema::hasColumn('oportunidades', 'acepta_donaciones')) {
                    $table->boolean('acepta_donaciones')->default(false);
                }
                if (!Schema::hasColumn('oportunidades', 'tiene_meta_donacion')) {
                    $table->boolean('tiene_meta_donacion')->default(false);
                }
                if (!Schema::hasColumn('oportunidades', 'meta_donacion')) {
                    $table->decimal('meta_donacion', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('oportunidades', 'monto_recaudado')) {
                    $table->decimal('monto_recaudado', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('oportunidades', 'yape_numero')) {
                    $table->string('yape_numero', 30)->nullable();
                }
            });
        }

        if (!Schema::hasTable('donaciones')) {
            Schema::create('donaciones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('oportunidad_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('donante_nombre');
                $table->string('donante_email')->nullable();
                $table->string('donante_dni', 20)->nullable();
                $table->boolean('es_anonimo')->default(false);
                $table->string('metodo_pago', 50); // Yape / Plin / Tarjeta / Donación en Especie
                $table->string('numero_operacion', 60)->nullable();
                $table->decimal('monto', 10, 2)->default(0);
                $table->string('detalle_especie')->nullable();
                $table->string('codigo_comprobante', 40)->unique();
                $table->string('mensaje_apoyo')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('donaciones', function (Blueprint $table) {
                if (!Schema::hasColumn('donaciones', 'donante_dni')) {
                    $table->string('donante_dni', 20)->nullable();
                }
                if (!Schema::hasColumn('donaciones', 'numero_operacion')) {
                    $table->string('numero_operacion', 60)->nullable();
                }
            });
        }

        if (!Schema::hasTable('rendiciones_gasto')) {
            Schema::create('rendiciones_gasto', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('oportunidad_id');
                $table->unsignedBigInteger('ong_id');
                $table->string('concepto');
                $table->decimal('monto_gastado', 10, 2);
                $table->string('comprobante_ref')->nullable();
                $table->date('fecha_gasto');
                $table->timestamps();
            });
        }
    }

    private function formatearRangoFechas($inicio, $fin)
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $cInicio = $inicio ? Carbon::parse($inicio) : Carbon::now();
        $cFin = $fin ? Carbon::parse($fin) : Carbon::now()->addDays(45);

        $diaI = $cInicio->day;
        $mesI = $meses[$cInicio->month];
        $anioI = $cInicio->year;

        $diaF = $cFin->day;
        $mesF = $meses[$cFin->month];
        $anioF = $cFin->year;

        if ($anioI === $anioF) {
            return "Del {$diaI} de {$mesI} al {$diaF} de {$mesF} del {$anioF}";
        }
        return "Del {$diaI} de {$mesI} del {$anioI} al {$diaF} de {$mesF} del {$anioF}";
    }

    private function getOportunidadesBase()
    {
        return [
            [
                'id' => 1,
                'real_id' => null,
                'ong_id' => null,
                'titulo' => 'Reforestación e Impacto Ambiental en Valle Sagrado',
                'ong' => 'EcoAndina Cusco',
                'distrito' => 'San Jerónimo',
                'direccion_exacta' => 'Cuenca Alta de San Jerónimo, Cusco',
                'latitud' => '-13.54361',
                'longitud' => '-71.88583',
                'horario' => 'Sábados (08:00 AM - 12:00 PM)',
                'rango_fechas' => 'Del 14 de Noviembre al 31 de Diciembre del 2026',
                'fecha_cierre_iso' => Carbon::now()->addDays(18)->endOfDay()->toIso8601String(),
                'categoria' => 'Medio Ambiente',
                'horas' => 20,
                'cupos_totales' => 15,
                'cupos_disponibles' => 4,
                'estado' => 'Abierta',
                'acepta_donaciones' => false,
                'tiene_meta_donacion' => false,
                'meta_donacion' => 0,
                'monto_recaudado' => 0,
                'yape_numero' => null,
                'imagen' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1400&q=85',
                'descripcion' => "Este proyecto busca restaurar más de 15 hectáreas de bosques nativos en las cuencas altas del distrito de San Jerónimo en Cusco. Trabajamos activamente con especies locales como la Queñua (Polylepis) y el Chachacomo para preservar la recarga hídrica de los manantiales comunitarios.",
                'funciones' => [
                    'Participar en las jornadas sabatinas de plantación directa en terreno.',
                    'Apoyar en el traslado de plantones desde el vivero central en San Jerónimo.',
                    'Elaborar talleres de concientización ambiental dirigidos a colegios locales.'
                ],
                'requisitos' => [
                    'Ser mayor de 18 años o estudiante universitario activo',
                    'Disponibilidad los sábados por la mañana',
                    'Compromiso mínimo de 20 horas'
                ]
            ],
            [
                'id' => 2,
                'real_id' => null,
                'ong_id' => null,
                'titulo' => 'Soporte TI y Digitalización de Archivos Históricos',
                'ong' => 'Biblioteca Cultural Cusco',
                'distrito' => 'Cusco Centro',
                'direccion_exacta' => 'Calle Garcilaso s/n, Centro Histórico, Cusco',
                'latitud' => '-13.51708',
                'longitud' => '-71.97853',
                'horario' => '¡Es Flexible! Nos acomodamos a tu horario.',
                'rango_fechas' => 'Del 20 de Noviembre al 28 de Diciembre del 2026',
                'fecha_cierre_iso' => Carbon::now()->addDays(12)->endOfDay()->toIso8601String(),
                'categoria' => 'Tecnología',
                'horas' => 30,
                'cupos_totales' => 8,
                'cupos_disponibles' => 2,
                'estado' => 'Abierta',
                'acepta_donaciones' => false,
                'tiene_meta_donacion' => false,
                'meta_donacion' => 0,
                'monto_recaudado' => 0,
                'yape_numero' => null,
                'imagen' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1400&q=85',
                'descripcion' => 'Desarrollo de módulos web para catalogar documentos coloniales e implementación de respaldos de seguridad en la nube.',
                'funciones' => [
                    'Estructurar bases de datos para el catálogo histórico digital.',
                    'Escanear y clasificar documentos antiguos con estándares de conservación.'
                ],
                'requisitos' => [
                    'Estudiante técnico o universitario de Ingeniería de Sistemas o carreras afines',
                    'Compromiso mínimo de 30 horas'
                ]
            ],
            [
                'id' => 3,
                'real_id' => null,
                'ong_id' => null,
                'titulo' => 'Tutoría Académica y Refuerzo Escolar',
                'ong' => 'KUSI WSI Niñez',
                'distrito' => 'Santiago',
                'direccion_exacta' => 'Av. Antonio Lorena 412, Santiago, Cusco',
                'latitud' => '-13.52450',
                'longitud' => '-71.98410',
                'horario' => 'Martes y Jueves (02:30 PM - 05:30 PM)',
                'rango_fechas' => 'Del 18 de Noviembre al 22 de Diciembre del 2026',
                'fecha_cierre_iso' => Carbon::now()->addDays(9)->endOfDay()->toIso8601String(),
                'categoria' => 'Educación',
                'horas' => 15,
                'cupos_totales' => 20,
                'cupos_disponibles' => 12,
                'estado' => 'Abierta',
                'acepta_donaciones' => false,
                'tiene_meta_donacion' => false,
                'meta_donacion' => 0,
                'monto_recaudado' => 0,
                'yape_numero' => null,
                'imagen' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1400&q=85',
                'descripcion' => 'Acompañamiento personalizado a niños de educación primaria en competencias de lectoescritura y matemática.',
                'funciones' => [
                    'Guiar sesiones dinámicas de comprensión lectora y matemáticas.',
                    'Preparar material didáctico lúdico.'
                ],
                'requisitos' => [
                    'Estudiante universitario activo',
                    'Paciencia y vocación pedagógica'
                ]
            ]
        ];
    }

    private function getOportunidadesCreadas()
    {
        $this->asegurarTablasDonaciones();

        return DB::table('oportunidades')->orderBy('created_at', 'desc')->get()->map(function ($item) {
            $funcionesArr = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $item->funciones ?? ''))));
            $requisitosArr = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $item->requisitos ?? ''))));

            $cierre = $item->fecha_cierre
                ? Carbon::parse($item->fecha_cierre)
                : ($item->fecha_fin ? Carbon::parse($item->fecha_fin)->endOfDay() : Carbon::now()->addDays(15)->endOfDay());

            $expirada = Carbon::now()->greaterThan($cierre);
            $estadoFinal = ($item->cupos_disponibles <= 0 || $item->estado === 'Cerrada' || $expirada) ? 'Cerrada' : 'Abierta';

            return [
                'id' => $item->id + 1000,
                'real_id' => $item->id,
                'ong_id' => $item->ong_id,
                'titulo' => $item->titulo,
                'ong' => $item->ong_nombre,
                'distrito' => $item->distrito,
                'direccion_exacta' => $item->direccion_exacta ?: ($item->distrito . ', Cusco'),
                'latitud' => $item->latitud ?: '-13.53195',
                'longitud' => $item->longitud ?: '-71.96746',
                'horario' => $item->horario ?: '¡Es Flexible! Nos acomodamos a tu horario.',
                'rango_fechas' => $this->formatearRangoFechas($item->fecha_inicio, $item->fecha_fin),
                'fecha_cierre_iso' => $cierre->toIso8601String(),
                'categoria' => $item->categoria,
                'horas' => $item->horas,
                'cupos_totales' => $item->cupos,
                'cupos_disponibles' => max(0, (int)$item->cupos_disponibles),
                'estado' => $estadoFinal,
                'acepta_donaciones' => (bool) ($item->acepta_donaciones ?? false),
                'tiene_meta_donacion' => (bool) ($item->tiene_meta_donacion ?? false),
                'meta_donacion' => (float) ($item->meta_donacion ?? 0),
                'monto_recaudado' => (float) ($item->monto_recaudado ?? 0),
                'yape_numero' => $item->yape_numero ?? null,
                'imagen' => $item->imagen ?: 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1400&q=85',
                'descripcion' => $item->descripcion,
                'funciones' => !empty($funcionesArr) ? $funcionesArr : ['Apoyo activo en las actividades de la ONG.'],
                'requisitos' => !empty($requisitosArr) ? $requisitosArr : ['Estudiante universitario activo'],
            ];
        })->toArray();
    }

    public function index()
    {
        $oportunidades = array_merge($this->getOportunidadesCreadas(), $this->getOportunidadesBase());
        return view('oportunidades', compact('oportunidades'));
    }

    public function detalle($id = 1)
    {
        $this->asegurarTablasDonaciones();
        $todas = collect(array_merge($this->getOportunidadesCreadas(), $this->getOportunidadesBase()));
        $oportunidad = $todas->firstWhere('id', (int)$id) ?? $todas->first();

        $mismaOng = $todas->filter(fn($op) => $op['id'] !== $oportunidad['id'] && $op['ong'] === $oportunidad['ong']);
        $otrasCusco = $todas->filter(fn($op) => $op['id'] !== $oportunidad['id'] && $op['ong'] !== $oportunidad['ong']);
        $recomendadas = $mismaOng->merge($otrasCusco)->take(6)->values();

        $donacionesRecientes = collect();
        $rendicionesGasto = collect();

        if (!empty($oportunidad['real_id']) && $oportunidad['acepta_donaciones']) {
            $donacionesRecientes = DB::table('donaciones')
                ->where('oportunidad_id', $oportunidad['real_id'])
                ->orderByDesc('id')
                ->limit(10)
                ->get();

            $rendicionesGasto = DB::table('rendiciones_gasto')
                ->where('oportunidad_id', $oportunidad['real_id'])
                ->orderByDesc('fecha_gasto')
                ->get();
        }

        return view('detalle-oportunidad', compact('oportunidad', 'recomendadas', 'donacionesRecientes', 'rendicionesGasto'));
    }

    // =========================================================================
    // PANTALLAS DEDICADAS DE CREACIÓN / EDICIÓN DE CAMPAÑA (ONG)
    // =========================================================================
    public function vistaCrearCampana()
    {
        $this->asegurarTablasDonaciones();
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            abort(403, 'Acceso exclusivo para organizaciones.');
        }

        $campana = null;
        return view('ong.formulario-campana', compact('user', 'campana'));
    }

    public function vistaEditarCampana($id)
    {
        $this->asegurarTablasDonaciones();
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            abort(403, 'Acceso exclusivo para organizaciones.');
        }

        $campana = DB::table('oportunidades')->where('id', $id)->where('ong_id', $user->id)->first();
        if (!$campana) {
            abort(404, 'Campaña no encontrada.');
        }

        return view('ong.formulario-campana', compact('user', 'campana'));
    }

    // =========================================================================
    // PANTALLA DEDICADA DE POSTULACIÓN (VOLUNTARIO)
    // =========================================================================
    public function vistaPostular($id)
    {
        $user = Auth::user();
        if (!$user || $user->tipo_usuario === 'organizacion') {
            return redirect()->route('detalle-oportunidad', ['id' => $id]);
        }

        $todas = collect(array_merge($this->getOportunidadesCreadas(), $this->getOportunidadesBase()));
        $oportunidad = $todas->firstWhere('id', (int)$id) ?? abort(404);

        return view('postular-campana', compact('user', 'oportunidad'));
    }

    public function postular(Request $request, $id = null)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Debes iniciar sesión como voluntario'], 401);
        }

        if ($user->tipo_usuario === 'organizacion') {
            return response()->json([
                'status' => 'error',
                'message' => 'Las cuentas de Organización / ONG no pueden postular a convocatorias.'
            ], 403);
        }

        $request->validate([
            'titulo' => 'required|string',
            'universidad' => 'required|string|max:150',
            'ciclo' => 'required|string|max:50',
            'telefono_contacto' => 'required|string|max:30',
            'disponibilidad' => 'required|string|max:150',
            'motivacion' => 'required|string|min:10',
        ]);

        $titulo = $request->input('titulo');
        $organizacion = $request->input('organizacion', 'ONG Aliada Cusco');
        $horas = (int) $request->input('horas', 20);

        $yaPostulo = DB::table('postulaciones')
            ->where('user_id', $user->id)
            ->where('proyecto', $titulo)
            ->whereIn('estado', ['En revisión', 'Aprobada', 'Completada'])
            ->exists();

        if ($yaPostulo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ya tienes una postulación activa o completada en esta convocatoria.'
            ], 422);
        }

        $oportunidadDb = DB::table('oportunidades')->where('titulo', $titulo)->first();

        if ($oportunidadDb) {
            $expirada = $oportunidadDb->fecha_cierre && Carbon::now()->greaterThan(Carbon::parse($oportunidadDb->fecha_cierre));
            if ($oportunidadDb->estado === 'Cerrada' || $oportunidadDb->cupos_disponibles <= 0 || $expirada) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Esta convocatoria ya cerró sus postulaciones o no tiene cupos disponibles.'
                ], 422);
            }
        }

        DB::table('postulaciones')->insert([
            'user_id' => $user->id,
            'oportunidad_id' => $oportunidadDb ? $oportunidadDb->id : null,
            'ong_id' => $oportunidadDb ? $oportunidadDb->ong_id : null,
            'proyecto' => $titulo,
            'organizacion' => $organizacion,
            'horas' => $horas,
            'horas_validadas' => 0,
            'universidad' => $request->universidad,
            'ciclo' => $request->ciclo,
            'telefono_contacto' => $request->telefono_contacto,
            'disponibilidad' => $request->disponibilidad,
            'motivacion' => $request->motivacion,
            'estado' => 'En revisión',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => '¡Tu ficha de postulación fue enviada exitosamente a la ONG!'
        ]);
    }

    // =========================================================================
    // PANTALLA DEDICADA DE DONACIÓN + VOUCHER OFICIAL SOLIDARIO
    // =========================================================================
    public function vistaDonar($realId)
    {
        $this->asegurarTablasDonaciones();
        $user = Auth::user();

        $campana = DB::table('oportunidades')->where('id', $realId)->first();
        if (!$campana || !($campana->acepta_donaciones ?? false)) {
            abort(404, 'Esta campaña no tiene habilitada la recepción de donaciones.');
        }

        $ong = $campana->ong_id ? DB::table('users')->where('id', $campana->ong_id)->first() : null;

        return view('donar-campana', compact('user', 'campana', 'ong'));
    }

    public function registrarDonacion(Request $request, $realId)
    {
        $this->asegurarTablasDonaciones();
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Debes iniciar sesión para realizar y validar tu donación.'], 401);
        }

        if ($user->tipo_usuario === 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'Las cuentas institucionales de ONG no realizan donaciones desde este portal.'], 403);
        }

        $campana = DB::table('oportunidades')->where('id', $realId)->first();
        if (!$campana || !($campana->acepta_donaciones ?? false)) {
            return response()->json(['status' => 'error', 'message' => 'Esta campaña no acepta donaciones en este momento.'], 422);
        }

        $metodo = $request->input('metodo_pago', 'Yape');
        $monto = (float) $request->input('monto', 0);
        $numeroOperacion = trim((string) $request->input('numero_operacion', ''));
        $detalleEspecie = trim((string) $request->input('detalle_especie', ''));

        if ($metodo === 'Donación en Especie') {
            if (strlen($detalleEspecie) < 6) {
                return response()->json(['status' => 'error', 'message' => 'Detalla los insumos o bienes que entregarás (mínimo 6 caracteres).'], 422);
            }
            $monto = 0;
        } else {
            if ($monto < 1) {
                return response()->json(['status' => 'error', 'message' => 'Ingresa un monto libre válido (mínimo S/ 1.00).'], 422);
            }
            if (strlen($numeroOperacion) < 5) {
                return response()->json(['status' => 'error', 'message' => 'Ingresa el N° de Operación / Constancia real de tu transferencia (mínimo 5 dígitos) para validar el voucher.'], 422);
            }
        }

        $esAnonimo = $request->boolean('es_anonimo');
        $donanteNombre = $esAnonimo
            ? 'Donante Solidario Anónimo'
            : (trim((string) $request->input('donante_nombre')) ?: $user->name);

        $codigoComprobante = 'VCH-CUSCO-' . date('Y') . '-' . strtoupper(Str::random(6));

        DB::table('donaciones')->insert([
            'oportunidad_id' => $campana->id,
            'user_id' => $user->id,
            'donante_nombre' => $donanteNombre,
            'donante_email' => $user->email,
            'donante_dni' => $user->dni ?? null,
            'es_anonimo' => $esAnonimo,
            'metodo_pago' => $metodo,
            'numero_operacion' => $numeroOperacion ?: 'ENTREGA-PRESENCIAL',
            'monto' => $monto,
            'detalle_especie' => $detalleEspecie ?: null,
            'codigo_comprobante' => $codigoComprobante,
            'mensaje_apoyo' => trim((string) $request->input('mensaje_apoyo', '')) ?: null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($monto > 0) {
            DB::table('oportunidades')->where('id', $campana->id)->increment('monto_recaudado', $monto);
        }

        // Notificar a la ONG sobre la nueva donación recibida
        if ($campana->ong_id && Schema::hasTable('notificaciones')) {
            $descAporte = $metodo === 'Donación en Especie' ? "bienes ({$detalleEspecie})" : "S/ " . number_format($monto, 2) . " vía {$metodo} (Op: {$numeroOperacion})";
            DB::table('notificaciones')->insert([
                'user_id' => $campana->ong_id,
                'tipo' => 'donacion',
                'titulo' => '¡Nueva Donación Solidaria Recibida!',
                'mensaje' => "{$donanteNombre} registró un aporte de {$descAporte} para la campaña \"{$campana->titulo}\". Voucher: {$codigoComprobante}.",
                'leida' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => '¡Donación validada y Voucher Solidario generado!',
            'voucher_url' => route('donacion.voucher', ['codigo' => $codigoComprobante])
        ]);
    }

    public function verVoucherDonacion($codigo)
    {
        $this->asegurarTablasDonaciones();

        $donacion = DB::table('donaciones')
            ->join('oportunidades', 'donaciones.oportunidad_id', '=', 'oportunidades.id')
            ->leftJoin('users as ong', 'oportunidades.ong_id', '=', 'ong.id')
            ->where('donaciones.codigo_comprobante', $codigo)
            ->select(
                'donaciones.*',
                'oportunidades.titulo as campana_titulo',
                'oportunidades.ong_nombre',
                'oportunidades.distrito as campana_distrito',
                'ong.ruc as ong_ruc',
                'ong.email as ong_email'
            )
            ->first();

        if (!$donacion) {
            abort(404, 'Voucher solidario no encontrado.');
        }

        return view('voucher-donacion', compact('donacion'));
    }

    // RENDICIÓN DE GASTOS TRANSPARENTE DE LA ONG
    public function registrarRendicionGasto(Request $request, $realId)
    {
        $this->asegurarTablasDonaciones();
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
        }

        $campana = DB::table('oportunidades')->where('id', $realId)->where('ong_id', $user->id)->first();
        if (!$campana) {
            return response()->json(['status' => 'error', 'message' => 'Campaña no encontrada'], 404);
        }

        $request->validate([
            'concepto' => 'required|string|max:255',
            'monto_gastado' => 'required|numeric|min:1',
            'fecha_gasto' => 'required|date',
        ]);

        DB::table('rendiciones_gasto')->insert([
            'oportunidad_id' => $campana->id,
            'ong_id' => $user->id,
            'concepto' => $request->concepto,
            'monto_gastado' => (float) $request->monto_gastado,
            'comprobante_ref' => $request->comprobante_ref ?: 'Boleta / Factura Institucional',
            'fecha_gasto' => $request->fecha_gasto,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rendición de gasto publicada en el portal transparente de la campaña.'
        ]);
    }

    public function verExpediente($id)
    {
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
        }

        $postulacion = DB::table('postulaciones')
            ->join('users', 'postulaciones.user_id', '=', 'users.id')
            ->where('postulaciones.id', $id)
            ->where('postulaciones.ong_id', $user->id)
            ->select(
                'postulaciones.*',
                'users.name as voluntario_nombre',
                'users.email as voluntario_email',
                'users.dni as voluntario_dni',
                'users.carrera as voluntario_carrera'
            )
            ->first();

        if (!$postulacion) {
            return response()->json(['status' => 'error', 'message' => 'Expediente no encontrado'], 404);
        }

        $historial = DB::table('postulaciones')
            ->where('user_id', $postulacion->user_id)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        $horasTotalesPrevias = $historial->where('estado', 'Completada')->sum('horas_validadas');
        $proyectosCompletados = $historial->where('estado', 'Completada')->count();
        $bajasPrevias = $historial->where('estado', 'Baja')->count();
        $promedioEstrellas = $historial->whereNotNull('calificacion')->avg('calificacion');

        return response()->json([
            'postulacion' => $postulacion,
            'estadisticas' => [
                'horas_totales' => $horasTotalesPrevias,
                'completados' => $proyectosCompletados,
                'bajas' => $bajasPrevias,
                'promedio_estrellas' => $promedioEstrellas ? round($promedioEstrellas, 1) : null,
            ],
            'historial' => $historial
        ]);
    }

    private function procesarImagenConvocatoria(Request $request, $imagenActual = null)
    {
        $destino = public_path('uploads/convocatorias');
        if (!file_exists($destino)) {
            mkdir($destino, 0777, true);
        }

        if ($request->filled('imagen_base64') && str_starts_with($request->imagen_base64, 'data:image/')) {
            $data = $request->imagen_base64;
            preg_match('/^data:image\/(\w+);base64,/', $data, $type);
            $ext = strtolower($type[1] ?? 'jpg');
            $data = substr($data, strpos($data, ',') + 1);
            $decoded = base64_decode($data);
            if ($decoded !== false) {
                $nombreArchivo = 'camp_' . time() . '_' . uniqid() . '.' . $ext;
                file_put_contents($destino . '/' . $nombreArchivo, $decoded);
                return '/uploads/convocatorias/' . $nombreArchivo;
            }
        }

        if ($request->hasFile('imagen_file') && $request->file('imagen_file')->isValid()) {
            $file = $request->file('imagen_file');
            $nombreArchivo = 'camp_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($destino, $nombreArchivo);
            return '/uploads/convocatorias/' . $nombreArchivo;
        }

        if ($request->filled('imagen_url')) {
            return $request->input('imagen_url');
        }

        return $imagenActual ?: 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1400&q=85';
    }

    public function crear(Request $request)
    {
        $this->asegurarTablasDonaciones();
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'Acceso exclusivo para ONGs'], 403);
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria' => 'required|string',
            'horas' => 'required|numeric|min:1',
            'cupos' => 'required|numeric|min:1',
            'distrito' => 'required|string',
            'descripcion' => 'required|string',
            'funciones' => 'required|string',
            'requisitos' => 'required|string',
        ]);

        $rutaImagen = $this->procesarImagenConvocatoria($request);
        $cuposTotales = (int) $request->cupos;

        $fechaInicio = $request->fecha_inicio ?: Carbon::now()->toDateString();
        $fechaFin = $request->fecha_fin ?: Carbon::now()->addDays(45)->toDateString();
        $fechaCierre = $request->fecha_cierre
            ? Carbon::parse($request->fecha_cierre)->toDateTimeString()
            : Carbon::parse($fechaFin)->endOfDay()->toDateTimeString();

        $aceptaDonaciones = $request->boolean('acepta_donaciones');
        $tieneMetaDonacion = $aceptaDonaciones && $request->boolean('tiene_meta_donacion');
        $metaDonacion = $tieneMetaDonacion ? max(0, (float) $request->input('meta_donacion', 0)) : 0;
        $yapeNumero = $aceptaDonaciones ? trim((string) $request->input('yape_numero', '')) : null;

        DB::table('oportunidades')->insert([
            'ong_id' => $user->id,
            'ong_nombre' => $user->name,
            'titulo' => $request->titulo,
            'categoria' => $request->categoria,
            'distrito' => $request->distrito,
            'direccion_exacta' => $request->direccion_exacta ?: ($request->distrito . ', Cusco'),
            'latitud' => $request->latitud ?: '-13.53195',
            'longitud' => $request->longitud ?: '-71.96746',
            'horario' => $request->horario ?: '¡Es Flexible! Nos acomodamos a tu horario.',
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'fecha_cierre' => $fechaCierre,
            'horas' => (int) $request->horas,
            'cupos' => $cuposTotales,
            'cupos_disponibles' => $cuposTotales,
            'estado' => 'Abierta',
            'acepta_donaciones' => $aceptaDonaciones,
            'tiene_meta_donacion' => $tieneMetaDonacion,
            'meta_donacion' => $metaDonacion,
            'monto_recaudado' => 0,
            'yape_numero' => $yapeNumero ?: null,
            'imagen' => $rutaImagen,
            'descripcion' => $request->descripcion,
            'funciones' => $request->funciones,
            'requisitos' => $request->requisitos,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['status' => 'success', 'message' => '¡Campaña publicada correctamente!']);
    }

    public function actualizar(Request $request, $id)
    {
        $this->asegurarTablasDonaciones();
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
        }

        $conv = DB::table('oportunidades')->where('id', $id)->where('ong_id', $user->id)->first();
        if (!$conv) {
            return response()->json(['status' => 'error', 'message' => 'Campaña no encontrada'], 404);
        }

        $rutaImagen = $this->procesarImagenConvocatoria($request, $conv->imagen);

        $aprobados = DB::table('postulaciones')
            ->where('oportunidad_id', $id)
            ->whereIn('estado', ['Aprobada', 'Completada'])
            ->count();

        $nuevosCuposTotales = (int) $request->cupos;
        $nuevosDisponibles = max(0, $nuevosCuposTotales - $aprobados);

        $fechaInicio = $request->fecha_inicio ?: $conv->fecha_inicio;
        $fechaFin = $request->fecha_fin ?: $conv->fecha_fin;
        $fechaCierre = $request->fecha_cierre
            ? Carbon::parse($request->fecha_cierre)->toDateTimeString()
            : $conv->fecha_cierre;

        $aceptaDonaciones = $request->boolean('acepta_donaciones');
        $tieneMetaDonacion = $aceptaDonaciones && $request->boolean('tiene_meta_donacion');
        $metaDonacion = $tieneMetaDonacion ? max(0, (float) $request->input('meta_donacion', 0)) : 0;
        $yapeNumero = $aceptaDonaciones ? trim((string) $request->input('yape_numero', '')) : null;

        DB::table('oportunidades')->where('id', $id)->where('ong_id', $user->id)->update([
            'titulo' => $request->titulo,
            'categoria' => $request->categoria,
            'distrito' => $request->distrito,
            'direccion_exacta' => $request->direccion_exacta ?: ($request->distrito . ', Cusco'),
            'latitud' => $request->latitud ?: $conv->latitud,
            'longitud' => $request->longitud ?: $conv->longitud,
            'horario' => $request->horario ?: $conv->horario,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'fecha_cierre' => $fechaCierre,
            'horas' => (int) $request->horas,
            'cupos' => $nuevosCuposTotales,
            'cupos_disponibles' => $nuevosDisponibles,
            'estado' => $nuevosDisponibles > 0 ? 'Abierta' : 'Cerrada',
            'acepta_donaciones' => $aceptaDonaciones,
            'tiene_meta_donacion' => $tieneMetaDonacion,
            'meta_donacion' => $metaDonacion,
            'yape_numero' => $yapeNumero ?: null,
            'imagen' => $rutaImagen,
            'descripcion' => $request->descripcion,
            'funciones' => $request->funciones,
            'requisitos' => $request->requisitos,
            'updated_at' => now()
        ]);

        return response()->json(['status' => 'success', 'message' => '¡Campaña actualizada correctamente!']);
    }

    public function toggleEstadoConvocatoria($id)
    {
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
        }

        $conv = DB::table('oportunidades')->where('id', $id)->where('ong_id', $user->id)->first();
        if (!$conv) {
            return response()->json(['status' => 'error', 'message' => 'No encontrada'], 404);
        }

        $nuevoEstado = ($conv->estado === 'Abierta') ? 'Cerrada' : 'Abierta';
        $nuevosDisponibles = ($nuevoEstado === 'Abierta' && $conv->cupos_disponibles <= 0) ? 1 : $conv->cupos_disponibles;

        DB::table('oportunidades')->where('id', $id)->update([
            'estado' => $nuevoEstado,
            'cupos_disponibles' => $nuevosDisponibles,
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $nuevoEstado === 'Cerrada'
                ? 'Campaña marcada como CERRADA (Cupos finalizados).'
                : 'Campaña REABIERTA para nuevos postulantes.'
        ]);
    }

    public function eliminar($id)
    {
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
        }

        DB::table('oportunidades')->where('id', $id)->where('ong_id', $user->id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Campaña eliminada del sistema.']);
    }

    public function registrarAvance(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'fecha' => 'required|date',
            'horas_registradas' => 'required|integer|min:1|max:100',
            'actividad' => 'required|string|max:255'
        ]);

        $postulacion = DB::table('postulaciones')
            ->where('id', $id)
            ->where('ong_id', $user->id)
            ->first();

        if (!$postulacion) {
            return response()->json(['status' => 'error', 'message' => 'Voluntario no encontrado'], 404);
        }

        DB::table('asistencias')->insert([
            'postulacion_id' => $id,
            'fecha' => $request->fecha,
            'horas_registradas' => (int) $request->horas_registradas,
            'actividad' => $request->actividad,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $nuevoTotal = $postulacion->horas_validadas + (int) $request->horas_registradas;

        DB::table('postulaciones')->where('id', $id)->update([
            'horas_validadas' => $nuevoTotal,
            'updated_at' => now()
        ]);

        DB::table('notificaciones')->insert([
            'user_id' => $postulacion->user_id,
            'tipo' => 'avance',
            'titulo' => 'Nuevas horas registradas (+' . $request->horas_registradas . ' hrs)',
            'mensaje' => "{$user->name} registró +{$request->horas_registradas} hrs por \"{$request->actividad}\" en {$postulacion->proyecto}. Acumulas {$nuevoTotal}/{$postulacion->horas} hrs.",
            'postulacion_id' => $id,
            'leida' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => "¡Se sumaron +{$request->horas_registradas} hrs! El voluntario acumula {$nuevoTotal} hrs."
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->tipo_usuario !== 'organizacion') {
            return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
        }

        $postulacion = DB::table('postulaciones')
            ->where('id', $id)
            ->where('ong_id', $user->id)
            ->first();

        if (!$postulacion) {
            return response()->json(['status' => 'error', 'message' => 'Solicitud no encontrada'], 404);
        }

        $nuevoEstado = $request->input('estado', 'Aprobada');

        if ($nuevoEstado === 'Aprobada' && $postulacion->estado !== 'Aprobada') {
            if ($postulacion->oportunidad_id) {
                $oportunidad = DB::table('oportunidades')->where('id', $postulacion->oportunidad_id)->first();
                if ($oportunidad) {
                    if ($oportunidad->cupos_disponibles <= 0) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Ya no quedan vacantes disponibles en esta campaña.'
                        ], 422);
                    }

                    $restantes = max(0, $oportunidad->cupos_disponibles - 1);
                    DB::table('oportunidades')->where('id', $oportunidad->id)->update([
                        'cupos_disponibles' => $restantes,
                        'estado' => $restantes === 0 ? 'Cerrada' : $oportunidad->estado,
                        'updated_at' => now()
                    ]);
                }
            }

            DB::table('notificaciones')->insert([
                'user_id' => $postulacion->user_id,
                'tipo' => 'aprobada',
                'titulo' => '¡Fuiste aceptado en la campaña!',
                'mensaje' => "{$user->name} aceptó tu postulación para \"{$postulacion->proyecto}\". Ya puedes coordinar tus jornadas por WhatsApp.",
                'postulacion_id' => $id,
                'leida' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if ($nuevoEstado === 'Baja' && $postulacion->estado === 'Aprobada' && $postulacion->oportunidad_id) {
            $oportunidad = DB::table('oportunidades')->where('id', $postulacion->oportunidad_id)->first();
            if ($oportunidad) {
                $nuevasDisponibles = min($oportunidad->cupos, $oportunidad->cupos_disponibles + 1);
                DB::table('oportunidades')->where('id', $oportunidad->id)->update([
                    'cupos_disponibles' => $nuevasDisponibles,
                    'estado' => 'Abierta',
                    'updated_at' => now()
                ]);
            }
        }

        $datosActualizar = [
            'estado' => $nuevoEstado,
            'updated_at' => now()
        ];

        if ($nuevoEstado === 'Completada') {
            $horasCumplidas = (int) $request->input('horas_validadas', max($postulacion->horas_validadas, $postulacion->horas));
            $calificacion = (int) $request->input('calificacion', 5);
            $resenaOng = $request->input('resena_ong', 'Excelente compromiso y puntualidad durante toda la campaña.');
            $codigo = 'VM-CUSCO-' . date('Y') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);

            $datosActualizar['horas_validadas'] = $horasCumplidas;
            $datosActualizar['codigo_certificado'] = $codigo;
            $datosActualizar['calificacion'] = $calificacion;
            $datosActualizar['resena_ong'] = $resenaOng;

            DB::table('notificaciones')->insert([
                'user_id' => $postulacion->user_id,
                'tipo' => 'certificado',
                'titulo' => '¡Felicitaciones! Certificado Oficial Emitido',
                'mensaje' => "La organización {$user->name} ha validado tus {$horasCumplidas} horas en \"{$postulacion->proyecto}\" y emitió tu Certificado Oficial ({$codigo}).",
                'postulacion_id' => $id,
                'leida' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        DB::table('postulaciones')->where('id', $id)->update($datosActualizar);

        return response()->json([
            'status' => 'success',
            'message' => match($nuevoEstado) {
                'Aprobada' => '¡Voluntario aceptado! Pasó a la lista de campaña y se descontó 1 vacante.',
                'Baja' => 'Voluntario dado de baja. Se liberó +1 vacante automáticamente.',
                'Completada' => '¡Campaña finalizada y Certificado Oficial emitido al estudiante!',
                default => "Solicitud marcada como: {$nuevoEstado}"
            }
        ]);
    }

    public function verCertificado($id)
    {
        $postulacion = DB::table('postulaciones')
            ->join('users as voluntario', 'postulaciones.user_id', '=', 'voluntario.id')
            ->leftJoin('users as ong', 'postulaciones.ong_id', '=', 'ong.id')
            ->where('postulaciones.id', $id)
            ->where('postulaciones.estado', 'Completada')
            ->select(
                'postulaciones.*',
                'voluntario.name as voluntario_nombre',
                'voluntario.dni as voluntario_dni',
                'voluntario.carrera as voluntario_carrera',
                'ong.ruc as ong_ruc',
                'ong.distrito as ong_distrito'
            )
            ->first();

        if (!$postulacion) {
            abort(404, 'Certificado no disponible aún.');
        }

        return view('certificado', compact('postulacion'));
    }

    public function constanciaConsolidada()
    {
        $user = Auth::user();
        if (!$user || $user->tipo_usuario === 'organizacion') {
            abort(403);
        }

        $completadas = DB::table('postulaciones')
            ->where('user_id', $user->id)
            ->where('estado', 'Completada')
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalHoras = $completadas->sum('horas_validadas');

        return view('constancia-consolidada', compact('user', 'completadas', 'totalHoras'));
    }

    public function verificarPublico($codigo)
    {
        $certificado = DB::table('postulaciones')
            ->join('users as voluntario', 'postulaciones.user_id', '=', 'voluntario.id')
            ->leftJoin('users as ong', 'postulaciones.ong_id', '=', 'ong.id')
            ->where('postulaciones.codigo_certificado', $codigo)
            ->where('postulaciones.estado', 'Completada')
            ->select(
                'postulaciones.*',
                'voluntario.name as voluntario_nombre',
                'voluntario.dni as voluntario_dni',
                'voluntario.carrera as voluntario_carrera',
                'ong.ruc as ong_ruc'
            )
            ->first();

        return view('verificar-certificado', compact('certificado', 'codigo'));
    }
}