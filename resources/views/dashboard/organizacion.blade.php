@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] py-10 px-6 max-w-7xl mx-auto space-y-8 z-10">
    
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-amberGlow-500/10 rounded-full blur-[160px]"></div>
    </div>

    <!-- CABECERA INSTITUCIONAL -->
    <div class="p-8 rounded-3xl glass-card border border-amberGlow-500/30 flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
        <div class="space-y-2">
            <span class="px-3 py-1 rounded-full text-xs font-black bg-amberGlow-500/20 text-amberGlow-400 border border-amberGlow-500/30 inline-block uppercase tracking-wider">
                <i class="bi bi-building-check me-1"></i> Panel Institucional ONG
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">{{ $user->name }}</h1>
            <p class="text-slate-400 text-xs sm:text-sm">
                RUC: <strong class="text-slate-200">{{ $user->ruc ?? 'Sin RUC' }}</strong> • Sede: <strong class="text-slate-200">{{ $user->distrito ?? 'Cusco Centro' }}</strong> • Tel: <strong class="text-slate-200">{{ $user->telefono ?? 'Sin teléfono' }}</strong>
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button type="button" onclick="abrirModalEditarPerfilOng()" class="px-4 py-3.5 rounded-2xl bg-slate-900 border border-white/15 hover:border-amberGlow-400/50 text-slate-200 hover:text-amberGlow-300 font-bold text-xs uppercase tracking-wider transition-all inline-flex items-center gap-1.5">
                <i class="bi bi-building-gear text-base"></i> Editar Perfil
            </button>
            <a href="{{ route('ong.campana.crear') }}" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amberGlow-400 via-amber-500 to-brand-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amberGlow-500/20 hover:scale-105 transition-all whitespace-nowrap inline-flex items-center gap-2">
                <i class="bi bi-plus-circle-fill text-base"></i> Publicar Nueva Campaña
            </a>
        </div>
    </div>

    <!-- MÉTRICAS REALES -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-amberGlow-500/20 text-amberGlow-400 flex items-center justify-center mx-auto mb-3 text-xl"><i class="bi bi-megaphone-fill"></i></div>
            <span class="block text-3xl font-black text-amberGlow-400 mb-1">{{ $totalConvocatorias }}</span>
            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Campañas Publicadas</span>
        </div>
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-cyanNeon-500/20 text-cyanNeon-400 flex items-center justify-center mx-auto mb-3 text-xl"><i class="bi bi-people-fill"></i></div>
            <span class="block text-3xl font-black text-cyanNeon-400 mb-1">{{ $solicitudesPendientes->count() }}</span>
            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Postulantes por Evaluar</span>
        </div>
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center mx-auto mb-3 text-xl"><i class="bi bi-award-fill"></i></div>
            <span class="block text-3xl font-black text-brand-400 mb-1">{{ $horasOtorgadas }} hrs</span>
            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Horas Certificadas</span>
        </div>
    </div>

    <!-- CHAT DIRECTO CON COORDINACIÓN UC + AVISOS PARA LA ONG -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 relative z-10">
        <div class="lg:col-span-7 glass-card rounded-3xl border border-amberGlow-500/30 p-6 flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-amberGlow-500/20 border border-amberGlow-500/40 text-amberGlow-300 font-black text-xs flex items-center justify-center">UC</div>
                    <div>
                        <h3 class="text-sm font-black text-white">Chat Institucional con Coordinación UC</h3>
                        <span class="text-[11px] text-emerald-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Canal Directo ONG</span>
                    </div>
                </div>
            </div>

            <div id="ong-chat-box" class="space-y-3 max-h-52 overflow-y-auto pr-1">
                @forelse($hiloSoporte as $msg)
                    @if($msg->mensaje !== 'Conversación iniciada por Coordinación Institucional.')
                        <div class="flex flex-col items-end space-y-0.5">
                            <span class="text-[10px] font-mono text-slate-400">Tu ONG • {{ \Carbon\Carbon::parse($msg->created_at)->format('d/m H:i') }}</span>
                            <div class="max-w-sm px-3.5 py-2.5 rounded-2xl rounded-tr-sm bg-slate-800 text-white text-xs">{{ $msg->mensaje }}</div>
                        </div>
                    @endif
                    @if(!empty($msg->respuesta_admin))
                        <div class="flex flex-col items-start space-y-0.5">
                            <span class="text-[10px] font-mono text-amberGlow-400">Coordinación UC</span>
                            <div class="max-w-sm px-3.5 py-2.5 rounded-2xl rounded-tl-sm bg-amberGlow-500/15 border border-amberGlow-500/40 text-amberGlow-100 text-xs">
                                {{ $msg->respuesta_admin }}
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">¿Necesitas coordinar convenios, validación RUC o campañas? Escribe aquí al Administrador.</p>
                @endforelse
            </div>

            <form onsubmit="enviarMensajeSoporteOng(event)" class="flex items-center gap-2 pt-2 border-t border-slate-800">
                <input type="text" id="ong-chat-input" required placeholder="Escribir mensaje a Coordinación UC..." class="flex-1 px-4 py-2.5 rounded-full bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
                <button type="submit" class="w-10 h-10 rounded-full bg-amberGlow-400 text-slate-950 font-black flex items-center justify-center shrink-0 hover:scale-105 transition-transform">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>

        <div class="lg:col-span-5 glass-card rounded-3xl border border-slate-800 p-6 space-y-3">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-sm font-black text-white uppercase tracking-wider"><i class="bi bi-bell-fill text-purple-400 me-2"></i>Comunicados Recientes</h3>
            </div>
            <div class="space-y-2.5 max-h-56 overflow-y-auto pr-1">
                @forelse($notificaciones->take(4) as $notif)
                    <div class="p-3 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-1">
                        <span class="text-xs font-black text-purple-300 block">{{ $notif->titulo }}</span>
                        <p class="text-[11px] text-slate-300 leading-relaxed">{{ $notif->mensaje }}</p>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-8">Sin comunicados nuevos.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- POSTULANTES PENDIENTES DE EVALUACIÓN -->
    <div class="glass-card rounded-3xl border border-cyanNeon-500/30 p-6 space-y-4 relative z-10">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-lg font-black text-white"><i class="bi bi-person-check-fill text-cyanNeon-400 me-2"></i>Postulantes Pendientes de Evaluación</h3>
                <p class="text-xs text-slate-400">Revisa el expediente y el historial previo del estudiante antes de aceptarlo en la campaña</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-cyanNeon-500/20 text-cyanNeon-400 border border-cyanNeon-500/30">{{ $solicitudesPendientes->count() }} Pendientes</span>
        </div>

        @if($solicitudesPendientes->isEmpty())
            <div class="py-8 text-center text-xs text-slate-400">No tienes solicitudes pendientes de revisión en este momento.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-300">
                    <thead class="text-[11px] font-black uppercase text-slate-400 border-b border-slate-800 bg-slate-900/50">
                        <tr>
                            <th class="p-3">Postulante</th>
                            <th class="p-3">Universidad / Carrera</th>
                            <th class="p-3">Campaña Solicitada</th>
                            <th class="p-3 text-right">Evaluación y Decisión</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($solicitudesPendientes as $sol)
                        <tr class="hover:bg-slate-900/40 transition-colors">
                            <td class="p-3">
                                <div class="font-bold text-white">{{ $sol->voluntario_nombre }}</div>
                                <div class="text-[11px] text-slate-400">DNI: {{ $sol->voluntario_dni ?? '---' }} • Tel: {{ $sol->telefono_contacto ?? '---' }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-semibold text-slate-200">{{ $sol->universidad ?? 'Estudiante Universitario' }}</div>
                                <div class="text-[11px] text-slate-400">{{ $sol->voluntario_carrera ?? '' }} ({{ $sol->ciclo ?? 'Activo' }})</div>
                            </td>
                            <td class="p-3 text-amberGlow-400 font-bold">{{ $sol->proyecto }}</td>
                            <td class="p-3 text-right space-x-1.5">
                                <button onclick="abrirExpedientePostulante({{ $sol->id }})" class="px-3.5 py-1.5 rounded-lg bg-cyanNeon-500/20 text-cyanNeon-300 border border-cyanNeon-500/40 font-bold text-[11px] hover:bg-cyanNeon-500 hover:text-slate-950 transition-all">
                                    <i class="bi bi-folder2-open me-1"></i> Ver Expediente
                                </button>
                                <button onclick="actualizarSolicitud({{ $sol->id }}, 'Aprobada')" class="px-3 py-1.5 rounded-lg bg-brand-500 text-slate-950 font-black text-[11px] hover:opacity-90">
                                    Aceptar (-1 cupo)
                                </button>
                                <button onclick="actualizarSolicitud({{ $sol->id }}, 'Rechazada')" class="px-2.5 py-1.5 rounded-lg bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-[11px] hover:bg-rose-500 hover:text-white">
                                    Rechazar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- MIS CAMPAÑAS -->
    <div class="space-y-6 relative z-10">
        <div>
            <h2 class="text-xl font-black text-white">Mis Campañas, Voluntarios Oficiales y Aportes Solidarios</h2>
            <p class="text-xs text-slate-400">Gestiona la asistencia por jornada, emite certificados y supervisa los vouchers de donación recibidos</p>
        </div>

        @if($misConvocatorias->isEmpty())
            <div class="glass-card rounded-3xl border border-slate-800 p-10 text-center space-y-3">
                <i class="bi bi-folder2-open text-4xl text-slate-600"></i>
                <p class="text-sm font-bold text-slate-300">Aún no has publicado ninguna campaña</p>
                <a href="{{ route('ong.campana.crear') }}" class="inline-block px-5 py-2.5 rounded-xl bg-amberGlow-400 text-slate-950 font-black text-xs">Publicar Primera Campaña</a>
            </div>
        @else
            @foreach($misConvocatorias as $conv)
                @php
                    $esCerrada = ($conv->estado === 'Cerrada' || $conv->cupos_disponibles <= 0);
                    $voluntariosCampana = $voluntariosPorCampana->get($conv->id, collect());
                    $donacionesCampana = isset($donacionesPorCampana) ? $donacionesPorCampana->get($conv->id, collect()) : collect();
                @endphp
                <div class="glass-card rounded-3xl border {{ $esCerrada ? 'border-rose-500/40' : 'border-slate-800' }} overflow-hidden">
                    <div class="p-6 bg-slate-900/80 border-b border-slate-800 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <img src="{{ $conv->imagen ?? 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=600&q=80' }}" class="w-24 h-24 rounded-2xl object-cover shrink-0 border border-slate-700 {{ $esCerrada ? 'grayscale' : '' }}">
                            <div class="space-y-1.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amberGlow-500/20 text-amberGlow-400">{{ $conv->categoria }}</span>
                                    @if($esCerrada)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-500/20 text-rose-400 border border-rose-500/30">CERRADA (0/{{ $conv->cupos }} vacantes)</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">{{ $conv->cupos_disponibles }}/{{ $conv->cupos }} vacantes libres</span>
                                    @endif
                                    <span class="text-xs font-bold text-slate-400">• Meta: {{ $conv->horas }} hrs</span>
                                </div>
                                <h3 class="text-lg font-black text-white">{{ $conv->titulo }}</h3>
                                <p class="text-xs text-slate-400"><i class="bi bi-geo-alt-fill text-amberGlow-400"></i> {{ $conv->distrito }}</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 shrink-0">
                            <a href="{{ route('detalle-oportunidad', ['id' => $conv->id + 1000]) }}" class="px-3 py-2 rounded-xl bg-slate-800 text-cyanNeon-400 font-bold text-xs hover:bg-slate-700">Ver Pública</a>
                            <button onclick="toggleEstadoConv({{ $conv->id }})" class="px-3 py-2 rounded-xl bg-slate-800 text-slate-300 font-bold text-xs">{{ $esCerrada ? 'Reabrir' : 'Cerrar Vacantes' }}</button>
                            <a href="{{ route('ong.campana.editar', ['id' => $conv->id]) }}" class="px-3 py-2 rounded-xl bg-slate-800 hover:bg-amberGlow-500 hover:text-slate-950 text-amberGlow-400 font-bold text-xs">Editar</a>
                            <button onclick="eliminarConvocatoria({{ $conv->id }})" class="px-3 py-2 rounded-xl bg-rose-500/15 hover:bg-rose-500 text-rose-400 hover:text-white font-bold text-xs"><i class="bi bi-trash3-fill"></i></button>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <h4 class="text-xs font-black uppercase tracking-wider text-brand-400">Voluntarios Aceptados ({{ $voluntariosCampana->count() }})</h4>
                        @if($voluntariosCampana->isEmpty())
                            <p class="text-xs text-slate-500 italic">Aún no hay voluntarios aceptados en esta campaña.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs text-slate-300">
                                    <thead class="text-[11px] font-black uppercase text-slate-400 border-b border-slate-800 bg-slate-950/50">
                                        <tr>
                                            <th class="p-3">Voluntario</th>
                                            <th class="p-3">Progreso</th>
                                            <th class="p-3 text-right">Gestión</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/60">
                                        @foreach($voluntariosCampana as $vol)
                                            @php $porcentaje = $vol->horas > 0 ? min(100, round(($vol->horas_validadas / $vol->horas) * 100)) : 0; @endphp
                                            <tr class="hover:bg-slate-900/30">
                                                <td class="p-3">
                                                    <div class="font-bold text-white">{{ $vol->voluntario_nombre }}</div>
                                                    <div class="text-[11px] text-slate-400">DNI: {{ $vol->voluntario_dni ?? '---' }}</div>
                                                </td>
                                                <td class="p-3 w-56">
                                                    <div class="flex justify-between text-[11px] font-bold mb-1">
                                                        <span class="text-brand-400">{{ $vol->horas_validadas }} / {{ $vol->horas }} hrs</span>
                                                        <span>{{ $porcentaje }}%</span>
                                                    </div>
                                                    <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                                                        <div class="h-full bg-gradient-to-r from-brand-500 to-emerald-400" style="width: {{ $porcentaje }}%"></div>
                                                    </div>
                                                </td>
                                                <td class="p-3 text-right space-x-1">
                                                    @if($vol->estado === 'Aprobada')
                                                        <button onclick="abrirModalAvance({{ $vol->id }}, '{{ addslashes($vol->voluntario_nombre) }}')" class="px-3 py-1.5 rounded-lg bg-cyanNeon-500/20 text-cyanNeon-300 font-bold text-[11px]">+ Horas</button>
                                                        <button onclick="emitirCertificadoModal({{ $vol->id }}, '{{ addslashes($vol->voluntario_nombre) }}', {{ max($vol->horas_validadas, $vol->horas) }})" class="px-3 py-1.5 rounded-lg bg-emerald-400 text-slate-950 font-black text-[11px]">Dar Certificado</button>
                                                    @else
                                                        <a href="{{ route('certificado.ver', ['id' => $vol->id]) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold text-[11px]">Ver Certificado</a>
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
            @endforeach
        @endif
    </div>
</div>

<script>
    async function enviarMensajeSoporteOng(e) {
        e.preventDefault();
        const input = document.getElementById('ong-chat-input');
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
                asunto: 'Consulta institucional desde Panel ONG',
                mensaje
            })
        });
        const data = await res.json();
        if (data.ok) {
            const box = document.getElementById('ong-chat-box');
            box.innerHTML += `
                <div class="flex flex-col items-end space-y-0.5">
                    <span class="text-[10px] font-mono text-slate-400">Tu ONG • Ahora</span>
                    <div class="max-w-sm px-3.5 py-2.5 rounded-2xl rounded-tr-sm bg-slate-800 text-white text-xs">${mensaje}</div>
                </div>
            `;
            box.scrollTop = box.scrollHeight;
            input.value = '';
        }
    }

    function abrirModalEditarPerfilOng() {
        Swal.fire({
            title: 'Editar Perfil Institucional ONG',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nombre de la Organización *</label>
                        <input type="text" id="perf-ong-name" value="{{ addslashes($user->name) }}" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Teléfono Institucional</label>
                            <input type="text" id="perf-ong-tel" value="{{ addslashes($user->telefono ?? '') }}" placeholder="984000000" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Distrito Sede Cusco</label>
                            <input type="text" id="perf-ong-dist" value="{{ addslashes($user->distrito ?? 'Cusco Centro') }}" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nueva Contraseña (Opcional)</label>
                        <input type="password" id="perf-ong-pass" placeholder="Dejar vacío para conservar actual" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar Cambios',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#f59e0b',
            background: '#0f172a',
            color: '#fff',
            preConfirm: () => {
                return fetch("{{ route('perfil.actualizar') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        name: document.getElementById('perf-ong-name').value.trim(),
                        telefono: document.getElementById('perf-ong-tel').value.trim(),
                        distrito: document.getElementById('perf-ong-dist').value.trim(),
                        password: document.getElementById('perf-ong-pass').value
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

    function abrirExpedientePostulante(postulacionId) {
        fetch(`/postulacion/${postulacionId}/expediente`, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            const p = data.postulacion;
            Swal.fire({
                title: `Expediente: ${p.voluntario_nombre}`,
                html: `
                    <div class="text-left space-y-2 text-xs text-slate-300 pt-2">
                        <p><b>Universidad:</b> ${p.universidad || 'No especificada'}</p>
                        <p><b>Carrera:</b> ${p.voluntario_carrera || 'Estudiante'}</p>
                        <p><b>DNI:</b> ${p.voluntario_dni || '---'} • <b>Tel:</b> ${p.telefono_contacto || '---'}</p>
                        <p class="pt-2 border-t border-slate-800">${p.motivacion || 'Sin descripción.'}</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Aceptar (-1 cupo)',
                cancelButtonText: 'Cerrar',
                confirmButtonColor: '#10b981',
                background: '#0f172a',
                color: '#fff'
            }).then((res) => { if (res.isConfirmed) actualizarSolicitud(postulacionId, 'Aprobada'); });
        });
    }

    function abrirModalAvance(postulacionId, nombreVoluntario) {
        const hoy = new Date().toISOString().split('T')[0];
        Swal.fire({
            title: 'Registrar Avance / Asistencia',
            html: `
                <div class="text-left space-y-3 pt-1">
                    <p class="text-xs text-slate-400">Voluntario: <b class="text-white">${nombreVoluntario}</b></p>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" id="av-fecha" value="${hoy}" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        <input type="number" id="av-horas" value="4" min="1" max="40" class="px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs font-bold">
                    </div>
                    <input type="text" id="av-actividad" placeholder="Actividad realizada..." class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar Avance',
            confirmButtonColor: '#0ea5e9',
            background: '#0f172a',
            color: '#fff',
            preConfirm: () => fetch(`/postulacion/${postulacionId}/registrar-avance`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    fecha: document.getElementById('av-fecha').value,
                    horas_registradas: document.getElementById('av-horas').value,
                    actividad: document.getElementById('av-actividad').value.trim()
                })
            }).then(r => r.json())
        }).then(res => { if (res.isConfirmed) window.location.reload(); });
    }

    function emitirCertificadoModal(postulacionId, nombreVoluntario, horasSugeridas) {
        Swal.fire({
            title: 'Emitir Certificado Oficial',
            html: `
                <div class="text-left space-y-3 pt-1">
                    <p class="text-xs text-slate-300">Voluntario: <b>${nombreVoluntario}</b></p>
                    <input type="number" id="input-horas-cumplidas" value="${horasSugeridas}" min="1" max="500" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-emerald-500/40 text-white text-sm font-black">
                    <textarea id="input-resena-ong" rows="2" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">Excelente compromiso y trabajo en equipo.</textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            confirmButtonText: 'Emitir Certificado',
            background: '#0f172a',
            color: '#fff'
        }).then(result => {
            if (result.isConfirmed) {
                actualizarSolicitud(postulacionId, 'Completada', document.getElementById('input-horas-cumplidas').value, 5, document.getElementById('input-resena-ong').value);
            }
        });
    }

    function actualizarSolicitud(id, estado, horasValidadas = null, calificacion = 5, resenaOng = '') {
        fetch(`/postulacion/${id}/estado`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ estado, horas_validadas: horasValidadas, calificacion, resena_ong: resenaOng })
        }).then(() => window.location.reload());
    }

    function toggleEstadoConv(id) {
        fetch(`/convocatoria/${id}/toggle-estado`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(() => window.location.reload());
    }

    function eliminarConvocatoria(id) {
        Swal.fire({ title: '¿Eliminar campaña?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', background: '#0f172a', color: '#fff' })
            .then(res => {
                if (res.isConfirmed) {
                    fetch(`/eliminar-convocatoria/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                        .then(() => window.location.reload());
                }
            });
    }
</script>
@endsection