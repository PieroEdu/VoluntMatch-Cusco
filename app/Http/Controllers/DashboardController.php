<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $this->asegurarColumnasYTablas();

        if (Schema::hasTable('users') && !User::where('email', 'admin@voluntmatch.edu.pe')->exists()) {
            $datosAdmin = [
                'name' => 'Coordinación Proyección Social UC',
                'email' => 'admin@voluntmatch.edu.pe',
                'password' => Hash::make('Admin2026*'),
            ];
            if (Schema::hasColumn('users', 'rol')) $datosAdmin['rol'] = 'admin';
            if (Schema::hasColumn('users', 'tipo_usuario')) $datosAdmin['tipo_usuario'] = 'admin';
            if (Schema::hasColumn('users', 'distrito')) $datosAdmin['distrito'] = 'Cusco - San Jerónimo';
            User::create($datosAdmin);
        }

        $user = Auth::user();
        $rol = $user->rol ?? $user->tipo_usuario ?? 'voluntario';

        // =====================================================================
        // ROL 1: PANEL ADMINISTRADOR
        // =====================================================================
        if ($rol === 'admin' || $user->email === 'admin@voluntmatch.edu.pe') {
            $usuarios = Schema::hasTable('users') ? DB::table('users')->orderByDesc('id')->get() : collect();
            $usuariosById = $usuarios->keyBy('id');

            $voluntarios = $usuarios->filter(function ($u) {
                $r = $u->rol ?? $u->tipo_usuario ?? 'voluntario';
                return in_array($r, ['voluntario', 'estudiante']) && $u->email !== 'admin@voluntmatch.edu.pe';
            })->values();

            $organizaciones = $usuarios->filter(function ($u) {
                $r = $u->rol ?? $u->tipo_usuario ?? '';
                return $r === 'organizacion';
            })->values();

            $convocatorias = Schema::hasTable('oportunidades')
                ? DB::table('oportunidades')->orderByDesc('id')->get()
                : collect();
            $convocatoriasById = $convocatorias->keyBy('id');

            $postulacionesRaw = Schema::hasTable('postulaciones')
                ? DB::table('postulaciones')->orderByDesc('id')->get()
                : collect();

            $postulaciones = $postulacionesRaw->map(function ($p) use ($usuariosById, $convocatoriasById) {
                $vol = isset($p->user_id) ? $usuariosById->get($p->user_id) : null;
                $conv = isset($p->oportunidad_id) ? $convocatoriasById->get($p->oportunidad_id) : null;
                $ong = ($conv && isset($conv->user_id)) ? $usuariosById->get($conv->user_id) : null;

                $p->voluntario_nombre = $p->voluntario_nombre ?? $vol->name ?? 'Estudiante Registrado';
                $p->voluntario_dni = $p->voluntario_dni ?? $vol->dni ?? '---';
                $p->voluntario_carrera = $p->voluntario_carrera ?? $vol->carrera ?? 'Estudiante UC';
                $p->universidad = $p->universidad ?? $vol->universidad ?? 'Universidad Continental';
                $p->proyecto = $p->proyecto ?? $conv->titulo ?? 'Campaña de Voluntariado';
                $p->organizacion = $p->organizacion ?? $conv->organizacion ?? $ong->name ?? 'ONG Aliada Cusco';
                $p->horas = $p->horas ?? $conv->horas ?? 0;
                $p->horas_validadas = $p->horas_validadas ?? 0;
                return $p;
            });

            $totalHorasSistema = $postulaciones->where('estado', 'Completada')->sum(fn($p) => (int) ($p->horas_validadas ?: $p->horas));
            $certificadosEmitidos = $postulaciones->where('estado', 'Completada')->values();

            $porDistrito = $convocatorias->groupBy(fn($c) => $c->distrito ?: 'Cusco Centro')->map->count();
            $porCategoria = $convocatorias->groupBy(fn($c) => $c->categoria ?: 'General')->map->count();

            $progresoAlumnos = $voluntarios->map(function ($vol) use ($postulaciones) {
                $misPosts = $postulaciones->filter(fn($p) => ($p->user_id ?? null) == $vol->id || ($p->voluntario_email ?? '') === $vol->email)->values();
                $horasCert = $misPosts->where('estado', 'Completada')->sum(fn($p) => (int) ($p->horas_validadas ?: $p->horas));
                $horasCurso = $misPosts->where('estado', 'Aprobada')->sum(fn($p) => (int) ($p->horas_validadas ?? 0));
                $calificaciones = $misPosts->pluck('calificacion')->filter();
                $vol->horas_certificadas = $horasCert;
                $vol->horas_en_curso = $horasCurso;
                $vol->porcentaje_meta = min(100, round(($horasCert / 100) * 100));
                $vol->proyectos_count = $misPosts->count();
                $vol->rating_promedio = $calificaciones->isNotEmpty() ? round($calificaciones->avg(), 1) : 5.0;
                $vol->historial_proyectos = $misPosts;
                $vol->iniciales = $this->obtenerIniciales($vol->name ?? 'ES');
                return $vol;
            })->sortByDesc('horas_certificadas')->values();

            $expedienteOngs = $organizaciones->map(function ($ong) use ($convocatorias, $postulaciones) {
                $misConvs = $convocatorias->filter(fn($c) => ($c->user_id ?? null) == $ong->id || ($c->ong_id ?? null) == $ong->id || ($c->organizacion ?? '') === $ong->name)->values();
                $misPosts = $postulaciones->filter(fn($p) => ($p->organizacion ?? '') === $ong->name)->values();
                $ong->campanas_lista = $misConvs;
                $ong->total_campanas = $misConvs->count();
                $ong->voluntarios_certificados = $misPosts->where('estado', 'Completada')->count();
                $ong->horas_emitidas = $misPosts->where('estado', 'Completada')->sum(fn($p) => (int) ($p->horas_validadas ?: $p->horas));
                $ong->estado_verificacion = $ong->estado_verificacion ?? 'Aprobado';
                $ong->iniciales = $this->obtenerIniciales($ong->name ?? 'ON');
                return $ong;
            })->values();

            $auditoriaLogs = Schema::hasTable('admin_logs')
                ? DB::table('admin_logs')->orderByDesc('id')->limit(30)->get()
                : collect();

            $consultasContacto = Schema::hasTable('consultas_contacto')
                ? DB::table('consultas_contacto')->orderBy('id')->get()
                : collect();

            // Agrupar conversaciones por correo para el Chat de Soporte tipo 2 columnas
            $conversacionesAgrupadas = $consultasContacto
                ->groupBy(fn($c) => strtolower(trim($c->correo)))
                ->map(function ($grupo, $correoKey) {
                    $ultimo = $grupo->last();
                    $pendientes = $grupo->where('estado', 'Pendiente')->count();
                    $mensajesHilo = collect();

                    foreach ($grupo as $item) {
                        $mensajesHilo->push([
                            'autor' => 'usuario',
                            'nombre' => $item->nombre,
                            'asunto' => $item->asunto,
                            'texto' => $item->mensaje,
                            'hora' => \Carbon\Carbon::parse($item->created_at)->format('d/m H:i'),
                        ]);
                        if (!empty($item->respuesta_admin)) {
                            $mensajesHilo->push([
                                'autor' => 'admin',
                                'nombre' => 'Coordinación UC',
                                'asunto' => $item->asunto,
                                'texto' => $item->respuesta_admin,
                                'hora' => $item->respondido_at ? \Carbon\Carbon::parse($item->respondido_at)->format('d/m H:i') : 'Respondido',
                            ]);
                        }
                    }

                    return [
                        'key' => $correoKey,
                        'user_id' => $ultimo->user_id,
                        'nombre' => $ultimo->nombre,
                        'correo' => $ultimo->correo,
                        'rol' => $ultimo->rol_remitente,
                        'iniciales' => $this->obtenerIniciales($ultimo->nombre),
                        'ultimo_texto' => Str::limit($ultimo->mensaje, 48),
                        'ultima_hora' => \Carbon\Carbon::parse($ultimo->created_at)->format('H:i'),
                        'pendientes' => $pendientes,
                        'mensajes' => $mensajesHilo->values(),
                    ];
                })
                ->sortByDesc('pendientes')
                ->values();

            return view('dashboard.admin', compact(
                'user',
                'voluntarios',
                'organizaciones',
                'expedienteOngs',
                'convocatorias',
                'postulaciones',
                'totalHorasSistema',
                'certificadosEmitidos',
                'porDistrito',
                'porCategoria',
                'progresoAlumnos',
                'auditoriaLogs',
                'consultasContacto',
                'conversacionesAgrupadas'
            ));
        }

        // =====================================================================
        // ROL 2: PANEL ORGANIZACIÓN / ONG
        // =====================================================================
        if ($rol === 'organizacion') {
            $misConvocatorias = Schema::hasTable('oportunidades')
                ? DB::table('oportunidades')
                    ->where(function ($q) use ($user) {
                        if (Schema::hasColumn('oportunidades', 'ong_id')) $q->where('ong_id', $user->id);
                        if (Schema::hasColumn('oportunidades', 'user_id')) $q->orWhere('user_id', $user->id);
                        if (Schema::hasColumn('oportunidades', 'organizacion_id')) $q->orWhere('organizacion_id', $user->id);
                        if (Schema::hasColumn('oportunidades', 'ong_nombre')) $q->orWhere('ong_nombre', $user->name);
                    })
                    ->orderByDesc('id')
                    ->get()
                : collect();

            $idsConv = $misConvocatorias->pluck('id')->toArray();
            $usuariosById = Schema::hasTable('users') ? DB::table('users')->get()->keyBy('id') : collect();
            $convocatoriasById = $misConvocatorias->keyBy('id');

            $todasPostulacionesRaw = Schema::hasTable('postulaciones')
                ? DB::table('postulaciones')
                    ->where(function ($q) use ($user, $idsConv) {
                        if (Schema::hasColumn('postulaciones', 'ong_id')) $q->where('ong_id', $user->id);
                        if (!empty($idsConv) && Schema::hasColumn('postulaciones', 'oportunidad_id')) $q->orWhereIn('oportunidad_id', $idsConv);
                        if (Schema::hasColumn('postulaciones', 'organizacion')) $q->orWhere('organizacion', $user->name);
                    })
                    ->orderByDesc('id')
                    ->get()
                : collect();

            $todasPostulaciones = $todasPostulacionesRaw->map(function ($p) use ($usuariosById, $convocatoriasById) {
                $vol = isset($p->user_id) ? $usuariosById->get($p->user_id) : null;
                $conv = isset($p->oportunidad_id) ? $convocatoriasById->get($p->oportunidad_id) : null;

                $p->voluntario_nombre = $p->voluntario_nombre ?? $vol->name ?? 'Voluntario';
                $p->voluntario_dni = $p->voluntario_dni ?? $vol->dni ?? '---';
                $p->voluntario_carrera = $p->voluntario_carrera ?? $vol->carrera ?? '';
                $p->proyecto = $p->proyecto ?? $conv->titulo ?? 'Campaña';
                $p->horas = $p->horas ?? $conv->horas ?? 0;
                $p->horas_validadas = $p->horas_validadas ?? 0;
                return $p;
            });

            $solicitudesPendientes = $todasPostulaciones->where('estado', 'En revisión')->values();
            $voluntariosActivos = $todasPostulaciones->whereIn('estado', ['Aprobada', 'Completada'])->values();
            $voluntariosPorCampana = $voluntariosActivos->groupBy('oportunidad_id');

            $bitacoras = Schema::hasTable('asistencias')
                ? DB::table('asistencias')->orderByDesc('id')->get()->groupBy('postulacion_id')
                : (Schema::hasTable('bitacoras') ? DB::table('bitacoras')->orderByDesc('id')->get()->groupBy('postulacion_id') : collect());

            $donacionesPorCampana = (Schema::hasTable('donaciones') && !empty($idsConv))
                ? DB::table('donaciones')->whereIn('oportunidad_id', $idsConv)->orderByDesc('id')->get()->groupBy('oportunidad_id')
                : collect();

            $notificaciones = Schema::hasTable('notificaciones')
                ? DB::table('notificaciones')->where('user_id', $user->id)->orderByDesc('id')->get()
                : collect();

            $hiloSoporte = Schema::hasTable('consultas_contacto')
                ? DB::table('consultas_contacto')->where('correo', $user->email)->orderBy('id')->get()
                : collect();

            $totalConvocatorias = $misConvocatorias->count();
            $horasOtorgadas = $todasPostulaciones->where('estado', 'Completada')->sum(fn($p) => (int) ($p->horas_validadas ?: $p->horas));

            return view('dashboard.organizacion', compact(
                'user',
                'misConvocatorias',
                'solicitudesPendientes',
                'voluntariosPorCampana',
                'bitacoras',
                'donacionesPorCampana',
                'notificaciones',
                'hiloSoporte',
                'totalConvocatorias',
                'horasOtorgadas'
            ));
        }

        // =====================================================================
        // ROL 3: PANEL VOLUNTARIO / ESTUDIANTE
        // =====================================================================
        $convocatoriasById = Schema::hasTable('oportunidades') ? DB::table('oportunidades')->get()->keyBy('id') : collect();
        $usuariosById = Schema::hasTable('users') ? DB::table('users')->get()->keyBy('id') : collect();

        $postulacionesRaw = Schema::hasTable('postulaciones')
            ? DB::table('postulaciones')
                ->where(function ($q) use ($user) {
                    if (Schema::hasColumn('postulaciones', 'user_id')) $q->where('user_id', $user->id);
                    if (Schema::hasColumn('postulaciones', 'voluntario_email')) $q->orWhere('voluntario_email', $user->email);
                })
                ->orderByDesc('id')
                ->get()
            : collect();

        $postulaciones = $postulacionesRaw->map(function ($p) use ($convocatoriasById, $usuariosById) {
            $conv = isset($p->oportunidad_id) ? $convocatoriasById->get($p->oportunidad_id) : null;
            $ong = ($conv && isset($conv->user_id)) ? $usuariosById->get($conv->user_id) : null;

            $p->proyecto = $p->proyecto ?? $conv->titulo ?? 'Campaña';
            $p->organizacion = $p->organizacion ?? $conv->ong_nombre ?? $ong->name ?? 'ONG Aliada';
            $p->horas = $p->horas ?? $conv->horas ?? 0;
            $p->horas_validadas = $p->horas_validadas ?? 0;
            return $p;
        });

        $totalHorasCompletadas = $postulaciones->where('estado', 'Completada')->sum(fn($p) => (int) ($p->horas_validadas ?: $p->horas));
        $totalHorasEnProgreso = $postulaciones->where('estado', 'Aprobada')->sum(fn($p) => (int) ($p->horas_validadas ?? 0));
        $totalPostulacionesActivas = $postulaciones->whereIn('estado', ['En revisión', 'Aprobada'])->count();
        $totalProyectosCompletados = $postulaciones->where('estado', 'Completada')->count();

        $bitacoras = Schema::hasTable('asistencias')
            ? DB::table('asistencias')->orderByDesc('id')->get()->groupBy('postulacion_id')
            : (Schema::hasTable('bitacoras') ? DB::table('bitacoras')->orderByDesc('id')->get()->groupBy('postulacion_id') : collect());

        $notificaciones = Schema::hasTable('notificaciones')
            ? DB::table('notificaciones')->where('user_id', $user->id)->orderByDesc('id')->get()
            : collect();

        $hiloSoporte = Schema::hasTable('consultas_contacto')
            ? DB::table('consultas_contacto')->where('correo', $user->email)->orderBy('id')->get()
            : collect();

        $certificadoNuevo = $notificaciones->first(fn($n) => ($n->tipo ?? '') === 'certificado' && !($n->leida ?? false));

        return view('dashboard.voluntario', compact(
            'user',
            'postulaciones',
            'totalHorasCompletadas',
            'totalHorasEnProgreso',
            'totalPostulacionesActivas',
            'totalProyectosCompletados',
            'bitacoras',
            'notificaciones',
            'hiloSoporte',
            'certificadoNuevo'
        ));
    }

    // =========================================================================
    // RECIBIR CONSULTA DESDE /contacto O DESDE EL MINI-CHAT DEL DASHBOARD
    // =========================================================================
    public function enviarConsultaContacto(Request $request)
    {
        $this->asegurarColumnasYTablas();

        $nombre = trim((string) $request->input('nombre', Auth::user()->name ?? ''));
        $correo = trim((string) $request->input('correo', Auth::user()->email ?? ''));
        $asunto = trim((string) $request->input('asunto', 'Consulta Directa desde Dashboard'));
        $mensaje = trim((string) $request->input('mensaje'));

        if (!$nombre || !$correo || !$mensaje) {
            return response()->json(['ok' => false, 'message' => 'Escribe tu mensaje antes de enviarlo.'], 422);
        }

        $usuarioRegistrado = Auth::user() ?: (Schema::hasTable('users') ? DB::table('users')->where('email', $correo)->first() : null);
        $rolRemitente = 'Visitante';
        if ($usuarioRegistrado) {
            $r = $usuarioRegistrado->rol ?? $usuarioRegistrado->tipo_usuario ?? 'voluntario';
            $rolRemitente = $r === 'organizacion' ? 'ONG / Organización' : ($r === 'admin' ? 'Administrador' : 'Voluntario');
        }

        DB::table('consultas_contacto')->insert([
            'user_id' => $usuarioRegistrado->id ?? null,
            'rol_remitente' => $rolRemitente,
            'nombre' => $nombre,
            'correo' => $correo,
            'asunto' => $asunto,
            'mensaje' => $mensaje,
            'estado' => 'Pendiente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Tu mensaje fue enviado al Administrador. Recibirás la respuesta aquí mismo.'
        ]);
    }

    // =========================================================================
    // RESPUESTA DEL ADMIN DESDE EL CHAT DE 2 COLUMNAS -> NOTIFICACIÓN AL USUARIO
    // =========================================================================
    public function adminResponderHiloChat(Request $request)
    {
        $this->verificarAdmin();
        $this->asegurarColumnasYTablas();

        $correo = trim((string) $request->input('correo'));
        $nombre = trim((string) $request->input('nombre', 'Usuario'));
        $respuesta = trim((string) $request->input('respuesta'));

        if (!$correo || !$respuesta) {
            return response()->json(['ok' => false, 'message' => 'Selecciona una conversación y escribe un mensaje.'], 422);
        }

        $usuarioDestino = Schema::hasTable('users') ? DB::table('users')->where('email', $correo)->first() : null;
        $ultimaPendiente = DB::table('consultas_contacto')
            ->where('correo', $correo)
            ->orderByDesc('id')
            ->first();

        if ($ultimaPendiente && empty($ultimaPendiente->respuesta_admin)) {
            DB::table('consultas_contacto')->where('id', $ultimaPendiente->id)->update([
                'respuesta_admin' => $respuesta,
                'estado' => 'Respondida',
                'respondido_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $rolDest = 'Voluntario';
            if ($usuarioDestino) {
                $r = $usuarioDestino->rol ?? $usuarioDestino->tipo_usuario ?? 'voluntario';
                $rolDest = $r === 'organizacion' ? 'ONG / Organización' : 'Voluntario';
            }
            DB::table('consultas_contacto')->insert([
                'user_id' => $usuarioDestino->id ?? null,
                'rol_remitente' => $rolDest,
                'nombre' => $usuarioDestino->name ?? $nombre,
                'correo' => $correo,
                'asunto' => 'Mensaje directo de Coordinación UC',
                'mensaje' => 'Conversación iniciada por Coordinación Institucional.',
                'respuesta_admin' => $respuesta,
                'estado' => 'Respondida',
                'respondido_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($usuarioDestino && Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')->insert([
                'user_id' => $usuarioDestino->id,
                'titulo' => 'Nuevo mensaje de Coordinación UC',
                'mensaje' => $respuesta,
                'tipo' => 'respuesta_admin',
                'leida' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->registrarLog('Soporte Chat', "Envió respuesta directa en chat a {$nombre} ({$correo})");

        return response()->json([
            'ok' => true,
            'hora' => now()->format('d/m H:i'),
            'message' => 'Mensaje enviado y notificado al usuario.'
        ]);
    }

    public function actualizarPerfil(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Sesión no válida.'], 401);
        }

        $nombre = trim((string) $request->input('name'));
        $telefono = trim((string) $request->input('telefono'));
        $carrera = trim((string) $request->input('carrera'));
        $distrito = trim((string) $request->input('distrito'));
        $nuevaClave = (string) $request->input('password');

        if (!$nombre) {
            return response()->json(['ok' => false, 'message' => 'El nombre no puede quedar vacío.'], 422);
        }

        $updates = [
            'name' => $nombre,
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('users', 'telefono')) $updates['telefono'] = $telefono;
        if (Schema::hasColumn('users', 'carrera') && $carrera !== '') $updates['carrera'] = $carrera;
        if (Schema::hasColumn('users', 'distrito') && $distrito !== '') $updates['distrito'] = $distrito;

        if ($nuevaClave !== '') {
            if (strlen($nuevaClave) < 6) {
                return response()->json(['ok' => false, 'message' => 'La nueva contraseña debe tener al menos 6 caracteres.'], 422);
            }
            $updates['password'] = Hash::make($nuevaClave);
        }

        DB::table('users')->where('id', $user->id)->update($updates);

        return response()->json(['ok' => true, 'message' => 'Tus datos de perfil se actualizaron correctamente.']);
    }

    public function marcarNotificacionesLeidas()
    {
        if (Auth::check() && Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')->where('user_id', Auth::id())->update(['leida' => 1]);
        }
        return response()->json(['ok' => true]);
    }

    public function adminToggleEstadoOng($id)
    {
        $this->verificarAdmin();
        $ong = DB::table('users')->where('id', $id)->first();
        if (!$ong) {
            return response()->json(['ok' => false, 'message' => 'ONG no encontrada.'], 404);
        }
        $actual = $ong->estado_verificacion ?? 'Aprobado';
        $nuevo = $actual === 'Aprobado' ? 'Pendiente' : 'Aprobado';
        DB::table('users')->where('id', $id)->update(['estado_verificacion' => $nuevo, 'updated_at' => now()]);
        $this->registrarLog('Verificación ONG', "Cambió estado institucional de '{$ong->name}' a {$nuevo}");
        return response()->json(['ok' => true, 'message' => "Estado de '{$ong->name}' actualizado a: {$nuevo}."]);
    }

    public function adminResetPassword($id)
    {
        $this->verificarAdmin();
        $target = DB::table('users')->where('id', $id)->first();
        if (!$target) {
            return response()->json(['ok' => false, 'message' => 'Usuario no encontrado.'], 404);
        }

        $claveTemporal = 'UC-' . date('Y') . '-' . rand(1000, 9999);
        DB::table('users')->where('id', $id)->update([
            'password' => Hash::make($claveTemporal),
            'updated_at' => now(),
        ]);

        $this->registrarLog('Seguridad / Contraseña', "Restableció contraseña temporal para {$target->name} ({$target->email})");

        return response()->json([
            'ok' => true,
            'usuario' => $target->name,
            'email' => $target->email,
            'clave_temporal' => $claveTemporal,
        ]);
    }

    public function adminToggleCampana($id)
    {
        $this->verificarAdmin();
        $campana = DB::table('oportunidades')->where('id', $id)->first();
        if ($campana) {
            $nuevoEstado = ($campana->estado ?? 'Abierta') === 'Cerrada' ? 'Abierta' : 'Cerrada';
            DB::table('oportunidades')->where('id', $id)->update(['estado' => $nuevoEstado]);
            $this->registrarLog('Moderación Campaña', "Cambió estado de '{$campana->titulo}' a {$nuevoEstado}");
            return response()->json(['ok' => true, 'message' => "Campaña cambiada a estado: {$nuevoEstado}."]);
        }
        return response()->json(['ok' => false, 'message' => 'Campaña no encontrada.'], 404);
    }

    public function adminEliminarCampana($id)
    {
        $this->verificarAdmin();
        $campana = DB::table('oportunidades')->where('id', $id)->first();
        DB::table('oportunidades')->where('id', $id)->delete();
        $this->registrarLog('Moderación Campaña', "Eliminó la convocatoria '" . ($campana->titulo ?? "ID {$id}") . "'");
        return response()->json(['ok' => true, 'message' => 'Campaña eliminada por moderación institucional.']);
    }

    public function adminCrearOng(Request $request)
    {
        $this->verificarAdmin();
        $nombre = trim((string) $request->input('name'));
        $email = trim((string) $request->input('email'));
        $ruc = preg_replace('/[^0-9]/', '', (string) $request->input('ruc'));
        $distrito = trim((string) $request->input('distrito', 'Cusco Centro'));
        $password = 'OngCusco2026*';

        if (!$nombre || !$email || !preg_match('/^(10|20)[0-9]{9}$/', $ruc)) {
            return response()->json(['ok' => false, 'message' => 'Completa el nombre, correo y un RUC válido (iniciado en 20 o 10 + 9 dígitos).'], 422);
        }

        if (DB::table('users')->where('email', $email)->exists()) {
            return response()->json(['ok' => false, 'message' => 'Ya existe una cuenta registrada con ese correo.'], 422);
        }

        $data = [
            'name' => $nombre,
            'email' => $email,
            'password' => Hash::make($password),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (Schema::hasColumn('users', 'rol')) $data['rol'] = 'organizacion';
        if (Schema::hasColumn('users', 'tipo_usuario')) $data['tipo_usuario'] = 'organizacion';
        if (Schema::hasColumn('users', 'estado_verificacion')) $data['estado_verificacion'] = 'Aprobado';
        if (Schema::hasColumn('users', 'ruc')) $data['ruc'] = $ruc;
        if (Schema::hasColumn('users', 'distrito')) $data['distrito'] = $distrito;

        DB::table('users')->insert($data);
        $this->registrarLog('Alta Institucional', "Registró y aprobó nueva ONG '{$nombre}' (RUC: {$ruc})");

        return response()->json(['ok' => true, 'message' => "Organización '{$nombre}' registrada y aprobada. Clave inicial: {$password}"]);
    }

    public function adminConvalidarHoras(Request $request)
    {
        $this->verificarAdmin();
        $userId = (int) $request->input('user_id');
        $proyecto = trim((string) $request->input('proyecto', 'Convenio Institucional Proyección Social UC'));
        $horas = (int) $request->input('horas', 20);

        $alumno = DB::table('users')->where('id', $userId)->first();
        if (!$alumno || $horas < 1) {
            return response()->json(['ok' => false, 'message' => 'Selecciona un estudiante y una cantidad válida de horas.'], 422);
        }

        $codigoQr = 'VM-UC-' . date('Y') . '-' . strtoupper(Str::random(6));
        $insert = [
            'estado' => 'Completada',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('postulaciones', 'user_id')) $insert['user_id'] = $alumno->id;
        if (Schema::hasColumn('postulaciones', 'oportunidad_id')) $insert['oportunidad_id'] = 1;
        if (Schema::hasColumn('postulaciones', 'voluntario_nombre')) $insert['voluntario_nombre'] = $alumno->name;
        if (Schema::hasColumn('postulaciones', 'voluntario_email')) $insert['voluntario_email'] = $alumno->email;
        if (Schema::hasColumn('postulaciones', 'voluntario_dni')) $insert['voluntario_dni'] = $alumno->dni ?? '---';
        if (Schema::hasColumn('postulaciones', 'voluntario_carrera')) $insert['voluntario_carrera'] = $alumno->carrera ?? 'Estudiante UC';
        if (Schema::hasColumn('postulaciones', 'proyecto')) $insert['proyecto'] = $proyecto;
        if (Schema::hasColumn('postulaciones', 'organizacion')) $insert['organizacion'] = 'Coordinación Proyección Social UC';
        if (Schema::hasColumn('postulaciones', 'horas')) $insert['horas'] = $horas;
        if (Schema::hasColumn('postulaciones', 'horas_validadas')) $insert['horas_validadas'] = $horas;
        if (Schema::hasColumn('postulaciones', 'codigo_certificado')) $insert['codigo_certificado'] = $codigoQr;

        $postId = DB::table('postulaciones')->insertGetId($insert);

        if (Schema::hasTable('notificaciones')) {
            DB::table('notificaciones')->insert([
                'user_id' => $alumno->id,
                'titulo' => '¡Convalidación Oficial Aprobada!',
                'mensaje' => "La Coordinación Universitaria te ha validado {$horas} horas por '{$proyecto}'. Código QR: {$codigoQr}.",
                'tipo' => 'certificado',
                'postulacion_id' => $postId,
                'leida' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->registrarLog('Convalidación Oficial', "Otorgó {$horas}h a {$alumno->name} ({$codigoQr})");

        return response()->json(['ok' => true, 'message' => "Se convalidaron {$horas} horas a {$alumno->name} y se emitió el certificado {$codigoQr}."]);
    }

    public function adminEnviarComunicado(Request $request)
    {
        $this->verificarAdmin();
        $titulo = trim((string) $request->input('titulo'));
        $mensaje = trim((string) $request->input('mensaje'));

        if (!$titulo || !$mensaje) {
            return response()->json(['ok' => false, 'message' => 'Completa el título y el cuerpo del comunicado.'], 422);
        }

        $destinatarios = DB::table('users')
            ->where('email', '!=', 'admin@voluntmatch.edu.pe')
            ->get();

        if (Schema::hasTable('notificaciones')) {
            foreach ($destinatarios as $dest) {
                DB::table('notificaciones')->insert([
                    'user_id' => $dest->id,
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'tipo' => 'info',
                    'leida' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->registrarLog('Comunicado Global', "Difundió aviso '{$titulo}' a {$destinatarios->count()} usuarios");

        return response()->json(['ok' => true, 'message' => "Comunicado institucional enviado a {$destinatarios->count()} usuarios."]);
    }

    public function adminEliminarUsuario($id)
    {
        $this->verificarAdmin();
        if ((int) $id === (int) Auth::id()) {
            return response()->json(['ok' => false, 'message' => 'No puedes eliminar tu propia cuenta administradora.'], 422);
        }
        $target = DB::table('users')->where('id', $id)->first();
        DB::table('users')->where('id', $id)->delete();
        $this->registrarLog('Gestión Padrón', "Eliminó cuenta de " . ($target->name ?? "ID {$id}"));
        return response()->json(['ok' => true, 'message' => 'Cuenta eliminada del padrón del sistema.']);
    }

    public function adminExportarReporteCsv()
    {
        $this->verificarAdmin();
        $this->registrarLog('Exportación Oficial', 'Descargó padrón consolidado de horas en formato Excel/CSV');

        $usuariosById = Schema::hasTable('users') ? DB::table('users')->get()->keyBy('id') : collect();
        $convocatoriasById = Schema::hasTable('oportunidades') ? DB::table('oportunidades')->get()->keyBy('id') : collect();
        $postulaciones = Schema::hasTable('postulaciones') ? DB::table('postulaciones')->orderByDesc('id')->get() : collect();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="Reporte_Proyeccion_Social_Cusco_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($postulaciones, $usuariosById, $convocatoriasById) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['ID', 'Estudiante', 'DNI', 'Carrera', 'Universidad', 'Campaña', 'Organización / ONG', 'Estado', 'Horas Validadas', 'Código Certificado QR'], ';');

            foreach ($postulaciones as $p) {
                $vol = isset($p->user_id) ? $usuariosById->get($p->user_id) : null;
                $conv = isset($p->oportunidad_id) ? $convocatoriasById->get($p->oportunidad_id) : null;

                fputcsv($file, [
                    $p->id,
                    $p->voluntario_nombre ?? $vol->name ?? 'Estudiante',
                    $p->voluntario_dni ?? $vol->dni ?? '---',
                    $p->voluntario_carrera ?? $vol->carrera ?? '',
                    $p->universidad ?? $vol->universidad ?? 'Universidad Continental',
                    $p->proyecto ?? $conv->titulo ?? 'Campaña',
                    $p->organizacion ?? $conv->ong_nombre ?? 'ONG Aliada',
                    $p->estado ?? '',
                    $p->horas_validadas ?? 0,
                    $p->codigo_certificado ?? 'En trámite',
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function obtenerIniciales($nombre)
    {
        $partes = preg_split('/\s+/', trim((string) $nombre));
        $ini = '';
        foreach (array_slice($partes, 0, 2) as $p) {
            if ($p !== '') $ini .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        return $ini ?: 'VM';
    }

    private function verificarAdmin()
    {
        $u = Auth::user();
        $r = $u->rol ?? $u->tipo_usuario ?? '';
        if (!$u || ($r !== 'admin' && $u->email !== 'admin@voluntmatch.edu.pe')) {
            abort(403, 'Acceso exclusivo para Coordinación de Proyección Social.');
        }
    }

    private function asegurarColumnasYTablas()
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'estado_verificacion')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('estado_verificacion')->default('Aprobado')->nullable();
            });
        }

        if (!Schema::hasTable('admin_logs')) {
            Schema::create('admin_logs', function (Blueprint $table) {
                $table->id();
                $table->string('admin_nombre');
                $table->string('modulo');
                $table->text('detalle');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('consultas_contacto')) {
            Schema::create('consultas_contacto', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('rol_remitente')->default('Visitante');
                $table->string('nombre');
                $table->string('correo');
                $table->string('asunto');
                $table->text('mensaje');
                $table->text('respuesta_admin')->nullable();
                $table->string('estado')->default('Pendiente');
                $table->timestamp('respondido_at')->nullable();
                $table->timestamps();
            });
        }
    }

    private function registrarLog($modulo, $detalle)
    {
        $this->asegurarColumnasYTablas();
        DB::table('admin_logs')->insert([
            'admin_nombre' => Auth::user()->name ?? 'Administrador',
            'modulo' => $modulo,
            'detalle' => $detalle,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}