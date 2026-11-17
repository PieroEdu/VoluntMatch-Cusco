@extends('layouts.app')

@section('content')
@php
    $metaHoras = 100;
    $porcentajeMeta = min(100, round(($totalHorasCompletadas / $metaHoras) * 100));
    $horasRestantes = max(0, $metaHoras - $totalHorasCompletadas);
    $historialDonaciones = \Illuminate\Support\Facades\Schema::hasTable('donaciones')
        ? \Illuminate\Support\Facades\DB::table('donaciones')
            ->leftJoin('oportunidades', 'donaciones.oportunidad_id', '=', 'oportunidades.id')
            ->where('donaciones.user_id', $user->id)
            ->select('donaciones.*', 'oportunidades.titulo as campana_titulo', 'oportunidades.ong_nombre')
            ->orderByDesc('donaciones.id')
            ->get()
        : collect();
@endphp

<div class="relative min-h-[85vh] py-10 px-6 max-w-7xl mx-auto space-y-8 z-10">
    
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-brand-500/10 rounded-full blur-[160px]"></div>
    </div>

    <!-- CABECERA DEL VOLUNTARIO -->
    <div class="p-8 rounded-3xl glass-card border border-brand-500/30 flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
        <div class="space-y-2">
            <span class="px-3 py-1 rounded-full text-xs font-black bg-brand-500/20 text-brand-400 border border-brand-500/30 inline-block uppercase tracking-wider">
                <i class="bi bi-person-badge-fill me-1"></i> Panel de Voluntario Universitario
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">{{ $user->name }}</h1>
            <p class="text-slate-400 text-xs sm:text-sm">
                DNI: <strong class="text-slate-200">{{ $user->dni ?? '---' }}</strong> • Carrera: <strong class="text-slate-200">{{ $user->carrera ?? 'Estudiante' }}</strong> • Tel: <strong class="text-slate-200">{{ $user->telefono ?? 'Sin teléfono' }}</strong>
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" onclick="abrirModalEditarPerfilVoluntario()" class="px-4 py-3.5 rounded-2xl bg-slate-900 border border-white/15 hover:border-brand-400/50 text-slate-200 hover:text-brand-300 font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-1.5">
                <i class="bi bi-person-gear text-sm"></i> Editar Perfil
            </button>
            <button type="button" id="btn-recomendar-ia" onclick="recomendarCampanaConIA()" class="px-5 py-3.5 rounded-2xl bg-gradient-to-r from-purple-500 to-cyanNeon-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-purple-500/20 hover:scale-105 transition-all flex items-center gap-1.5">
                <i class="bi bi-stars text-sm"></i> Recomendar Campaña IA
            </button>
            <a href="{{ route('constancia.consolidada') }}" target="_blank" class="px-5 py-3.5 rounded-2xl bg-slate-900 border border-amberGlow-500/40 text-amberGlow-400 font-black text-xs uppercase tracking-wider hover:bg-amberGlow-500 hover:text-slate-950 transition-all">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Récord Consolidado
            </a>
            <a href="{{ route('oportunidades') }}" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-brand-500/20 hover:scale-105 transition-all">
                <i class="bi bi-search me-1"></i> Explorar Campañas
            </a>
        </div>
    </div>

    <!-- BARRA GAMIFICADA DE PROGRESO HACIA LAS 100 HORAS -->
    <div class="p-6 sm:p-8 rounded-3xl glass-card border border-emerald-500/30 space-y-4 relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-base sm:text-lg font-black text-white flex items-center gap-2">
                    <i class="bi bi-trophy-fill text-amberGlow-400"></i> Ruta de Convalidación Universitaria (100 Horas)
                </h2>
                <p class="text-xs text-slate-400">
                    @if($horasRestantes > 0)
                        Te faltan <strong class="text-emerald-400">{{ $horasRestantes }} horas certificadas</strong> para completar tu requisito de Proyección Social.
                    @else
                        <strong class="text-emerald-400">¡Meta cumplida!</strong> Ya puedes descargar tu Récord Consolidado para convalidación académica.
                    @endif
                </p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-emerald-400">{{ $totalHorasCompletadas }}</span>
                <span class="text-xs font-bold text-slate-400">/ 100 hrs ({{ $porcentajeMeta }}%)</span>
            </div>
        </div>

        <div class="w-full h-4 rounded-full bg-slate-950 border border-slate-800 p-0.5 overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-cyanNeon-400 via-brand-500 to-emerald-400 transition-all duration-700" style="width: {{ $porcentajeMeta }}%"></div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-[11px]">
            <div class="p-2.5 rounded-xl border {{ $totalHorasCompletadas >= 25 ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300' : 'bg-slate-950/60 border-slate-800 text-slate-500' }} flex items-center gap-2 font-bold">
                <i class="bi {{ $totalHorasCompletadas >= 25 ? 'bi-check-circle-fill text-emerald-400' : 'bi-lock-fill' }}"></i> 25h • Iniciado
            </div>
            <div class="p-2.5 rounded-xl border {{ $totalHorasCompletadas >= 50 ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300' : 'bg-slate-950/60 border-slate-800 text-slate-500' }} flex items-center gap-2 font-bold">
                <i class="bi {{ $totalHorasCompletadas >= 50 ? 'bi-check-circle-fill text-emerald-400' : 'bi-lock-fill' }}"></i> 50h • Comprometido
            </div>
            <div class="p-2.5 rounded-xl border {{ $totalHorasCompletadas >= 75 ? 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300' : 'bg-slate-950/60 border-slate-800 text-slate-500' }} flex items-center gap-2 font-bold">
                <i class="bi {{ $totalHorasCompletadas >= 75 ? 'bi-check-circle-fill text-emerald-400' : 'bi-lock-fill' }}"></i> 75h • Líder Social
            </div>
            <div class="p-2.5 rounded-xl border {{ $totalHorasCompletadas >= 100 ? 'bg-amberGlow-500/20 border-amberGlow-500/50 text-amberGlow-300' : 'bg-slate-950/60 border-slate-800 text-slate-500' }} flex items-center gap-2 font-bold">
                <i class="bi {{ $totalHorasCompletadas >= 100 ? 'bi-award-fill text-amberGlow-400' : 'bi-lock-fill' }}"></i> 100h • Meta Cumplida
            </div>
        </div>
    </div>

    <!-- MÉTRICAS REALES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 relative z-10">
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-11 h-11 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center mx-auto mb-2.5 text-lg"><i class="bi bi-patch-check-fill"></i></div>
            <span class="block text-2xl font-black text-brand-400">{{ $totalHorasCompletadas }} hrs</span>
            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Horas Certificadas</span>
        </div>
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-11 h-11 rounded-2xl bg-cyanNeon-500/20 text-cyanNeon-400 flex items-center justify-center mx-auto mb-2.5 text-lg"><i class="bi bi-hourglass-split"></i></div>
            <span class="block text-2xl font-black text-cyanNeon-400">{{ $totalHorasEnProgreso }} hrs</span>
            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Horas en Progreso</span>
        </div>
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-11 h-11 rounded-2xl bg-purple-500/20 text-purple-400 flex items-center justify-center mx-auto mb-2.5 text-lg"><i class="bi bi-send-check-fill"></i></div>
            <span class="block text-2xl font-black text-purple-400">{{ $totalPostulacionesActivas }}</span>
            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Campañas Activas</span>
        </div>
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-11 h-11 rounded-2xl bg-amberGlow-500/20 text-amberGlow-400 flex items-center justify-center mx-auto mb-2.5 text-lg"><i class="bi bi-award-fill"></i></div>
            <span class="block text-2xl font-black text-amberGlow-400">{{ $totalProyectosCompletados }}</span>
            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Certificados Oficiales</span>
        </div>
    </div>

    <!-- CHAT DIRECTO CON COORDINACIÓN UC + NOTIFICACIONES -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative z-10">
        <!-- Mini Chat Soporte con el Administrador -->
        <div class="lg:col-span-6 glass-card rounded-3xl border border-cyanNeon-500/30 p-6 flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-brand-500/20 border border-brand-500/40 text-brand-300 font-black text-xs flex items-center justify-center">UC</div>
                    <div>
                        <h3 class="text-sm font-black text-white">Chat con Coordinación UC</h3>
                        <span class="text-[11px] text-emerald-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Soporte Directo</span>
                    </div>
                </div>
            </div>

            <div id="vol-chat-box" class="space-y-3 max-h-56 overflow-y-auto pr-1">
                @forelse($hiloSoporte as $msg)
                    @if($msg->mensaje !== 'Conversación iniciada por Coordinación Institucional.')
                        <div class="flex flex-col items-end space-y-0.5">
                            <span class="text-[10px] font-mono text-slate-400">Tú • {{ \Carbon\Carbon::parse($msg->created_at)->format('d/m H:i') }}</span>
                            <div class="max-w-xs px-3.5 py-2.5 rounded-2xl rounded-tr-sm bg-slate-800 text-white text-xs">{{ $msg->mensaje }}</div>
                        </div>
                    @endif
                    @if(!empty($msg->respuesta_admin))
                        <div class="flex flex-col items-start space-y-0.5">
                            <span class="text-[10px] font-mono text-brand-400">Coordinación UC</span>
                            <div class="max-w-xs px-3.5 py-2.5 rounded-2xl rounded-tl-sm bg-gradient-to-r from-brand-500/20 to-emerald-500/20 border border-brand-500/40 text-emerald-200 text-xs">
                                {{ $msg->respuesta_admin }}
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">¿Tienes alguna duda sobre tus 100 horas o certificados? Escríbenos aquí abajo.</p>
                @endforelse
            </div>

            <form onsubmit="enviarMensajeSoporteVoluntario(event)" class="flex items-center gap-2 pt-2 border-t border-slate-800">
                <input type="text" id="vol-chat-input" required placeholder="Escribe tu consulta al Administrador..." class="flex-1 px-4 py-2.5 rounded-full bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-400 focus:outline-none">
                <button type="submit" class="w-10 h-10 rounded-full bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black flex items-center justify-center shrink-0 hover:scale-105 transition-transform">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>

        <!-- Avisos y Certificados -->
        <div class="lg:col-span-6 glass-card rounded-3xl border border-slate-800 p-6 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-sm font-black text-white uppercase tracking-wider"><i class="bi bi-bell-fill text-amberGlow-400 me-2"></i>Avisos Institucionales</h3>
                <button onclick="marcarLeidas()" class="text-[11px] font-bold text-slate-400 hover:text-white">Marcar leído</button>
            </div>
            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                @forelse($notificaciones->take(5) as $notif)
                    <div class="p-3.5 rounded-2xl {{ $notif->tipo === 'certificado' ? 'bg-emerald-500/10 border border-emerald-500/40' : 'bg-slate-900/80 border border-slate-800' }} flex items-start justify-between gap-3">
                        <div class="space-y-1">
                            <span class="text-xs font-black {{ $notif->tipo === 'certificado' ? 'text-emerald-400' : 'text-white' }} block">{{ $notif->titulo }}</span>
                            <p class="text-[11px] text-slate-300 leading-relaxed">{{ $notif->mensaje }}</p>
                        </div>
                        @if($notif->tipo === 'certificado' && $notif->postulacion_id)
                            <a href="{{ route('certificado.ver', ['id' => $notif->postulacion_id]) }}" target="_blank" class="shrink-0 px-3 py-1.5 rounded-xl bg-emerald-500 text-slate-950 font-black text-[10px] uppercase">
                                Diploma
                            </a>
                        @endif
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-8">No tienes notificaciones pendientes.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- MIS POSTULACIONES, AVANCE Y CERTIFICADOS OFICIALES -->
    <div class="glass-card rounded-3xl border border-slate-800 p-6 space-y-4 relative z-10">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-lg font-black text-white">Mis Campañas, Avance de Horas y Certificados</h3>
                <p class="text-xs text-slate-400">Visualiza tus sesiones registradas por cada ONG y descarga tus diplomas oficiales</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-400 border border-brand-500/30">{{ $postulaciones->count() }} Total</span>
        </div>

        @if($postulaciones->isEmpty())
            <div class="py-12 text-center space-y-3">
                <i class="bi bi-compass text-4xl text-slate-600"></i>
                <p class="text-sm font-bold text-slate-300">Aún no tienes postulaciones registradas</p>
                <div class="pt-2">
                    <a href="{{ route('oportunidades') }}" class="inline-block px-5 py-2.5 rounded-xl bg-brand-500/20 text-brand-400 border border-brand-500/30 text-xs font-bold hover:bg-brand-500 hover:text-slate-950 transition-all">Ver Oportunidades</a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="text-[11px] font-black uppercase text-slate-400 border-b border-slate-800 bg-slate-900/50">
                        <tr>
                            <th class="p-3">Campaña / Organización</th>
                            <th class="p-3">Avance de Horas</th>
                            <th class="p-3">Bitácora y Coordinación</th>
                            <th class="p-3">Estado</th>
                            <th class="p-3 text-right">Certificado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($postulaciones as $post)
                        @php
                            $porcentaje = $post->horas > 0 ? min(100, round(($post->horas_validadas / $post->horas) * 100)) : 0;
                            $sesiones = $bitacoras->get($post->id, collect());
                            $telOng = preg_replace('/[^0-9]/', '', $post->ong_telefono ?? '');
                            if ($telOng && !str_starts_with($telOng, '51')) { $telOng = '51' . $telOng; }
                        @endphp
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="p-3">
                                <div class="font-bold text-white">{{ $post->proyecto }}</div>
                                <div class="text-[11px] text-brand-400 font-semibold">{{ $post->organizacion }}</div>
                            </td>
                            <td class="p-3 w-52">
                                <div class="flex justify-between text-[11px] font-bold mb-1">
                                    <span class="text-white">{{ $post->horas_validadas }} / {{ $post->horas }} hrs</span>
                                    <span class="text-brand-400">{{ $porcentaje }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-brand-500 to-emerald-400" style="width: {{ $porcentaje }}%"></div>
                                </div>
                            </td>
                            <td class="p-3 space-y-1">
                                @if($sesiones->isNotEmpty())
                                    <button onclick='verBitacoraVoluntario("{{ addslashes($post->proyecto) }}", @json($sesiones))' class="block text-[11px] font-bold text-cyanNeon-400 hover:underline">
                                        <i class="bi bi-journal-check me-1"></i> Ver {{ $sesiones->count() }} sesiones asistidas
                                    </button>
                                @endif
                                @if($post->estado === 'Aprobada' && $telOng)
                                    <a href="https://wa.me/{{ $telOng }}?text={{ urlencode('Hola, soy ' . $user->name . ', voluntario aceptado en la campaña: ' . $post->proyecto) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 hover:underline">
                                        <i class="bi bi-whatsapp"></i> WhatsApp de la ONG
                                    </a>
                                @endif
                            </td>
                            <td class="p-3">
                                @if($post->estado === 'En revisión')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amberGlow-500/20 text-amberGlow-400 border border-amberGlow-500/30">En revisión</span>
                                @elseif($post->estado === 'Aprobada')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-cyanNeon-500/20 text-cyanNeon-400 border border-cyanNeon-500/30">Aceptado (En curso)</span>
                                @elseif($post->estado === 'Completada')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Completada</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-400 border border-rose-500/30">{{ $post->estado }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                @if($post->estado === 'Completada')
                                    <a href="{{ route('certificado.ver', ['id' => $post->id]) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-[11px] hover:scale-105 transition-transform shadow">
                                        <i class="bi bi-award-fill"></i> Ver Certificado
                                    </a>
                                @else
                                    <span class="text-[11px] text-slate-500">Al finalizar campaña</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    async function enviarMensajeSoporteVoluntario(e) {
        e.preventDefault();
        const input = document.getElementById('vol-chat-input');
        const mensaje = input.value.trim();
        if (!mensaje) return;

        const res = await fetch("{{ route('contacto.enviar') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                asunto: 'Consulta directa desde Panel Voluntario',
                mensaje
            })
        });
        const data = await res.json();
        if (data.ok) {
            const box = document.getElementById('vol-chat-box');
            box.innerHTML += `
                <div class="flex flex-col items-end space-y-0.5">
                    <span class="text-[10px] font-mono text-slate-400">Tú • Ahora</span>
                    <div class="max-w-xs px-3.5 py-2.5 rounded-2xl rounded-tr-sm bg-slate-800 text-white text-xs">${mensaje}</div>
                </div>
            `;
            box.scrollTop = box.scrollHeight;
            input.value = '';
        }
    }

    function abrirModalEditarPerfilVoluntario() {
        Swal.fire({
            title: 'Editar Perfil de Voluntario',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nombre Completo *</label>
                        <input type="text" id="perf-vol-name" value="{{ addslashes($user->name) }}" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Teléfono / WhatsApp</label>
                            <input type="text" id="perf-vol-tel" value="{{ addslashes($user->telefono ?? '') }}" placeholder="984000000" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Distrito Cusco</label>
                            <input type="text" id="perf-vol-dist" value="{{ addslashes($user->distrito ?? 'Cusco') }}" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Escuela Profesional / Carrera</label>
                        <input type="text" id="perf-vol-carrera" value="{{ addslashes($user->carrera ?? '') }}" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nueva Contraseña (Opcional)</label>
                        <input type="password" id="perf-vol-pass" placeholder="Dejar vacío para conservar actual" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar Cambios',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981',
            background: '#0f172a',
            color: '#fff',
            preConfirm: () => {
                return fetch("{{ route('perfil.actualizar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: document.getElementById('perf-vol-name').value.trim(),
                        telefono: document.getElementById('perf-vol-tel').value.trim(),
                        distrito: document.getElementById('perf-vol-dist').value.trim(),
                        carrera: document.getElementById('perf-vol-carrera').value.trim(),
                        password: document.getElementById('perf-vol-pass').value
                    })
                }).then(r => r.json());
            }
        }).then(res => {
            if (res.isConfirmed && res.value.ok) {
                Swal.fire({ title: '¡Perfil Actualizado!', text: res.value.message, icon: 'success', background: '#0f172a', color: '#fff' })
                    .then(() => window.location.reload());
            }
        });
    }

    async function recomendarCampanaConIA() {
        const btn = document.getElementById('btn-recomendar-ia');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Analizando...';

        try {
            const res = await fetch("{{ route('ia.recomendar-campanas') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            Swal.fire({
                title: 'Match Académico con IA',
                html: `<div class="text-left space-y-2 pt-2"><strong class="text-sm text-brand-400 block">${data.campana_titulo || 'Convocatoria Cusco'}</strong><p class="text-xs text-slate-200">${data.recomendacion}</p></div>`,
                showCancelButton: true,
                confirmButtonText: 'Ir a Postular →',
                cancelButtonText: 'Cerrar',
                confirmButtonColor: '#10b981',
                background: '#0f172a',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed && data.campana_id) {
                    window.location.href = `{{ route('oportunidades') }}?open_id=${data.campana_id}`;
                }
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-stars text-sm"></i> Recomendar Campaña IA';
        }
    }

    function marcarLeidas() {
        fetch('{{ route("notificaciones.leidas") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
    }

    function verBitacoraVoluntario(proyecto, sesiones) {
        const lista = sesiones.map(s => `
            <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 flex justify-between items-center text-xs">
                <div class="text-left"><span class="font-bold text-white block">${s.actividad}</span><span class="text-[11px] text-slate-400">${s.fecha}</span></div>
                <span class="px-2.5 py-1 rounded-lg bg-brand-500/20 text-brand-400 font-black">+${s.horas_registradas} hrs</span>
            </div>
        `).join('');
        Swal.fire({ title: `Sesiones en ${proyecto}`, html: `<div class="space-y-2 pt-2 max-h-72 overflow-y-auto">${lista}</div>`, background: '#0f172a', color: '#fff', confirmButtonColor: '#10b981' });
    }
</script>
@endsection