<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class ChatbotController extends Controller
{
    public function preguntar(Request $request)
    {
        try {
            $pregunta = trim((string) (
                $request->input('pregunta')
                ?? $request->input('mensaje')
                ?? $request->input('message')
                ?? ''
            ));

            if ($pregunta === '') {
                return response()->json([
                    'respuesta' => 'Escribe tu consulta aquí abajo o pulsa una de las preguntas rápidas sobre VoluntMatch Cusco.',
                    'reply' => 'Escribe tu consulta aquí abajo o pulsa una de las preguntas rápidas sobre VoluntMatch Cusco.'
                ]);
            }

            $user = Auth::user();
            $rol = $user ? ($user->rol ?? $user->tipo_usuario ?? 'voluntario') : 'guest';

            // Métricas seguras verificando columnas existentes
            $campanasActivas = 0;
            if (Schema::hasTable('oportunidades')) {
                $qCamp = DB::table('oportunidades');
                if (Schema::hasColumn('oportunidades', 'estado')) {
                    $qCamp->where('estado', '!=', 'Cerrada');
                }
                $campanasActivas = $qCamp->count();
            }

            $ongsCount = 0;
            if (Schema::hasTable('users')) {
                $qOng = DB::table('users')->where(function ($q) {
                    if (Schema::hasColumn('users', 'rol')) $q->where('rol', 'organizacion');
                    if (Schema::hasColumn('users', 'tipo_usuario')) $q->orWhere('tipo_usuario', 'organizacion');
                });
                $ongsCount = $qOng->count();
            }

            // Calcular horas del voluntario de forma 100% segura
            $horasCert = 0;
            if ($user && !in_array($rol, ['organizacion', 'admin']) && Schema::hasTable('postulaciones')) {
                $hasUserId = Schema::hasColumn('postulaciones', 'user_id');
                $hasVolEmail = Schema::hasColumn('postulaciones', 'voluntario_email');
                $hasEstado = Schema::hasColumn('postulaciones', 'estado');
                $hasHorasVal = Schema::hasColumn('postulaciones', 'horas_validadas');
                $hasHoras = Schema::hasColumn('postulaciones', 'horas');

                $qPost = DB::table('postulaciones');
                if ($hasUserId || $hasVolEmail) {
                    $qPost->where(function ($q) use ($user, $hasUserId, $hasVolEmail) {
                        if ($hasUserId) $q->where('user_id', $user->id);
                        if ($hasVolEmail) $q->orWhere('voluntario_email', $user->email);
                    });
                }
                if ($hasEstado) {
                    $qPost->where('estado', 'Completada');
                }

                $filas = $qPost->get();
                foreach ($filas as $f) {
                    $hv = $hasHorasVal ? (int) ($f->horas_validadas ?? 0) : 0;
                    $h = $hasHoras ? (int) ($f->horas ?? 0) : 0;
                    $horasCert += ($hv > 0 ? $hv : $h);
                }
            }

            // Contar campañas de la ONG de forma 100% segura
            $misConvs = 0;
            if ($user && $rol === 'organizacion' && Schema::hasTable('oportunidades')) {
                $hasUserId = Schema::hasColumn('oportunidades', 'user_id');
                $hasOngNombre = Schema::hasColumn('oportunidades', 'ong_nombre');
                $qMis = DB::table('oportunidades');
                if ($hasUserId || $hasOngNombre) {
                    $qMis->where(function ($q) use ($user, $hasUserId, $hasOngNombre) {
                        if ($hasUserId) $q->where('user_id', $user->id);
                        if ($hasOngNombre) $q->orWhere('ong_nombre', $user->name);
                    });
                }
                $misConvs = $qMis->count();
            }

            $mensajeFueraDeTema = "No preguntes cosas que no estén dentro del tema. Soy **VoluntBot Cusco**, el asistente oficial de **VoluntMatch Cusco**, y solo respondo consultas sobre voluntariado universitario, ONGs en Cusco, requisitos de registro, convocatorias, donaciones y certificados QR de proyección social.";

            if (!$user) {
                $contextoRol = "Eres 'VoluntBot Cusco', asistente oficial de VoluntMatch Cusco para visitantes que aún no inician sesión. "
                    . "Datos actuales: {$ongsCount} ONGs verificadas y {$campanasActivas} campañas activas en Cusco para convalidar 100 horas de Proyección Social. "
                    . "Requisitos Voluntario: nombre completo, DNI de 8 dígitos, carrera, correo y contraseña. "
                    . "Requisitos ONG: razón social, RUC SUNAT válido de 11 dígitos (iniciado en 20 o 10), distrito en Cusco y correo institucional.";
            } elseif ($rol === 'organizacion') {
                $contextoRol = "Eres 'VoluntBot Cusco', asesor para la organización '{$user->name}' (RUC: " . ($user->ruc ?? 'Registrado') . ", {$misConvs} campañas creadas). "
                    . "Ayúdala a publicar convocatorias con vacantes reales, evaluar expedientes de postulantes, registrar asistencias por jornada, emitir certificados oficiales con código QR y gestionar vouchers de donación.";
            } else {
                $faltan = max(0, 100 - $horasCert);
                $contextoRol = "Eres 'VoluntBot Cusco', coach académico para el estudiante '{$user->name}' (Carrera: " . ($user->carrera ?? 'Universitario') . ", Horas certificadas: {$horasCert}/100 hrs, le faltan {$faltan} hrs). "
                    . "Ayúdalo a elegir campañas en Cusco según su carrera, acumular horas, coordinar por WhatsApp con la ONG tras ser aceptado y descargar sus certificados QR o su Récord Consolidado de 100 horas.";
            }

            $reglaEstricta = "\n\nREGLA OBLIGATORIA DE DOMINIO: Solo puedes responder preguntas sobre VoluntMatch Cusco, voluntariado universitario, proyección social, ONGs, postulaciones, horas, donaciones y certificados QR. Si el usuario pregunta sobre cualquier otro tema ajeno (por ejemplo: anime, Goku, videojuegos, fútbol, cocina, chistes, programación general, tareas escolares o cultura general), NO respondas la pregunta y responde únicamente: \"{$mensajeFueraDeTema}\"";

            $apiKey = env('GEMINI_API_KEY');
            if ($apiKey) {
                try {
                    $prompt = $contextoRol . $reglaEstricta . "\nResponde en español, con tono cercano, claro y estructurado en máximo 3 párrafos cortos.\n\nConsulta del usuario: " . $pregunta;
                    $response = Http::timeout(12)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                        'contents' => [[
                            'parts' => [['text' => $prompt]]
                        ]]
                    ]);

                    if ($response->successful()) {
                        $texto = data_get($response->json(), 'candidates.0.content.parts.0.text');
                        if ($texto) {
                            $limpio = trim($texto);
                            return response()->json(['respuesta' => $limpio, 'reply' => $limpio]);
                        }
                    }
                } catch (\Throwable $e) {
                }
            }

            // Respaldo inteligente local con detección estricta de tema
            $q = mb_strtolower($pregunta);
            $palabrasPermitidas = [
                'volunt', 'match', 'cusco', 'ong', 'ruc', 'hora', 'certificado', 'qr',
                'campaña', 'campana', 'convocatoria', 'postul', 'cuenta', 'registr',
                'requisito', 'estudiante', 'universidad', 'continental', 'proyeccion',
                'proyección', 'donaci', 'voucher', 'whatsapp', 'panel', 'dni', 'carrera',
                'sistema', 'plataforma', 'funciona', 'sirve', 'ayuda', 'hola', 'buenos',
                'buenas', 'gracias', 'convalid', 'record', 'récord', 'constancia', 'asistencia',
                'public', 'valid', 'revis', 'descarg', 'contact', 'falta', '100'
            ];

            $esDelTema = false;
            foreach ($palabrasPermitidas as $palabra) {
                if (str_contains($q, $palabra)) {
                    $esDelTema = true;
                    break;
                }
            }

            if (!$esDelTema) {
                return response()->json(['respuesta' => $mensajeFueraDeTema, 'reply' => $mensajeFueraDeTema]);
            }

            if (!$user) {
                if (str_contains($q, 'sirve') || str_contains($q, 'sistema') || str_contains($q, 'plataforma') || str_contains($q, 'funciona')) {
                    $resp = "**VoluntMatch Cusco** es la plataforma universitaria que conecta estudiantes con **{$ongsCount} ONGs verificadas** y **{$campanasActivas} campañas activas** en Cusco.\n\nTe permite postular a proyectos reales, registrar tus asistencias y obtener **certificados digitales con código QR** válidos para convalidar tus **100 horas de Proyección Social**.";
                } elseif (str_contains($q, 'voluntario') || str_contains($q, 'estudiante') || str_contains($q, 'cuenta') || str_contains($q, 'requisito') || str_contains($q, 'registr')) {
                    $resp = "Para crear tu cuenta de **Voluntario Universitario**, haz clic en **Registrarse → Soy Estudiante** arriba a la derecha.\n\nSolo te pediremos: **Nombre completo, DNI (8 dígitos), Carrera profesional, Correo y Contraseña**. ¡Es 100% gratuito y al instante!";
                } elseif (str_contains($q, 'ong') || str_contains($q, 'ruc')) {
                    $resp = "Para registrar tu **ONG o Entidad Aliada**, selecciona **Registrarse → Soy Organización / ONG**.\n\nRequisitos: **Nombre o Razón Social, RUC SUNAT válido de 11 dígitos** (iniciado en **20** para persona jurídica o **10** para persona natural), **Distrito en Cusco** y **Correo institucional**.";
                } elseif (str_contains($q, 'certificado') || str_contains($q, 'qr') || str_contains($q, 'convalid')) {
                    $resp = "Sí, todos los certificados emitidos en VoluntMatch Cusco incluyen un **código QR único verificable 24/7** y permiten descargar el **Récord Consolidado de 100 Horas** para presentar en tu facultad.";
                } else {
                    $resp = "¡Hola! Bienvenido a **VoluntMatch Cusco**. Actualmente tenemos **{$campanasActivas} campañas activas** con **{$ongsCount} ONGs aliadas**.\n\nConsúltame sobre cómo crear tu cuenta de **Voluntario** o **ONG**, postular a convocatorias o descargar tus **certificados QR**.";
                }
            } elseif ($rol === 'organizacion') {
                if (str_contains($q, 'public') || str_contains($q, 'campaña') || str_contains($q, 'campana') || str_contains($q, 'convocatoria')) {
                    $resp = "Para publicar una convocatoria, entra a **Mi Panel** y pulsa **Publicar Nueva Campaña**. Define el título, distrito en Cusco, horas que otorga, vacantes disponibles y usa el botón **Mejorar con IA** para redactar una descripción atractiva.";
                } elseif (str_contains($q, 'valid') || str_contains($q, 'hora') || str_contains($q, 'certificado') || str_contains($q, 'qr') || str_contains($q, 'asistencia')) {
                    $resp = "En **Mi Panel ONG**, abre tu campaña, ubica al voluntario aceptado y usa **+ Horas** para registrar cada jornada asistida.\n\nAl completar la meta de horas, pulsa **Dar Certificado** para generar automáticamente su diploma oficial con **código QR verificable**.";
                } elseif (str_contains($q, 'donaci') || str_contains($q, 'voucher') || str_contains($q, 'revis')) {
                    $resp = "En **Mi Panel ONG**, cada campaña muestra los **aportes solidarios y vouchers** recibidos por los donantes, junto con el módulo de **Rendición de Gastos** para mantener total transparencia.";
                } else {
                    $resp = "Hola **{$user->name}**, tienes **{$misConvs} campañas** registradas. Desde **Mi Panel** puedes evaluar postulantes, sumar horas por jornada, emitir certificados QR y revisar los vouchers de donaciones.";
                }
            } else {
                $faltan = max(0, 100 - $horasCert);

                if (str_contains($q, 'hora') || str_contains($q, 'falta') || str_contains($q, '100')) {
                    $resp = "Llevas **{$horasCert} horas certificadas** de tu meta de **100 horas** de Proyección Social.\n\n" . ($faltan > 0 ? "Te faltan **{$faltan} horas**; puedes completarlas postulando a las **{$campanasActivas} campañas activas** en **Oportunidades**." : "¡Felicidades, ya completaste las 100 horas! Descarga tu **Récord Consolidado** desde **Mi Panel**.");
                } elseif (str_contains($q, 'certificado') || str_contains($q, 'descarg') || str_contains($q, 'qr') || str_contains($q, 'record') || str_contains($q, 'récord') || str_contains($q, 'constancia')) {
                    $resp = "Cuando la ONG marque tu participación como **Completada**, aparecerá el botón verde **Ver Certificado** en **Mi Panel** y también podrás descargar tu **Récord Consolidado de 100 Horas** con código QR.";
                } elseif (str_contains($q, 'whatsapp') || str_contains($q, 'contact') || str_contains($q, 'ong')) {
                    $resp = "Una vez que la ONG acepte tu postulación (**Estado: Aceptado**), se habilitará automáticamente el botón directo de **WhatsApp de la ONG** en tu tabla de postulaciones dentro de **Mi Panel** para coordinar tus horarios.";
                } else {
                    $resp = "Hola **{$user->name}**, llevas **{$horasCert}/100 hrs** certificadas. Puedes usar el botón **Recomendar Campaña IA** en **Mi Panel** para encontrar convocatorias en Cusco ideales para tu carrera.";
                }
            }

            return response()->json(['respuesta' => $resp, 'reply' => $resp]);
        } catch (\Throwable $e) {
            return response()->json([
                'respuesta' => 'Ocurrió un inconveniente temporal al consultar tus datos. Por favor, intenta nuevamente en unos segundos.',
                'reply' => 'Ocurrió un inconveniente temporal al consultar tus datos. Por favor, intenta nuevamente en unos segundos.'
            ]);
        }
    }

    public function mejorarDescripcion(Request $request)
    {
        $titulo = trim((string) $request->input('titulo', 'Campaña de Voluntariado'));
        $descripcion = trim((string) $request->input('descripcion', ''));
        $categoria = trim((string) $request->input('categoria', 'Impacto Social'));

        $apiKey = env('GEMINI_API_KEY');
        if ($apiKey) {
            try {
                $prompt = "Mejora y redacta de forma profesional, inspiradora y clara la descripción de una convocatoria de voluntariado universitario en Cusco.\nTítulo: {$titulo}\nCategoría: {$categoria}\nBorrador: {$descripcion}\nDevuelve solo el texto mejorado en 2 párrafos breves.";
                $response = Http::timeout(12)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);
                if ($response->successful()) {
                    $texto = data_get($response->json(), 'candidates.0.content.parts.0.text');
                    if ($texto) {
                        return response()->json(['descripcion_mejorada' => trim($texto)]);
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        $mejorada = "Únete a nuestra iniciativa '{$titulo}' en la categoría de {$categoria} en Cusco. Buscamos estudiantes universitarios comprometidos con el desarrollo comunitario para ejecutar actividades de campo de alto impacto social.\n\nAl completar tu participación recibirás validación oficial de horas de Proyección Social y certificado institucional con código QR verificable.";
        return response()->json(['descripcion_mejorada' => $mejorada]);
    }

    public function recomendarCampanas(Request $request)
    {
        $user = Auth::user();
        $carrera = $user->carrera ?? 'Estudios Generales';
        $campanas = Schema::hasTable('oportunidades')
            ? DB::table('oportunidades')->where('estado', '!=', 'Cerrada')->orderByDesc('id')->limit(6)->get()
            : collect();

        $elegida = $campanas->first();
        if (!$elegida) {
            return response()->json([
                'campana_id' => null,
                'campana_titulo' => 'Convocatorias Cusco',
                'recomendacion' => 'Actualmente todas las campañas están completas. Revisa nuevamente pronto para nuevas convocatorias en Cusco.'
            ]);
        }

        return response()->json([
            'campana_id' => $elegida->id,
            'campana_titulo' => $elegida->titulo,
            'recomendacion' => "Por tu perfil en {$carrera}, te recomendamos '{$elegida->titulo}' en {$elegida->distrito}, que otorga {$elegida->horas} horas certificadas de Proyección Social."
        ]);
    }

    public function adminCopiloto(Request $request)
    {
        $pregunta = trim((string) ($request->input('pregunta') ?? $request->input('mensaje') ?? ''));
        if ($pregunta === '') {
            return response()->json(['respuesta' => 'Indícame qué reporte, padrón o acción administrativa deseas consultar.']);
        }

        $usuarios = Schema::hasTable('users') ? DB::table('users')->get() : collect();
        $voluntarios = $usuarios->filter(fn($u) => in_array(($u->rol ?? $u->tipo_usuario ?? 'voluntario'), ['voluntario', 'estudiante']) && $u->email !== 'admin@voluntmatch.edu.pe');
        $ongs = $usuarios->filter(fn($u) => ($u->rol ?? $u->tipo_usuario ?? '') === 'organizacion');
        $campanas = Schema::hasTable('oportunidades') ? DB::table('oportunidades')->get() : collect();
        $postulaciones = Schema::hasTable('postulaciones') ? DB::table('postulaciones')->get() : collect();
        $consultas = Schema::hasTable('consultas_contacto') ? DB::table('consultas_contacto')->get() : collect();

        $totalHoras = $postulaciones->where('estado', 'Completada')->sum(fn($p) => (int) (($p->horas_validadas ?? 0) ?: ($p->horas ?? 0)));
        $certificadosCount = $postulaciones->where('estado', 'Completada')->count();
        $pendientesChat = $consultas->where('estado', 'Pendiente')->count();

        $alumnosAptos = [];
        foreach ($voluntarios as $v) {
            $hrs = $postulaciones->where('estado', 'Completada')
                ->filter(fn($p) => ($p->user_id ?? null) == $v->id || ($p->voluntario_email ?? '') === $v->email)
                ->sum(fn($p) => (int) (($p->horas_validadas ?? 0) ?: ($p->horas ?? 0)));
            if ($hrs >= 100) {
                $alumnosAptos[] = "{$v->name} ({$hrs}h)";
            }
        }

        $distritosResumen = $campanas->groupBy(fn($c) => ($c->distrito ?? '') ?: 'Cusco Centro')->map->count();
        $distritosTexto = $distritosResumen->map(fn($cnt, $d) => "{$d}: {$cnt}")->implode(', ') ?: 'Sin campañas aún';
        $aptosTexto = count($alumnosAptos) > 0 ? implode(', ', $alumnosAptos) : 'Ninguno aún';

        $apiKey = env('GEMINI_API_KEY');
        if ($apiKey) {
            try {
                $sistema = "Eres 'VoluntBot Admin', asistente ejecutivo exclusivo del Administrador de Proyección Social en VoluntMatch Cusco. Responde de manera ejecutiva, precisa y directa en español (máximo 130 palabras) usando exclusivamente estas métricas reales de la base de datos:\n"
                    . "- Estudiantes registrados: {$voluntarios->count()}\n"
                    . "- Estudiantes con >=100 horas (Aptos): " . count($alumnosAptos) . " ({$aptosTexto})\n"
                    . "- ONGs aliadas registradas: {$ongs->count()}\n"
                    . "- Campañas activas: {$campanas->count()} (Distribución: {$distritosTexto})\n"
                    . "- Horas certificadas totales: {$totalHoras} hrs en {$certificadosCount} certificados QR\n"
                    . "- Mensajes pendientes en Soporte: {$pendientesChat}\n";

                $response = Http::timeout(12)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => [[
                        'parts' => [['text' => $sistema . "\n\nConsulta del Administrador: " . $pregunta]]
                    ]]
                ]);

                if ($response->successful()) {
                    $texto = data_get($response->json(), 'candidates.0.content.parts.0.text');
                    if ($texto) {
                        return response()->json(['respuesta' => trim($texto)]);
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        $q = mb_strtolower($pregunta);
        if (str_contains($q, '100') || str_contains($q, 'apto') || str_contains($q, 'alumno') || str_contains($q, 'estudiante')) {
            $resp = "Actualmente tienes **{$voluntarios->count()} estudiantes** en el padrón y **" . count($alumnosAptos) . " aptos con ≥100 horas** ({$aptosTexto}).";
        } elseif (str_contains($q, 'soporte') || str_contains($q, 'mensaje') || str_contains($q, 'chat') || str_contains($q, 'pendiente')) {
            $resp = "Tienes **{$pendientesChat} mensajes pendientes** en la Bandeja de Soporte Institucional.";
        } elseif (str_contains($q, 'ong') || str_contains($q, 'ruc') || str_contains($q, 'distrito')) {
            $resp = "Hay **{$ongs->count()} ONGs aliadas** registradas y **{$campanas->count()} campañas** distribuidas así: {$distritosTexto}.";
        } elseif (str_contains($q, 'comunicado') || str_contains($q, 'redact')) {
            $resp = "Borrador sugerido: *\"Estimados estudiantes y ONGs aliadas de Cusco: les recordamos verificar sus asistencias y certificados QR antes del cierre de ciclo de Proyección Social. Coordinación Institucional UC.\"*";
        } else {
            $resp = "Resumen Ejecutivo en Vivo: **{$voluntarios->count()} estudiantes**, **{$ongs->count()} ONGs RUC**, **{$totalHoras} horas certificadas** ({$certificadosCount} diplomas QR) y **{$pendientesChat} consultas pendientes** de soporte.";
        }

        return response()->json(['respuesta' => $resp]);
    }

    public function adminSugerirRespuesta(Request $request)
    {
        $nombre = trim((string) $request->input('nombre', 'Usuario'));
        $rol = trim((string) $request->input('rol', 'Voluntario'));
        $ultimoMensaje = trim((string) $request->input('mensaje', ''));

        if ($ultimoMensaje === '') {
            return response()->json([
                'sugerencia' => "Hola {$nombre}, te saludamos desde Coordinación de Proyección Social UC en Cusco. ¿En qué podemos apoyarte hoy?"
            ]);
        }

        $apiKey = env('GEMINI_API_KEY');
        if ($apiKey) {
            try {
                $prompt = "Redacta una respuesta institucional breve, cordial y profesional (máximo 2 oraciones) como Coordinador Administrador de VoluntMatch Cusco para responder a este mensaje de {$nombre} ({$rol}): \"{$ultimoMensaje}\". Devuelve solo el texto listo para enviar.";
                $response = Http::timeout(10)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);
                if ($response->successful()) {
                    $texto = data_get($response->json(), 'candidates.0.content.parts.0.text');
                    if ($texto) {
                        return response()->json(['sugerencia' => trim($texto)]);
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        $m = mb_strtolower($ultimoMensaje);
        if (str_contains($m, 'hora') || str_contains($m, 'certificado')) {
            $sugerencia = "Hola {$nombre}, hemos revisado tu consulta sobre validación de horas y certificados QR. Tu registro se encuentra en verificación por Coordinación UC y podrás descargarlo directamente desde tu panel.";
        } elseif (str_contains($m, 'ruc') || str_contains($m, 'ong') || str_contains($m, 'campaña')) {
            $sugerencia = "Estimado(a) {$nombre}, gracias por comunicarte con Coordinación Institucional Cusco. Hemos verificado el estado de tu organización y ya puedes gestionar tus convocatorias desde tu panel.";
        } else {
            $sugerencia = "Hola {$nombre}, gracias por escribir a Coordinación de Proyección Social UC. Ya estamos atendiendo tu solicitud y te confirmamos su actualización en tu panel.";
        }

        return response()->json(['sugerencia' => $sugerencia]);
    }
}