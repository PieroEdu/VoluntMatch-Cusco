@extends('layouts.app')

@section('content')
@php
    $consultasPendientesCount = $consultasContacto->where('estado', 'Pendiente')->count();
    $aptos100Count = $progresoAlumnos->where('horas_certificadas', '>=', 100)->count();
    $enProgresoCount = $progresoAlumnos->filter(fn($a) => $a->horas_certificadas > 0 && $a->horas_certificadas < 100)->count();
    $nuevosSinHorasCount = $progresoAlumnos->where('horas_certificadas', 0)->count();
    $ongsAprobadasCount = $expedienteOngs->where('estado_verificacion', 'Aprobado')->count();
    // Logo oficial del Chatbot VoluntBot
    $botAdminLogo = 'https://i.postimg.cc/ZnFw58cV/0e85b099-bb9b-4769-a4be-5d1932f26711-removebg-preview.png';
@endphp
<style>
    body > header, body > nav, #chatbot-widget, #chatbot-toggle, .chatbot-public-widget { display: none !important; }
    body > main { padding-top: 0 !important; margin-top: 0 !important; }

    @keyframes fadeSlideUp {
        from { opacity: 0; transform: translateY(16px) scale(0.985); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes sparkDraw {
        0% { stroke-dashoffset: 140; }
        100% { stroke-dashoffset: 0; }
    }
    @keyframes barBreath {
        0%, 100% { transform: scaleY(1); filter: brightness(1); }
        50% { transform: scaleY(1.16); filter: brightness(1.3); }
    }
    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 18px rgba(16, 185, 129, 0.25), 0 0 36px rgba(34, 211, 238, 0.12); }
        50% { box-shadow: 0 0 28px rgba(16, 185, 129, 0.5), 0 0 54px rgba(34, 211, 238, 0.25); }
    }
    @keyframes floatSlow {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }

    .anim-entrada { animation: fadeSlideUp 0.45s cubic-bezier(0.16, 1, 0.3, 1) both; }
    .delay-1 { animation-delay: 0.05s; }
    .delay-2 { animation-delay: 0.12s; }
    .delay-3 { animation-delay: 0.18s; }
    .delay-4 { animation-delay: 0.24s; }

    .spark-line-anim {
        stroke-dasharray: 140;
        stroke-dashoffset: 0;
        animation: sparkDraw 1.8s ease-out forwards;
        filter: drop-shadow(0 0 6px currentColor);
    }
    .bar-pulse {
        transform-origin: bottom;
        animation: barBreath 2.6s ease-in-out infinite;
    }
    .kpi-glow-card {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .kpi-glow-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px -12px rgba(16, 185, 129, 0.25);
    }
    .ai-orb-btn {
        animation: glowPulse 3s infinite ease-in-out, floatSlow 4s infinite ease-in-out;
    }

    .nav-admin-item.active {
        background: linear-gradient(90deg, rgba(16, 185, 129, 0.22), rgba(34, 211, 238, 0.08));
        border-color: rgba(16, 185, 129, 0.45);
        color: #ffffff;
        box-shadow: 0 4px 20px -4px rgba(16, 185, 129, 0.25);
    }
    .nav-admin-item.active .nav-icon { color: #34d399; transform: scale(1.1); }

    .pill-filter.active {
        background: linear-gradient(90deg, #10b981, #22d3ee);
        color: #020617;
        border-color: transparent;
        font-weight: 900;
        box-shadow: 0 4px 16px -2px rgba(16, 185, 129, 0.4);
    }
</style>

<div class="min-h-screen bg-slate-950 text-slate-200 flex flex-col lg:flex-row relative overflow-x-hidden">
    
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute -top-24 left-1/4 w-[620px] h-[360px] bg-brand-500/12 rounded-full blur-[150px] animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-[520px] h-[320px] bg-cyanNeon-500/12 rounded-full blur-[140px]"></div>
    </div>

    <!-- SIDEBAR CON LOGO OFICIAL VOLUNTMATCH -->
    <aside id="sidebar-admin" class="w-full lg:w-72 bg-slate-900/95 backdrop-blur-xl border-b lg:border-b-0 lg:border-r border-slate-800/80 flex flex-col justify-between shrink-0 z-30 lg:sticky lg:top-0 lg:h-screen">
        <div class="p-6 space-y-6 overflow-y-auto">
            <div class="flex items-center justify-between">
                <a href="{{ route('index') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-slate-950 border border-brand-500/40 p-1.5 flex items-center justify-center shadow-lg shadow-brand-500/20 group-hover:scale-105 transition-transform">
                        <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" alt="Logo VoluntMatch" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="font-black text-white tracking-tight text-base block leading-none">Volunt<span class="text-brand-400">Match</span></span>
                        <span class="text-[10px] font-mono font-bold text-cyanNeon-400 uppercase tracking-widest mt-1 block">Admin Console UC</span>
                    </div>
                </a>
                <button onclick="document.getElementById('menu-admin-links').classList.toggle('hidden')" class="lg:hidden px-3 py-2 rounded-xl bg-slate-800 text-slate-200 text-xs">
                    <i class="bi bi-list text-lg"></i>
                </button>
            </div>

            <div class="p-3.5 rounded-2xl bg-slate-950/80 border border-slate-800/90 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-500/20 text-brand-300 border border-brand-500/30 flex items-center justify-center font-black text-xs shrink-0">AD</div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-white truncate">{{ $user->name }}</p>
                    <p class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Cusco • En Línea
                    </p>
                </div>
            </div>

            <nav id="menu-admin-links" class="hidden lg:flex flex-col space-y-1.5">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 px-3 pb-1">Gobierno del Sistema</span>

                <button onclick="navegarSeccionAdmin('resumen', this)" class="nav-admin-item active w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-transparent text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                    <span class="flex items-center gap-3"><i class="bi bi-grid-1x2-fill nav-icon text-sm transition-transform"></i> Dashboard Ejecutivo</span>
                    <span class="px-2 py-0.5 rounded-md bg-brand-500/20 text-brand-300 font-mono text-[10px]">KPI</span>
                </button>

                <button onclick="navegarSeccionAdmin('alumnos', this)" class="nav-admin-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-transparent text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                    <span class="flex items-center gap-3"><i class="bi bi-mortarboard-fill nav-icon text-sm transition-transform"></i> Directorio Estudiantes</span>
                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-cyanNeon-400 font-mono text-[10px]">{{ $voluntarios->count() }}</span>
                </button>

                <button onclick="navegarSeccionAdmin('ongs', this)" class="nav-admin-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-transparent text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                    <span class="flex items-center gap-3"><i class="bi bi-buildings-fill nav-icon text-sm transition-transform"></i> Directorio ONGs (RUC)</span>
                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-amberGlow-400 font-mono text-[10px]">{{ $expedienteOngs->count() }}</span>
                </button>

                <button onclick="navegarSeccionAdmin('consultas', this)" class="nav-admin-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-transparent text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                    <span class="flex items-center gap-3"><i class="bi bi-chat-dots-fill nav-icon text-sm transition-transform"></i> Mensajes y Soporte</span>
                    <span id="sidebar-badge-soporte" class="px-2 py-0.5 rounded-md {{ $consultasPendientesCount > 0 ? 'bg-rose-500 text-white animate-pulse' : 'bg-slate-800 text-slate-300' }} font-mono text-[10px]">{{ $consultasPendientesCount > 0 ? $consultasPendientesCount : $conversacionesAgrupadas->count() }}</span>
                </button>

                <button onclick="navegarSeccionAdmin('campanas', this)" class="nav-admin-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-transparent text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                    <span class="flex items-center gap-3"><i class="bi bi-megaphone-fill nav-icon text-sm transition-transform"></i> Moderación Campañas</span>
                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-purple-300 font-mono text-[10px]">{{ $convocatorias->count() }}</span>
                </button>

                <button onclick="navegarSeccionAdmin('certificados', this)" class="nav-admin-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-transparent text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                    <span class="flex items-center gap-3"><i class="bi bi-qr-code-scan nav-icon text-sm transition-transform"></i> Certificados QR</span>
                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-emerald-400 font-mono text-[10px]">{{ $certificadosEmitidos->count() }}</span>
                </button>

                <button onclick="navegarSeccionAdmin('auditoria', this)" class="nav-admin-item w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-transparent text-xs font-bold text-slate-400 hover:text-white hover:bg-slate-800/50 transition-all">
                    <span class="flex items-center gap-3"><i class="bi bi-journal-code nav-icon text-sm transition-transform"></i> Bitácora Auditoría</span>
                    <span class="px-2 py-0.5 rounded-md bg-slate-800 text-rose-300 font-mono text-[10px]">{{ $auditoriaLogs->count() }}</span>
                </button>

                <div class="pt-3 border-t border-slate-800/80 space-y-1">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500 px-3 pb-1 block">Acciones Rápidas</span>
                    <button onclick="abrirModalConvalidar()" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-bold text-emerald-400 hover:bg-emerald-500/10 transition-all">
                        <i class="bi bi-patch-plus-fill"></i> Convalidar Horas Manual
                    </button>
                    <button onclick="abrirModalNuevaOng()" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-bold text-amberGlow-400 hover:bg-amberGlow-500/10 transition-all">
                        <i class="bi bi-building-add"></i> Registrar Nueva ONG
                    </button>
                    <button onclick="abrirModalComunicado()" class="w-full flex items-center gap-3 px-3.5 py-2 rounded-xl text-xs font-bold text-cyanNeon-400 hover:bg-cyanNeon-500/10 transition-all">
                        <i class="bi bi-broadcast"></i> Comunicado Global
                    </button>
                </div>
            </nav>
        </div>

        <div class="p-5 border-t border-slate-800/80 space-y-2">
            <a href="{{ route('oportunidades') }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-800/80 hover:bg-slate-800 text-slate-300 font-bold text-xs transition-all">
                <i class="bi bi-box-arrow-up-right"></i> Ver Portal Público
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-rose-500/15 hover:bg-rose-500 text-rose-400 hover:text-white font-bold text-xs transition-all">
                    <i class="bi bi-box-arrow-left"></i> Cerrar Sesión Admin
                </button>
            </form>
        </div>
    </aside>

    <!-- ÁREA PRINCIPAL -->
    <div class="flex-1 flex flex-col min-w-0 z-10">
        <header class="bg-slate-900/75 backdrop-blur-md border-b border-slate-800/80 px-6 lg:px-10 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sticky top-0 z-20">
            <div>
                <h1 id="topbar-titulo" class="text-lg sm:text-xl font-black text-white tracking-tight">Dashboard Ejecutivo • Proyección Social Cusco</h1>
                <p class="text-[11px] text-slate-400">Gestión integral de estudiantes, organizaciones RUC, certificaciones QR y VoluntBot Admin</p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <button onclick="abrirBuscadorGlobalAdmin()" class="px-3.5 py-2 rounded-xl bg-slate-950/90 hover:bg-slate-800 border border-slate-700/80 text-slate-300 font-bold text-xs flex items-center gap-2 transition-all">
                    <i class="bi bi-search text-cyanNeon-400"></i>
                    <span class="hidden md:inline">Buscar rápido</span>
                    <kbd class="px-1.5 py-0.5 rounded bg-slate-800 text-[10px] font-mono text-slate-400">Ctrl K</kbd>
                </button>
                <button onclick="toggleCopilotoAdmin()" class="px-3.5 py-2 rounded-xl bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/40 text-purple-200 font-bold text-xs flex items-center gap-2 transition-all">
                    <img src="{{ $botAdminLogo }}" alt="VoluntBot" class="w-4 h-4 object-contain"> VoluntBot Admin
                </button>
                <button onclick="abrirModalVerificadorQr()" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-cyanNeon-400 font-bold text-xs flex items-center gap-1.5 transition-all">
                    <i class="bi bi-qr-code-scan"></i> Validar QR
                </button>
                <a href="{{ route('admin.exportar.csv') }}" class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-400 to-brand-500 text-slate-950 font-black text-xs flex items-center gap-1.5 shadow-lg shadow-emerald-500/20 hover:scale-105 transition-transform">
                    <i class="bi bi-file-earmark-excel-fill"></i> Exportar Excel
                </a>
            </div>
        </header>

        <div class="p-6 lg:p-10 max-w-7xl w-full mx-auto space-y-8">

            <!-- SECCIÓN 1: DASHBOARD EJECUTIVO -->
            <section id="sec-admin-resumen" class="admin-section space-y-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                    <div class="anim-entrada delay-1 kpi-glow-card p-5 rounded-3xl bg-slate-900/85 border border-slate-800 hover:border-cyanNeon-500/50 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Estudiantes</span>
                            <span class="text-3xl font-black text-white counter-kpi mt-1 block" data-target="{{ $voluntarios->count() }}">0</span>
                            <span class="text-[11px] text-cyanNeon-400 font-bold mt-1 inline-flex items-center gap-1">
                                <i class="bi bi-arrow-up-right"></i> {{ $aptos100Count }} con 100h
                            </span>
                        </div>
                        <svg class="w-20 h-12 shrink-0 text-cyanNeon-400" viewBox="0 0 80 45">
                            <defs>
                                <linearGradient id="gradCyan" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#22d3ee" stop-opacity="0.4"/>
                                    <stop offset="100%" stop-color="#22d3ee" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M2 38 L18 30 L34 34 L50 18 L66 22 L78 8 L78 44 L2 44 Z" fill="url(#gradCyan)"/>
                            <path class="spark-line-anim" d="M2 38 L18 30 L34 34 L50 18 L66 22 L78 8" fill="none" stroke="#22d3ee" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="78" cy="8" r="3" fill="#22d3ee" class="animate-ping"/>
                        </svg>
                    </div>

                    <div class="anim-entrada delay-2 kpi-glow-card p-5 rounded-3xl bg-slate-900/85 border border-slate-800 hover:border-amberGlow-500/50 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ONGs Aliadas</span>
                            <span class="text-3xl font-black text-white counter-kpi mt-1 block" data-target="{{ $expedienteOngs->count() }}">0</span>
                            <span class="text-[11px] text-amberGlow-400 font-bold mt-1 inline-flex items-center gap-1">
                                <i class="bi bi-patch-check-fill"></i> {{ $ongsAprobadasCount }} aprobadas
                            </span>
                        </div>
                        <div class="flex items-end gap-1.5 h-12 shrink-0">
                            <span class="w-2 h-5 rounded-full bg-amberGlow-500/40 bar-pulse" style="animation-delay:0.1s"></span>
                            <span class="w-2 h-8 rounded-full bg-amberGlow-500/60 bar-pulse" style="animation-delay:0.3s"></span>
                            <span class="w-2 h-6 rounded-full bg-amberGlow-500/50 bar-pulse" style="animation-delay:0.5s"></span>
                            <span class="w-2 h-10 rounded-full bg-amberGlow-400/80 bar-pulse" style="animation-delay:0.7s"></span>
                            <span class="w-2 h-12 rounded-full bg-amberGlow-400 shadow-lg shadow-amberGlow-400/50 bar-pulse" style="animation-delay:0.9s"></span>
                        </div>
                    </div>

                    <div class="anim-entrada delay-3 kpi-glow-card p-5 rounded-3xl bg-slate-900/85 border border-slate-800 hover:border-purple-500/50 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Campañas Cusco</span>
                            <span class="text-3xl font-black text-white counter-kpi mt-1 block" data-target="{{ $convocatorias->count() }}">0</span>
                            <span class="text-[11px] text-purple-300 font-bold mt-1 inline-flex items-center gap-1">
                                <i class="bi bi-geo-alt-fill"></i> {{ $porDistrito->count() }} distritos
                            </span>
                        </div>
                        <div class="flex items-center gap-1 h-12 shrink-0">
                            <span class="w-1.5 h-4 rounded-full bg-purple-500/50 bar-pulse" style="animation-delay:0.2s"></span>
                            <span class="w-1.5 h-9 rounded-full bg-purple-400 bar-pulse" style="animation-delay:0.4s"></span>
                            <span class="w-1.5 h-6 rounded-full bg-purple-500/70 bar-pulse" style="animation-delay:0.6s"></span>
                            <span class="w-1.5 h-11 rounded-full bg-purple-400 shadow-lg shadow-purple-400/40 bar-pulse" style="animation-delay:0.8s"></span>
                            <span class="w-1.5 h-7 rounded-full bg-purple-500/60 bar-pulse" style="animation-delay:1.0s"></span>
                            <span class="w-1.5 h-10 rounded-full bg-purple-300 bar-pulse" style="animation-delay:1.2s"></span>
                        </div>
                    </div>

                    <div class="anim-entrada delay-4 kpi-glow-card p-5 rounded-3xl bg-slate-900/85 border border-slate-800 hover:border-emerald-500/50 flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Horas Certificadas</span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="text-3xl font-black text-emerald-400 counter-kpi" data-target="{{ $totalHorasSistema }}">0</span>
                                <span class="text-xs font-bold text-slate-400">hrs</span>
                            </div>
                            <span class="text-[11px] text-emerald-400 font-bold mt-1 inline-flex items-center gap-1">
                                <i class="bi bi-qr-code"></i> {{ $certificadosEmitidos->count() }} diplomas QR
                            </span>
                        </div>
                        <svg class="w-20 h-12 shrink-0 text-emerald-400" viewBox="0 0 80 45">
                            <defs>
                                <linearGradient id="gradGreen" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#10b981" stop-opacity="0.4"/>
                                    <stop offset="100%" stop-color="#10b981" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <path d="M2 40 L16 32 L32 28 L48 22 L64 12 L78 5 L78 44 L2 44 Z" fill="url(#gradGreen)"/>
                            <path class="spark-line-anim" d="M2 40 L16 32 L32 28 L48 22 L64 12 L78 5" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="78" cy="5" r="3" fill="#10b981" class="animate-ping"/>
                        </svg>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 anim-entrada delay-2">
                    <div class="lg:col-span-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="kpi-glow-card p-6 rounded-3xl bg-slate-900/85 border border-slate-800 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-black text-white uppercase tracking-wider">Cobertura Cusco</h3>
                                        <p class="text-[11px] text-slate-400">Campañas por distrito</p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg bg-cyanNeon-500/15 text-cyanNeon-400 font-mono text-[10px] font-bold">DIST</span>
                                </div>
                                <div class="h-56"><canvas id="chartDistritos"></canvas></div>
                            </div>

                            <div class="kpi-glow-card p-6 rounded-3xl bg-slate-900/85 border border-slate-800 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-black text-white uppercase tracking-wider">Áreas Académicas</h3>
                                        <p class="text-[11px] text-slate-400">Proyectos sociales</p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg bg-purple-500/15 text-purple-300 font-mono text-[10px] font-bold">ÁREAS</span>
                                </div>
                                <div class="h-56 flex items-center justify-center"><canvas id="chartCategorias"></canvas></div>
                            </div>
                        </div>

                        <div class="p-6 rounded-3xl bg-slate-900/85 border border-slate-800 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-black text-white uppercase tracking-wider">Líderes hacia las 100 Horas</h3>
                                <button onclick="navegarSeccionAdmin('alumnos')" class="text-xs font-bold text-brand-400 hover:underline">Abrir directorio completo →</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($progresoAlumnos->take(3) as $top)
                                <div onclick='abrirExpedienteAlumno(@json($top))' class="cursor-pointer p-4 rounded-2xl bg-slate-950/60 hover:bg-slate-950 border border-slate-800/80 hover:border-brand-500/50 transition-all hover:-translate-y-1 space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2.5 min-w-0">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-cyanNeon-400 text-slate-950 font-black text-xs flex items-center justify-center shrink-0">
                                                {{ $top->iniciales }}
                                            </div>
                                            <div class="min-w-0">
                                                <span class="font-bold text-white text-xs block truncate">{{ $top->name ?? 'Estudiante' }}</span>
                                                <span class="text-[10px] text-slate-400 block truncate">{{ $top->carrera ?? 'Estudiante UC' }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-black text-emerald-400 shrink-0">{{ $top->horas_certificadas }}h</span>
                                    </div>
                                    <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-brand-500 to-emerald-400 transition-all duration-1000" style="width: {{ $top->porcentaje_meta }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-4 p-6 rounded-3xl bg-gradient-to-b from-slate-900 to-slate-950 border border-brand-500/30 flex flex-col justify-between space-y-5 shadow-2xl">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                    <h3 class="text-xs font-black text-white uppercase tracking-wider">Monitor Institucional</h3>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 font-mono text-[10px] font-bold">EN VIVO</span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800">
                                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Aptos 100h</span>
                                    <span class="text-2xl font-black text-emerald-400 mt-0.5 block">{{ $aptos100Count }}</span>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800">
                                    <span class="text-[10px] text-slate-400 uppercase font-bold block">Chats Soporte</span>
                                    <span class="text-2xl font-black text-cyanNeon-400 mt-0.5 block">{{ $conversacionesAgrupadas->count() }}</span>
                                </div>
                            </div>

                            <div class="space-y-2.5">
                                <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Últimos Certificados Emitidos</span>
                                @forelse($certificadosEmitidos->take(4) as $cLive)
                                    <div class="flex items-center justify-between gap-2 py-2 border-b border-slate-800/60 last:border-0 text-xs">
                                        <div class="min-w-0">
                                            <span class="font-bold text-white block truncate">{{ $cLive->voluntario_nombre }}</span>
                                            <span class="text-[10px] text-slate-400 block truncate">{{ $cLive->proyecto }}</span>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-500/15 text-emerald-400 font-mono font-bold text-[10px] shrink-0">
                                            +{{ ($cLive->horas_validadas ?: $cLive->horas) }}h
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-xs text-slate-500 py-3">Sin certificados recientes.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="space-y-2">
                            <button onclick="toggleCopilotoAdmin('📊 Dame un diagnóstico ejecutivo del semestre actual')" class="w-full py-2.5 rounded-2xl bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/40 text-purple-200 font-bold text-xs flex items-center justify-center gap-2 transition-all">
                                <img src="{{ $botAdminLogo }}" alt="VoluntBot" class="w-4 h-4 object-contain"> Consultar a VoluntBot Admin
                            </button>
                            <button onclick="navegarSeccionAdmin('consultas')" class="w-full py-3 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider hover:scale-[1.02] transition-transform flex items-center justify-center gap-2">
                                <i class="bi bi-chat-dots-fill"></i> Abrir Bandeja de Soporte
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 2: DIRECTORIO DE ESTUDIANTES -->
            <section id="sec-admin-alumnos" class="admin-section hidden anim-entrada space-y-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Directorio de Estudiantes <span class="text-brand-400 font-light italic">Voluntarios</span></h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $voluntarios->count() }} estudiantes registrados • {{ $aptos100Count }} con meta de 100h completada</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="buscador-cards-alumnos" onkeyup="filtrarCardsAlumnos()" placeholder="Buscar nombre, DNI o carrera..." class="pl-9 pr-4 py-2.5 rounded-full bg-slate-900 border border-slate-800 text-white text-xs w-64 focus:border-brand-400 focus:outline-none">
                        </div>
                        <button onclick="abrirModalConvalidar()" class="px-4 py-2.5 rounded-full bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs shadow-lg shadow-brand-500/20 hover:scale-105 transition-transform">
                            + Convalidar Horas
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button onclick="setFiltroPillAlumno('todos', this)" class="pill-filter active px-4 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs text-slate-300 transition-all">
                        Todos <span class="ml-1 opacity-75">{{ $voluntarios->count() }}</span>
                    </button>
                    <button onclick="setFiltroPillAlumno('apto', this)" class="pill-filter px-4 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs text-slate-300 transition-all">
                        Aptos 100h <span class="ml-1 opacity-75">{{ $aptos100Count }}</span>
                    </button>
                    <button onclick="setFiltroPillAlumno('progreso', this)" class="pill-filter px-4 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs text-slate-300 transition-all">
                        En Progreso <span class="ml-1 opacity-75">{{ $enProgresoCount }}</span>
                    </button>
                    <button onclick="setFiltroPillAlumno('nuevo', this)" class="pill-filter px-4 py-1.5 rounded-full bg-slate-900 border border-slate-800 text-xs text-slate-300 transition-all">
                        Nuevos (0h) <span class="ml-1 opacity-75">{{ $nuevosSinHorasCount }}</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
                    <div id="grid-cards-alumnos" class="xl:col-span-9 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @forelse($progresoAlumnos as $al)
                            @php
                                $estadoAl = $al->horas_certificadas >= 100 ? 'apto' : ($al->horas_certificadas > 0 ? 'progreso' : 'nuevo');
                            @endphp
                            <div class="card-alumno-item kpi-glow-card p-5 rounded-3xl bg-slate-900/90 border border-slate-800/90 hover:border-brand-500/50 flex flex-col justify-between space-y-4 shadow-xl"
                                 data-estado="{{ $estadoAl }}"
                                 data-search="{{ strtolower(($al->name ?? '') . ' ' . ($al->dni ?? '') . ' ' . ($al->carrera ?? '') . ' ' . ($al->email ?? '')) }}">
                                
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-slate-800 to-slate-950 border border-brand-500/40 text-brand-300 font-black text-sm flex items-center justify-center shrink-0">
                                                {{ $al->iniciales }}
                                            </div>
                                            <div class="min-w-0">
                                                <h3 class="font-bold text-white text-sm truncate">{{ $al->name ?? 'Estudiante' }}</h3>
                                                <p class="text-[11px] text-slate-400 truncate">{{ $al->carrera ?? 'Estudiante UC' }}</p>
                                            </div>
                                        </div>

                                        @if($estadoAl === 'apto')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-400 shrink-0">Apto 100h</span>
                                        @elseif($estadoAl === 'progreso')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyanNeon-500/20 text-cyanNeon-300 shrink-0">Activo</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amberGlow-500/20 text-amberGlow-300 shrink-0">Nuevo</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between text-xs pt-1">
                                        <span class="text-amberGlow-400 font-bold flex items-center gap-1">
                                            ★★★★★ <span class="text-slate-300 font-mono text-[11px]">{{ number_format($al->rating_promedio, 1) }}</span>
                                        </span>
                                        <span class="text-slate-300 font-bold">
                                            <strong class="text-emerald-400">{{ $al->horas_certificadas }}h</strong> / 100h
                                        </span>
                                    </div>

                                    <div class="space-y-0.5 text-[11px] text-slate-400">
                                        <p class="truncate text-slate-300">{{ $al->email }}</p>
                                        <p class="font-mono">DNI: {{ $al->dni ?? '---' }} • {{ $al->telefono ?? 'Sin teléfono' }}</p>
                                    </div>

                                    <div class="w-full h-1.5 rounded-full bg-slate-800 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-brand-500 to-cyanNeon-400" style="width: {{ $al->porcentaje_meta }}%"></div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2.5 pt-1">
                                    <button type="button" onclick='abrirExpedienteAlumno(@json($al))' class="py-2 px-3 rounded-full bg-slate-950 hover:bg-brand-500 text-white hover:text-slate-950 border border-slate-700 hover:border-brand-500 font-bold text-xs transition-all flex items-center justify-center gap-1.5">
                                        <i class="bi bi-eye"></i> Ver Ficha
                                    </button>
                                    <button type="button" onclick="abrirChatConUsuario('{{ addslashes($al->email) }}', '{{ addslashes($al->name) }}', 'Voluntario')" class="py-2 px-3 rounded-full bg-slate-800/70 hover:bg-cyanNeon-500/20 text-slate-200 hover:text-cyanNeon-300 border border-slate-700 hover:border-cyanNeon-500/40 font-bold text-xs transition-all flex items-center justify-center gap-1.5">
                                        <i class="bi bi-chat-left-text"></i> Mensaje
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-10 text-center text-slate-500 text-xs">No hay estudiantes registrados todavía.</div>
                        @endforelse
                    </div>

                    <div class="xl:col-span-3 p-6 rounded-3xl bg-slate-900/90 border border-slate-800 space-y-5 sticky top-24">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-black text-white">Cobertura 100h</h3>
                            <span class="px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-400 text-[10px] font-bold">● Live</span>
                        </div>

                        <div class="relative h-44 flex items-center justify-center">
                            <canvas id="chartCoberturaAlumnos"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-2xl font-black text-white">{{ $voluntarios->count() }}</span>
                                <span class="text-[10px] text-slate-400">en padrón</span>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2 border-t border-slate-800 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-300"><span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span> Aptos (100h)</span>
                                <strong class="text-white">{{ $aptos100Count }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-300"><span class="w-2.5 h-2.5 rounded-full bg-cyanNeon-400"></span> En Progreso</span>
                                <strong class="text-white">{{ $enProgresoCount }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2 text-slate-300"><span class="w-2.5 h-2.5 rounded-full bg-amberGlow-400"></span> Nuevos (0h)</span>
                                <strong class="text-white">{{ $nuevosSinHorasCount }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 3: DIRECTORIO DE ONGs -->
            <section id="sec-admin-ongs" class="admin-section hidden anim-entrada space-y-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Directorio de ONGs <span class="text-amberGlow-400 font-light italic">Aliadas Cusco</span></h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $expedienteOngs->count() }} organizaciones verificadas con RUC SUNAT</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <input type="text" id="buscador-cards-ongs" onkeyup="filtrarCardsOngs()" placeholder="Buscar ONG, RUC o distrito..." class="px-4 py-2.5 rounded-full bg-slate-900 border border-slate-800 text-white text-xs w-64 focus:border-amberGlow-400 focus:outline-none">
                        <button onclick="abrirModalNuevaOng()" class="px-4 py-2.5 rounded-full bg-amberGlow-400 text-slate-950 font-black text-xs hover:scale-105 transition-transform">
                            + Registrar ONG
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="grid-cards-ongs">
                    @forelse($expedienteOngs as $ong)
                        @php $aprobada = ($ong->estado_verificacion ?? 'Aprobado') === 'Aprobado'; @endphp
                        <div class="card-ong-item kpi-glow-card p-5 rounded-3xl bg-slate-900/90 border border-slate-800/90 hover:border-amberGlow-500/50 flex flex-col justify-between space-y-4 shadow-xl"
                             data-search="{{ strtolower(($ong->name ?? '') . ' ' . ($ong->ruc ?? '') . ' ' . ($ong->distrito ?? '') . ' ' . ($ong->email ?? '')) }}">
                            
                            <div class="space-y-3">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-full bg-amberGlow-500/15 border border-amberGlow-500/40 text-amberGlow-300 font-black text-sm flex items-center justify-center shrink-0">
                                            {{ $ong->iniciales }}
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="font-bold text-white text-sm truncate">{{ $ong->name ?? 'ONG' }}</h3>
                                            <p class="text-[11px] text-slate-400 truncate">{{ $ong->distrito ?? 'Cusco Centro' }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black {{ $aprobada ? 'bg-emerald-500/20 text-emerald-400' : 'bg-amberGlow-500/20 text-amberGlow-300' }} shrink-0">
                                        {{ $aprobada ? 'RUC Activo' : 'Pendiente' }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-xs pt-1">
                                    <span class="text-slate-300 font-bold"><strong class="text-white">{{ $ong->total_campanas }}</strong> campañas</span>
                                    <span class="text-emerald-400 font-bold">{{ $ong->horas_emitidas }} hrs emitidas</span>
                                </div>

                                <div class="space-y-0.5 text-[11px] text-slate-400">
                                    <p class="truncate text-slate-300">{{ $ong->email }}</p>
                                    <p class="font-mono text-amberGlow-400">RUC: {{ $ong->ruc ?? 'Sin RUC' }} • {{ $ong->telefono ?? 'Sin tel.' }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5 pt-1">
                                <button type="button" onclick='abrirExpedienteOng(@json($ong))' class="py-2 px-3 rounded-full bg-slate-950 hover:bg-amberGlow-400 text-white hover:text-slate-950 border border-slate-700 hover:border-amberGlow-400 font-bold text-xs transition-all flex items-center justify-center gap-1.5">
                                    <i class="bi bi-eye"></i> Ver Ficha
                                </button>
                                <button type="button" onclick="abrirChatConUsuario('{{ addslashes($ong->email) }}', '{{ addslashes($ong->name) }}', 'ONG / Organización')" class="py-2 px-3 rounded-full bg-slate-800/70 hover:bg-cyanNeon-500/20 text-slate-200 hover:text-cyanNeon-300 border border-slate-700 hover:border-cyanNeon-500/40 font-bold text-xs transition-all flex items-center justify-center gap-1.5">
                                    <i class="bi bi-chat-left-text"></i> Mensaje
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-10 text-center text-slate-500 text-xs">No hay ONGs registradas todavía.</div>
                    @endforelse
                </div>
            </section>

            <!-- SECCIÓN 4: BANDEJA DE SOPORTE DE 2 COLUMNAS -->
            <section id="sec-admin-consultas" class="admin-section hidden anim-entrada space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-black text-white tracking-tight">Mensajes y Soporte Institucional</h2>
                        <p class="text-xs text-slate-400">Conversaciones directas con Voluntarios y ONGs • Incluye redactor automático con VoluntBot Admin</p>
                    </div>
                </div>

                <div class="rounded-3xl bg-slate-900/95 border border-slate-800 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[560px] shadow-2xl">
                    <div class="lg:col-span-4 border-b lg:border-b-0 lg:border-r border-slate-800 flex flex-col bg-slate-950/60">
                        <div class="p-4 border-b border-slate-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-black text-white">Conversaciones</span>
                                <span class="text-[11px] font-mono text-slate-400">{{ $conversacionesAgrupadas->count() }} hilos</span>
                            </div>
                            <input type="text" id="buscar-chat-lista" onkeyup="filtrarListaChats()" placeholder="Filtrar persona..." class="w-full px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs focus:border-brand-400 focus:outline-none">
                        </div>

                        <div id="lista-conversaciones-chat" class="flex-1 overflow-y-auto divide-y divide-slate-800/50 max-h-[460px]">
                            @forelse($conversacionesAgrupadas as $idx => $convChat)
                                <button type="button"
                                        onclick="seleccionarHiloChat('{{ $convChat['key'] }}')"
                                        id="chat-item-{{ md5($convChat['key']) }}"
                                        data-nombre="{{ strtolower($convChat['nombre'] . ' ' . $convChat['correo']) }}"
                                        class="chat-thread-btn w-full text-left p-4 hover:bg-slate-900/90 transition-all flex items-center justify-between gap-3 {{ $idx === 0 ? 'bg-slate-900 border-l-4 border-brand-400' : '' }}">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-full {{ $convChat['rol'] === 'ONG / Organización' ? 'bg-amberGlow-500/20 text-amberGlow-300 border border-amberGlow-500/40' : 'bg-brand-500/20 text-brand-300 border border-brand-500/40' }} font-black text-xs flex items-center justify-center shrink-0">
                                            {{ $convChat['iniciales'] }}
                                        </div>
                                        <div class="min-w-0">
                                            <strong class="text-xs text-white truncate block">{{ $convChat['nombre'] }}</strong>
                                            <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $convChat['ultimo_texto'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 shrink-0">
                                        <span class="font-mono text-[10px] text-slate-500">{{ $convChat['ultima_hora'] }}</span>
                                        @if($convChat['pendientes'] > 0)
                                            <span id="badge-pend-{{ md5($convChat['key']) }}" class="badge-chat-pendiente w-5 h-5 rounded-full bg-rose-500 text-white font-black text-[10px] flex items-center justify-center">
                                                {{ $convChat['pendientes'] }}
                                            </span>
                                        @endif
                                    </div>
                                </button>
                            @empty
                                <div id="chat-lista-vacia" class="p-8 text-center text-xs text-slate-500">
                                    Aún no hay mensajes. Puedes iniciar uno pulsando "Mensaje" en cualquier tarjeta de Estudiante u ONG.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="lg:col-span-8 flex flex-col justify-between bg-slate-900/40">
                        <div class="p-4 border-b border-slate-800 bg-slate-950/40 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div id="chat-active-avatar" class="w-10 h-10 rounded-full bg-brand-500/20 border border-brand-500/40 text-brand-300 font-black text-xs flex items-center justify-center">
                                    VM
                                </div>
                                <div>
                                    <h3 id="chat-active-nombre" class="text-sm font-black text-white">Selecciona una conversación</h3>
                                    <p id="chat-active-meta" class="text-[11px] text-emerald-400 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Respuesta sincronizada con Dashboard del usuario
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" id="btn-sugerir-ia-chat" onclick="sugerirRespuestaChatIA()" class="px-3 py-1.5 rounded-full bg-purple-500/20 hover:bg-purple-500/30 border border-purple-500/40 text-purple-200 font-bold text-[11px] flex items-center gap-1.5 transition-all">
                                    <img src="{{ $botAdminLogo }}" alt="IA" class="w-3.5 h-3.5 object-contain"> Sugerir Respuesta IA
                                </button>
                                <span id="chat-active-rol" class="px-3 py-1 rounded-full bg-slate-800 text-slate-300 font-mono text-[10px] font-bold">Soporte UC</span>
                            </div>
                        </div>

                        <div id="chat-mensajes-contenedor" class="flex-1 p-6 space-y-4 overflow-y-auto max-h-[380px]">
                            <div class="h-full flex items-center justify-center text-xs text-slate-500">
                                Selecciona un usuario a la izquierda para ver su historial de consultas.
                            </div>
                        </div>

                        <form onsubmit="enviarRespuestaChatAdmin(event)" class="p-4 border-t border-slate-800 bg-slate-950/60 flex items-center gap-3">
                            <input type="hidden" id="chat-input-correo">
                            <input type="hidden" id="chat-input-nombre">
                            <input type="text" id="chat-input-mensaje" required placeholder="Escribe una respuesta oficial o usa 'Sugerir Respuesta IA'..." class="flex-1 px-4 py-3 rounded-full bg-slate-900 border border-slate-700 text-white text-xs focus:border-brand-400 focus:outline-none">
                            <button type="submit" id="btn-enviar-chat-admin" class="w-11 h-11 rounded-full bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black flex items-center justify-center shadow-lg shadow-brand-500/25 hover:scale-105 transition-transform shrink-0">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 5: MODERACIÓN GLOBAL DE CAMPAÑAS -->
            <section id="sec-admin-campanas" class="admin-section hidden anim-entrada space-y-5">
                <div class="p-6 rounded-3xl bg-slate-900/85 border border-slate-800 space-y-4">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-black text-white">Moderación Global de Convocatorias</h2>
                        <p class="text-xs text-slate-400">Supervisa, pausa o elimina campañas publicadas en la plataforma</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead class="text-[11px] font-black uppercase text-slate-400 border-b border-slate-800 bg-slate-950/60">
                                <tr>
                                    <th class="p-3">Campaña</th>
                                    <th class="p-3">Categoría / Distrito</th>
                                    <th class="p-3">Cupos / Horas</th>
                                    <th class="p-3">Estado</th>
                                    <th class="p-3 text-right">Moderación</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @forelse($convocatorias as $c)
                                @php $cerrada = ($c->estado ?? 'Activa') === 'Cerrada'; @endphp
                                <tr class="hover:bg-slate-800/30">
                                    <td class="p-3">
                                        <div class="font-bold text-white">{{ $c->titulo ?? 'Campaña' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $c->organizacion ?? 'ONG Aliada' }}</div>
                                    </td>
                                    <td class="p-3">
                                        <span class="text-amberGlow-400 font-bold block">{{ $c->categoria ?? 'General' }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $c->distrito ?? 'Cusco' }}</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-bold text-white">{{ $c->cupos_disponibles ?? $c->cupos ?? 0 }}/{{ $c->cupos ?? 0 }} libres</span>
                                        <span class="block text-[11px] text-emerald-400">{{ $c->horas ?? 0 }} horas</span>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $cerrada ? 'bg-rose-500/20 text-rose-400' : 'bg-emerald-500/20 text-emerald-400' }}">
                                            {{ $c->estado ?? 'Activa' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-right space-x-1">
                                        <a href="{{ route('detalle-oportunidad', ['id' => $c->id + 1000]) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-slate-800 text-cyanNeon-400 font-bold text-[11px]">Ver</a>
                                        <button onclick="toggleCampanaAdmin({{ $c->id }})" class="px-3 py-1.5 rounded-lg bg-brand-500/20 text-brand-300 border border-brand-500/40 font-bold text-[11px] hover:bg-brand-500 hover:text-slate-950">
                                            {{ $cerrada ? 'Reactivar' : 'Pausar' }}
                                        </button>
                                        <button onclick="eliminarCampanaAdmin({{ $c->id }}, '{{ addslashes($c->titulo ?? 'Campaña') }}')" class="px-2.5 py-1.5 rounded-lg bg-rose-500/15 text-rose-400 hover:bg-rose-500 hover:text-white font-bold text-[11px]">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="p-6 text-center text-slate-500">No hay campañas registradas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 6: AUDITORÍA DE CERTIFICADOS QR -->
            <section id="sec-admin-certificados" class="admin-section hidden anim-entrada space-y-5">
                <div class="p-6 rounded-3xl bg-slate-900/85 border border-slate-800 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-4">
                        <div>
                            <h2 class="text-lg font-black text-white">Registro Maestro de Certificados con Código QR</h2>
                            <p class="text-xs text-slate-400">Historial auditado de diplomas emitidos en el sistema</p>
                        </div>
                        <button onclick="abrirModalVerificadorQr()" class="px-4 py-2 rounded-xl bg-cyanNeon-500 text-slate-950 font-black text-xs">
                            <i class="bi bi-qr-code-scan me-1"></i> Verificar Código QR
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead class="text-[11px] font-black uppercase text-slate-400 border-b border-slate-800 bg-slate-950/60">
                                <tr>
                                    <th class="p-3">Código QR</th>
                                    <th class="p-3">Estudiante</th>
                                    <th class="p-3">Campaña / Entidad</th>
                                    <th class="p-3">Horas</th>
                                    <th class="p-3 text-right">Diploma</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @forelse($certificadosEmitidos as $cert)
                                <tr class="hover:bg-slate-800/30">
                                    <td class="p-3 font-mono font-black text-emerald-400">{{ $cert->codigo_certificado ?? 'VM-' . $cert->id }}</td>
                                    <td class="p-3">
                                        <div class="font-bold text-white">{{ $cert->voluntario_nombre ?? 'Estudiante' }}</div>
                                        <div class="text-[11px] text-slate-400">DNI: {{ $cert->voluntario_dni ?? '---' }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-semibold text-slate-200">{{ $cert->proyecto ?? 'Campaña' }}</div>
                                        <div class="text-[11px] text-amberGlow-400">{{ $cert->organizacion ?? 'ONG Aliada' }}</div>
                                    </td>
                                    <td class="p-3 font-black text-emerald-400">{{ ($cert->horas_validadas ?? 0) ?: ($cert->horas ?? 0) }} hrs</td>
                                    <td class="p-3 text-right">
                                        <a href="{{ route('certificado.ver', ['id' => $cert->id]) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 font-bold text-[11px] hover:bg-emerald-500 hover:text-slate-950">
                                            <i class="bi bi-patch-check-fill me-1"></i> Abrir Diploma
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="p-6 text-center text-slate-500">Aún no se han emitido certificados oficiales.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- SECCIÓN 7: BITÁCORA DE AUDITORÍA -->
            <section id="sec-admin-auditoria" class="admin-section hidden anim-entrada space-y-5">
                <div class="p-6 rounded-3xl bg-slate-900/85 border border-slate-800 space-y-4">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-lg font-black text-white">Bitácora de Auditoría y Seguridad Institucional</h2>
                        <p class="text-xs text-slate-400">Registro inmutable de todas las acciones administrativas ejecutadas en el sistema</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-slate-300">
                            <thead class="text-[11px] font-black uppercase text-slate-400 border-b border-slate-800 bg-slate-950/60">
                                <tr>
                                    <th class="p-3">Fecha / Hora</th>
                                    <th class="p-3">Módulo</th>
                                    <th class="p-3">Acción Ejecutada</th>
                                    <th class="p-3">Responsable</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @forelse($auditoriaLogs as $log)
                                <tr class="hover:bg-slate-800/30">
                                    <td class="p-3 font-mono text-[11px] text-slate-400">{{ $log->created_at }}</td>
                                    <td class="p-3"><span class="px-2.5 py-1 rounded-lg bg-brand-500/15 text-brand-300 font-bold text-[10px]">{{ $log->modulo }}</span></td>
                                    <td class="p-3 text-white font-medium">{{ $log->detalle }}</td>
                                    <td class="p-3 text-emerald-400 font-bold text-[11px]">{{ $log->admin_nombre }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="p-6 text-center text-slate-500">Aún no se registran eventos en la bitácora.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- VOLUNTBOT ADMIN EXCLUSIVO CON LOGO OFICIAL DEL CHATBOX                    -->
<!-- ========================================================================= -->
<div id="admin-copiloto-widget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
    <div id="admin-copiloto-panel" class="hidden mb-3 w-[350px] sm:w-[400px] rounded-3xl bg-slate-900/95 backdrop-blur-xl border border-purple-500/45 shadow-2xl overflow-hidden anim-entrada">
        <div class="p-4 bg-gradient-to-r from-slate-950 via-purple-950/50 to-slate-950 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-purple-500/25 to-cyanNeon-400/25 border border-purple-400/50 p-1 flex items-center justify-center shadow-lg shrink-0">
                    <img src="{{ $botAdminLogo }}" alt="VoluntBot Admin" class="w-full h-full object-contain drop-shadow-[0_0_8px_rgba(168,85,247,0.6)]">
                </div>
                <div>
                    <h4 class="text-xs font-black text-white">VoluntBot Admin</h4>
                    <span class="text-[10px] text-emerald-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> Conectado a métricas reales
                    </span>
                </div>
            </div>
            <button onclick="toggleCopilotoAdmin()" class="w-8 h-8 rounded-xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 flex items-center justify-center text-xs">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="p-3 bg-slate-950/70 border-b border-slate-800/80 flex flex-wrap gap-1.5">
            <button type="button" onclick="enviarPreguntaCopilotoAdmin('📊 Dame un diagnóstico ejecutivo del semestre actual')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-purple-500/20 border border-slate-700 hover:border-purple-400/50 text-[10px] font-bold text-slate-200 transition-all">
                📊 Diagnóstico semestre
            </button>
            <button type="button" onclick="enviarPreguntaCopilotoAdmin('🎓 ¿Qué estudiantes ya tienen 100 horas aptas?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-emerald-500/20 border border-slate-700 hover:border-emerald-400/50 text-[10px] font-bold text-slate-200 transition-all">
                🎓 Aptos 100h
            </button>
            <button type="button" onclick="enviarPreguntaCopilotoAdmin('💬 Resumen de mensajes pendientes de soporte')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-cyanNeon-500/20 border border-slate-700 hover:border-cyanNeon-400/50 text-[10px] font-bold text-slate-200 transition-all">
                💬 Soporte pendiente
            </button>
            <button type="button" onclick="enviarPreguntaCopilotoAdmin('✍️ Redacta un comunicado oficial para todos los usuarios')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-amberGlow-500/20 border border-slate-700 hover:border-amberGlow-400/50 text-[10px] font-bold text-slate-200 transition-all">
                ✍️ Redactar comunicado
            </button>
        </div>

        <div id="admin-copiloto-mensajes" class="p-4 space-y-3 h-64 overflow-y-auto text-xs">
            <div class="p-3 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-slate-700 text-slate-200 leading-relaxed">
                Hola, Coordinador. Soy <strong class="text-purple-300">VoluntBot Admin</strong>. Tengo acceso en tiempo real al padrón de estudiantes, ONGs RUC, certificados QR y chats pendientes. ¿Qué deseas analizar?
            </div>
        </div>

        <form onsubmit="handleFormCopilotoAdmin(event)" class="p-3 border-t border-slate-800 bg-slate-950/90 flex items-center gap-2">
            <input type="text" id="admin-copiloto-input" required placeholder="Pregunta sobre métricas, ONGs, distritos..." class="flex-1 px-3.5 py-2.5 rounded-full bg-slate-900 border border-slate-700 text-white text-xs focus:border-purple-400 focus:outline-none">
            <button type="submit" id="admin-copiloto-send" class="w-9 h-9 rounded-full bg-gradient-to-r from-purple-500 to-cyanNeon-400 text-slate-950 font-black flex items-center justify-center shrink-0 hover:scale-105 transition-transform">
                <i class="bi bi-send-fill text-xs"></i>
            </button>
        </form>
    </div>

    <button onclick="toggleCopilotoAdmin()" class="ai-orb-btn group pl-2.5 pr-4 py-2 rounded-full bg-gradient-to-r from-slate-950 via-purple-950/80 to-slate-950 border-2 border-purple-400/65 text-white font-black text-xs flex items-center gap-2.5 hover:scale-105 transition-transform">
        <span class="relative flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-purple-500/25 to-cyanNeon-400/25 border border-purple-400/50 p-1">
            <img src="{{ $botAdminLogo }}" alt="VoluntBot Admin" class="w-full h-full object-contain group-hover:rotate-12 transition-transform duration-300 drop-shadow-[0_0_8px_rgba(168,85,247,0.65)]">
            <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-slate-950 animate-pulse"></span>
        </span>
        <div class="text-left">
            <span class="block text-xs font-black text-white leading-none">VoluntBot</span>
            <span class="block text-[9px] font-mono font-bold text-purple-300 mt-0.5">ADMIN AI</span>
        </div>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const titulosSecciones = {
        resumen: 'Dashboard Ejecutivo • Proyección Social Cusco',
        alumnos: 'Directorio de Estudiantes Voluntarios (100 Horas)',
        ongs: 'Directorio de ONGs y Entidades Aliadas (RUC)',
        consultas: 'Mensajes y Soporte Institucional en Vivo',
        campanas: 'Moderación Global de Convocatorias',
        certificados: 'Auditoría Oficial de Certificados con Código QR',
        auditoria: 'Bitácora de Auditoría y Trazabilidad Institucional'
    };

    const hilosChatData = @json($conversacionesAgrupadas->keyBy('key'));
    const alumnosDataGlobal = @json($progresoAlumnos);
    const ongsDataGlobal = @json($expedienteOngs);
    let filtroEstadoAlumnoActual = 'todos';
    let chatKeyActivo = null;

    function navegarSeccionAdmin(seccion, btnElement = null) {
        document.querySelectorAll('.admin-section').forEach(sec => sec.classList.add('hidden'));
        const target = document.getElementById(`sec-admin-${seccion}`);
        if (target) target.classList.remove('hidden');
        document.getElementById('topbar-titulo').textContent = titulosSecciones[seccion] || 'Panel Administrador';

        document.querySelectorAll('.nav-admin-item').forEach(item => item.classList.remove('active'));
        if (btnElement) btnElement.classList.add('active');
        else {
            const autoBtn = document.querySelector(`button[onclick*="navegarSeccionAdmin('${seccion}'"]`);
            if (autoBtn) autoBtn.classList.add('active');
        }
    }

    function toggleCopilotoAdmin(preguntaAuto = null) {
        const panel = document.getElementById('admin-copiloto-panel');
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            document.getElementById('admin-copiloto-input').focus();
            if (preguntaAuto) {
                enviarPreguntaCopilotoAdmin(preguntaAuto);
            }
        }
    }

    function handleFormCopilotoAdmin(e) {
        e.preventDefault();
        const input = document.getElementById('admin-copiloto-input');
        const texto = input.value.trim();
        if (!texto) return;
        input.value = '';
        enviarPreguntaCopilotoAdmin(texto);
    }

    async function enviarPreguntaCopilotoAdmin(pregunta) {
        const box = document.getElementById('admin-copiloto-mensajes');
        const btn = document.getElementById('admin-copiloto-send');

        box.innerHTML += `
            <div class="flex justify-end">
                <div class="max-w-[85%] px-3.5 py-2.5 rounded-2xl rounded-tr-sm bg-purple-500/25 border border-purple-400/40 text-white">
                    ${escapeHtml(pregunta)}
                </div>
            </div>
        `;
        const typingId = 'typing-' + Date.now();
        box.innerHTML += `
            <div id="${typingId}" class="flex justify-start">
                <div class="px-3.5 py-2.5 rounded-2xl bg-slate-800/80 text-purple-300 font-mono text-[11px] animate-pulse">
                    Analizando base de datos Cusco...
                </div>
            </div>
        `;
        box.scrollTop = box.scrollHeight;
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('ia.admin-copiloto') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pregunta })
            });
            const data = await res.json();
            document.getElementById(typingId)?.remove();

            const htmlFormateado = escapeHtml(data.respuesta || 'Sin respuesta.')
                .replace(/\*\*(.*?)\*\*/g, '<strong class="text-emerald-300">$1</strong>')
                .replace(/\n/g, '<br>');

            box.innerHTML += `
                <div class="flex justify-start">
                    <div class="max-w-[90%] px-3.5 py-2.5 rounded-2xl rounded-tl-sm bg-slate-800/95 border border-slate-700 text-slate-100 leading-relaxed">
                        ${htmlFormateado}
                    </div>
                </div>
            `;
            box.scrollTop = box.scrollHeight;
        } finally {
            btn.disabled = false;
        }
    }

    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            abrirBuscadorGlobalAdmin();
        }
    });

    function abrirBuscadorGlobalAdmin() {
        Swal.fire({
            title: 'Búsqueda Rápida Institucional',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <input type="text" id="global-search-input" placeholder="Escribe nombre de estudiante, DNI, ONG o RUC..." class="w-full px-4 py-3 rounded-2xl bg-slate-950 border border-cyanNeon-500/40 text-white text-xs focus:outline-none">
                    <div id="global-search-results" class="space-y-2 max-h-60 overflow-y-auto text-xs">
                        <p class="text-slate-500 text-center py-4">Escribe al menos 2 letras para buscar en todo el sistema...</p>
                    </div>
                </div>
            `,
            didOpen: () => {
                const inp = document.getElementById('global-search-input');
                const resBox = document.getElementById('global-search-results');
                inp.focus();
                inp.addEventListener('input', () => {
                    const q = inp.value.toLowerCase().trim();
                    if (q.length < 2) {
                        resBox.innerHTML = '<p class="text-slate-500 text-center py-4">Escribe al menos 2 letras...</p>';
                        return;
                    }
                    const matchAl = alumnosDataGlobal.filter(a => ((a.name || '') + ' ' + (a.dni || '') + ' ' + (a.carrera || '')).toLowerCase().includes(q)).slice(0, 4);
                    const matchOng = ongsDataGlobal.filter(o => ((o.name || '') + ' ' + (o.ruc || '') + ' ' + (o.distrito || '')).toLowerCase().includes(q)).slice(0, 4);

                    let html = '';
                    matchAl.forEach(a => {
                        html += `<div onclick='Swal.close(); abrirExpedienteAlumno(${JSON.stringify(a).replace(/'/g, "&#39;")})' class="cursor-pointer p-3 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 flex justify-between items-center">
                            <div><strong class="text-white block">${escapeHtml(a.name)}</strong><span class="text-[10px] text-slate-400">Estudiante • DNI ${a.dni || '---'}</span></div>
                            <span class="text-emerald-400 font-black">${a.horas_certificadas}h</span>
                        </div>`;
                    });
                    matchOng.forEach(o => {
                        html += `<div onclick='Swal.close(); abrirExpedienteOng(${JSON.stringify(o).replace(/'/g, "&#39;")})' class="cursor-pointer p-3 rounded-xl bg-slate-950 hover:bg-slate-800 border border-slate-800 flex justify-between items-center">
                            <div><strong class="text-amberGlow-300 block">${escapeHtml(o.name)}</strong><span class="text-[10px] text-slate-400">ONG • RUC ${o.ruc || '---'}</span></div>
                            <span class="text-cyanNeon-400 font-bold">Ficha →</span>
                        </div>`;
                    });
                    resBox.innerHTML = html || '<p class="text-slate-500 text-center py-4">No se encontraron coincidencias.</p>';
                });
            },
            showConfirmButton: false,
            showCloseButton: true,
            background: '#0f172a',
            color: '#fff'
        });
    }

    function setFiltroPillAlumno(estado, btn) {
        filtroEstadoAlumnoActual = estado;
        document.querySelectorAll('.pill-filter').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        filtrarCardsAlumnos();
    }

    function filtrarCardsAlumnos() {
        const query = (document.getElementById('buscador-cards-alumnos')?.value || '').toLowerCase();
        document.querySelectorAll('.card-alumno-item').forEach(card => {
            const matchEstado = filtroEstadoAlumnoActual === 'todos' || card.dataset.estado === filtroEstadoAlumnoActual;
            const matchTexto = !query || card.dataset.search.includes(query);
            card.style.display = (matchEstado && matchTexto) ? '' : 'none';
        });
    }

    function filtrarCardsOngs() {
        const query = (document.getElementById('buscador-cards-ongs')?.value || '').toLowerCase();
        document.querySelectorAll('.card-ong-item').forEach(card => {
            card.style.display = (!query || card.dataset.search.includes(query)) ? '' : 'none';
        });
    }

    function seleccionarHiloChat(key) {
        const hilo = hilosChatData[key];
        if (!hilo) return;
        chatKeyActivo = key;

        document.querySelectorAll('.chat-thread-btn').forEach(b => {
            b.classList.remove('bg-slate-900', 'border-l-4', 'border-brand-400');
        });
        const btnActivo = Array.from(document.querySelectorAll('.chat-thread-btn')).find(b => b.getAttribute('onclick')?.includes(key));
        if (btnActivo) btnActivo.classList.add('bg-slate-900', 'border-l-4', 'border-brand-400');

        document.getElementById('chat-active-avatar').textContent = hilo.iniciales;
        document.getElementById('chat-active-nombre').textContent = hilo.nombre;
        document.getElementById('chat-active-meta').innerHTML = `<span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> ${hilo.correo}`;
        document.getElementById('chat-active-rol').textContent = hilo.rol;
        document.getElementById('chat-input-correo').value = hilo.correo;
        document.getElementById('chat-input-nombre').value = hilo.nombre;
        document.getElementById('chat-input-mensaje').placeholder = `Escribir mensaje a ${hilo.nombre}...`;

        renderizarBurbujasChat(hilo.mensajes);
    }

    async function sugerirRespuestaChatIA() {
        if (!chatKeyActivo || !hilosChatData[chatKeyActivo]) return;
        const hilo = hilosChatData[chatKeyActivo];
        const ultimoUsuario = [...hilo.mensajes].reverse().find(m => m.autor === 'usuario');
        const btn = document.getElementById('btn-sugerir-ia-chat');
        const input = document.getElementById('chat-input-mensaje');

        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Redactando...';

        try {
            const res = await fetch("{{ route('ia.admin-sugerir-respuesta') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nombre: hilo.nombre,
                    rol: hilo.rol,
                    mensaje: ultimoUsuario ? ultimoUsuario.texto : ''
                })
            });
            const data = await res.json();
            if (data.sugerencia) {
                input.value = data.sugerencia;
                input.focus();
            }
        } finally {
            btn.disabled = false;
            btn.innerHTML = `<img src="{{ $botAdminLogo }}" alt="IA" class="w-3.5 h-3.5 object-contain"> Sugerir Respuesta IA`;
        }
    }

    function renderizarBurbujasChat(mensajes) {
        const cont = document.getElementById('chat-mensajes-contenedor');
        if (!mensajes || mensajes.length === 0) {
            cont.innerHTML = `<div class="h-full flex items-center justify-center text-xs text-slate-400">Aún no hay mensajes en este hilo. Escribe abajo para iniciar la conversación.</div>`;
            return;
        }

        cont.innerHTML = mensajes.map(m => {
            if (m.autor === 'admin') {
                return `
                    <div class="flex flex-col items-end space-y-1">
                        <span class="text-[10px] font-mono text-slate-400">Tú (Coordinación UC) • ${m.hora}</span>
                        <div class="max-w-md px-4 py-3 rounded-2xl rounded-tr-sm bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-medium text-xs shadow-lg">
                            ${escapeHtml(m.texto)}
                        </div>
                    </div>
                `;
            }
            return `
                <div class="flex flex-col items-start space-y-1">
                    <span class="text-[10px] font-mono text-slate-400">${escapeHtml(m.nombre)} • ${m.hora}</span>
                    <div class="max-w-md px-4 py-3 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-slate-700 text-slate-100 text-xs space-y-1">
                        ${m.asunto ? `<span class="text-[10px] font-bold text-cyanNeon-400 block">${escapeHtml(m.asunto)}</span>` : ''}
                        <p class="leading-relaxed">${escapeHtml(m.texto)}</p>
                    </div>
                </div>
            `;
        }).join('');

        cont.scrollTop = cont.scrollHeight;
    }

    function abrirChatConUsuario(correo, nombre, rol) {
        const key = correo.toLowerCase().trim();
        if (!hilosChatData[key]) {
            const iniciales = nombre.split(' ').slice(0, 2).map(p => p[0] || '').join('').toUpperCase() || 'US';
            hilosChatData[key] = { key, nombre, correo, rol, iniciales, pendientes: 0, mensajes: [] };

            const lista = document.getElementById('lista-conversaciones-chat');
            const vacia = document.getElementById('chat-lista-vacia');
            if (vacia) vacia.remove();

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'chat-thread-btn w-full text-left p-4 hover:bg-slate-900/90 transition-all flex items-center justify-between gap-3';
            btn.setAttribute('onclick', `seleccionarHiloChat('${key}')`);
            btn.innerHTML = `
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-cyanNeon-500/20 text-cyanNeon-300 border border-cyanNeon-500/40 font-black text-xs flex items-center justify-center shrink-0">${iniciales}</div>
                    <div class="min-w-0">
                        <strong class="text-xs text-white truncate block">${escapeHtml(nombre)}</strong>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">Nueva conversación directa</p>
                    </div>
                </div>
                <span class="font-mono text-[10px] text-slate-500">Ahora</span>
            `;
            lista.prepend(btn);
        }

        navegarSeccionAdmin('consultas');
        seleccionarHiloChat(key);
        document.getElementById('chat-input-mensaje').focus();
    }

    async function enviarRespuestaChatAdmin(e) {
        e.preventDefault();
        const correo = document.getElementById('chat-input-correo').value;
        const nombre = document.getElementById('chat-input-nombre').value;
        const inputMsg = document.getElementById('chat-input-mensaje');
        const respuesta = inputMsg.value.trim();

        if (!correo || !respuesta) return;

        const btn = document.getElementById('btn-enviar-chat-admin');
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('admin.chat.responder') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ correo, nombre, respuesta })
            });
            const data = await res.json();
            if (data.ok) {
                const key = correo.toLowerCase().trim();
                hilosChatData[key].mensajes.push({
                    autor: 'admin',
                    nombre: 'Coordinación UC',
                    texto: respuesta,
                    hora: data.hora || 'Ahora'
                });
                const btnActivo = Array.from(document.querySelectorAll('.chat-thread-btn')).find(b => b.getAttribute('onclick')?.includes(key));
                btnActivo?.querySelector('.badge-chat-pendiente')?.remove();

                renderizarBurbujasChat(hilosChatData[key].mensajes);
                inputMsg.value = '';
            }
        } finally {
            btn.disabled = false;
        }
    }

    function filtrarListaChats() {
        const q = (document.getElementById('buscar-chat-lista')?.value || '').toLowerCase();
        document.querySelectorAll('.chat-thread-btn').forEach(btn => {
            btn.style.display = (!q || (btn.dataset.nombre || '').includes(q)) ? '' : 'none';
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const primeraKey = Object.keys(hilosChatData)[0];
        if (primeraKey) seleccionarHiloChat(primeraKey);

        document.querySelectorAll('.counter-kpi').forEach(el => {
            const target = parseInt(el.getAttribute('data-target') || '0', 10);
            if (target === 0) { el.textContent = '0'; return; }
            let current = 0;
            const step = Math.max(1, Math.ceil(target / 25));
            const timer = setInterval(() => {
                current += step;
                if (current >= target) { el.textContent = target; clearInterval(timer); }
                else { el.textContent = current; }
            }, 25);
        });

        const distritosLabels = @json($porDistrito->keys());
        const distritosData = @json($porDistrito->values());

        new Chart(document.getElementById('chartDistritos'), {
            type: 'bar',
            data: {
                labels: distritosLabels.length ? distritosLabels : ['Cusco Centro'],
                datasets: [{
                    label: 'Campañas',
                    data: distritosData.length ? distritosData : [0],
                    backgroundColor: ['#22d3ee', '#10b981', '#a855f7', '#f59e0b', '#38bdf8', '#f43f5e'],
                    borderRadius: 12,
                    hoverBorderWidth: 2,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { display: false } },
                    y: { beginAtZero: true, ticks: { color: '#94a3b8', stepSize: 1 }, grid: { color: '#1e293b' } }
                }
            }
        });

        const catLabels = @json($porCategoria->keys());
        const catData = @json($porCategoria->values());

        new Chart(document.getElementById('chartCategorias'), {
            type: 'doughnut',
            data: {
                labels: catLabels.length ? catLabels : ['General'],
                datasets: [{
                    data: catData.length ? catData : [1],
                    backgroundColor: ['#10b981', '#06b6d4', '#a855f7', '#f59e0b', '#ec4899', '#3b82f6'],
                    borderColor: '#0f172a',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { animateRotate: true, animateScale: true, duration: 1200 },
                plugins: { legend: { position: 'right', labels: { color: '#cbd5e1', font: { size: 11 } } } }
            }
        });

        new Chart(document.getElementById('chartCoberturaAlumnos'), {
            type: 'doughnut',
            data: {
                labels: ['Aptos 100h', 'En Progreso', 'Nuevos'],
                datasets: [{
                    data: [{{ $aptos100Count }}, {{ $enProgresoCount }}, {{ max(1, $nuevosSinHorasCount) }}],
                    backgroundColor: ['#10b981', '#22d3ee', '#f59e0b'],
                    borderColor: '#0f172a',
                    borderWidth: 3,
                    cutout: '72%',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { animateRotate: true, duration: 1100 },
                plugins: { legend: { display: false } }
            }
        });
    });

    function abrirExpedienteAlumno(al) {
        const proyectos = Array.isArray(al.historial_proyectos) ? al.historial_proyectos : [];
        const filasProyectos = proyectos.length
            ? proyectos.map(p => `
                <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                    <div class="text-left">
                        <span class="font-bold text-white block">${p.proyecto || 'Campaña'}</span>
                        <span class="text-[11px] text-slate-400">${p.organizacion || 'ONG Aliada'} • Estado: <strong class="text-cyanNeon-400">${p.estado}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-500/15 text-emerald-400 font-black">${(p.horas_validadas || p.horas || 0)} hrs</span>
                        ${p.estado === 'Completada' ? `<a href="/certificado/${p.id}" target="_blank" class="px-2.5 py-1 rounded-lg bg-brand-500 text-slate-950 font-black text-[10px]">QR</a>` : ''}
                    </div>
                </div>
            `).join('')
            : '<p class="text-xs text-slate-500 py-3 text-center">Este estudiante aún no tiene postulaciones registradas.</p>';

        Swal.fire({
            width: '720px',
            title: `Expediente 360° • ${al.name || 'Estudiante'}`,
            html: `
                <div class="text-left space-y-4 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-950 border border-slate-800 text-xs">
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">DNI / Documento</span><strong class="text-cyanNeon-400 font-mono text-sm">${al.dni || 'Sin DNI'}</strong></div>
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">Carrera</span><strong class="text-white">${al.carrera || 'Estudiante UC'}</strong></div>
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">Correo Institucional</span><strong class="text-slate-200 break-all">${al.email || ''}</strong></div>
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">Teléfono</span><strong class="text-slate-200">${al.telefono || 'No registrado'}</strong></div>
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">Distrito Cusco</span><strong class="text-slate-200">${al.distrito || 'Cusco'}</strong></div>
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">Registro</span><strong class="text-slate-200">${(al.created_at || '').toString().slice(0,10) || '2026'}</strong></div>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-950 border border-emerald-500/30 space-y-2">
                        <div class="flex justify-between text-xs font-black">
                            <span class="text-white">Progreso Meta 100 Horas Proyección Social</span>
                            <span class="text-emerald-400">${al.horas_certificadas || 0} / 100 hrs (${al.porcentaje_meta || 0}%)</span>
                        </div>
                        <div class="w-full h-3 rounded-full bg-slate-800 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-brand-500 to-emerald-400" style="width: ${al.porcentaje_meta || 0}%"></div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <span class="text-[11px] font-black uppercase text-slate-400 block">Historial de Proyectos y Certificados (${proyectos.length})</span>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">${filasProyectos}</div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 pt-3 border-t border-slate-800">
                        <button onclick="Swal.close(); abrirModalConvalidar(${al.id}, '${(al.name || '').replace(/'/g, "\\'")}')" class="px-3.5 py-2 rounded-xl bg-emerald-500 text-slate-950 font-black text-xs">+ Otorgar Horas</button>
                        <button onclick="Swal.close(); resetPasswordUsuario(${al.id}, '${(al.name || '').replace(/'/g, "\\'")}')" class="px-3.5 py-2 rounded-xl bg-amberGlow-500/20 text-amberGlow-300 border border-amberGlow-500/40 font-bold text-xs">🔑 Clave Temporal</button>
                        <button onclick="Swal.close(); eliminarUsuarioAdmin(${al.id}, '${(al.name || '').replace(/'/g, "\\'")}')" class="px-3.5 py-2 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/40 font-bold text-xs">🗑️ Eliminar</button>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            background: '#0f172a',
            color: '#fff'
        });
    }

    function abrirExpedienteOng(ong) {
        const campanas = Array.isArray(ong.campanas_lista) ? ong.campanas_lista : [];
        const filasCampanas = campanas.length
            ? campanas.map(c => `
                <div class="p-3 rounded-xl bg-slate-950 border border-slate-800 flex items-center justify-between text-xs">
                    <div>
                        <span class="font-bold text-white block">${c.titulo}</span>
                        <span class="text-[11px] text-slate-400">${c.distrito || 'Cusco'} • ${c.horas} hrs • ${c.cupos} cupos</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400">${c.estado || 'Activa'}</span>
                </div>
            `).join('')
            : '<p class="text-xs text-slate-500 py-3 text-center">Esta ONG aún no ha publicado convocatorias.</p>';

        Swal.fire({
            width: '700px',
            title: `Expediente Institucional • ${ong.name || 'ONG'}`,
            html: `
                <div class="text-left space-y-4 pt-2">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-950 border border-slate-800 text-xs">
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">RUC SUNAT</span><strong class="text-amberGlow-400 font-mono text-sm">${ong.ruc || 'Sin RUC'}</strong></div>
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">Sede Cusco</span><strong class="text-white">${ong.distrito || 'Cusco'}</strong></div>
                        <div><span class="text-slate-500 block text-[10px] uppercase font-bold">Correo</span><strong class="text-cyanNeon-400 break-all">${ong.email || ''}</strong></div>
                    </div>

                    <div class="space-y-2">
                        <span class="text-[11px] font-black uppercase text-slate-400 block">Convocatorias Publicadas (${campanas.length})</span>
                        <div class="space-y-2 max-h-44 overflow-y-auto">${filasCampanas}</div>
                    </div>

                    <div class="flex flex-wrap justify-end gap-2 pt-3 border-t border-slate-800">
                        <button onclick="Swal.close(); toggleEstadoOng(${ong.id})" class="px-3.5 py-2 rounded-xl bg-emerald-500 text-slate-950 font-black text-xs">Cambiar Estado Verificación</button>
                        <button onclick="Swal.close(); resetPasswordUsuario(${ong.id}, '${(ong.name || '').replace(/'/g, "\\'")}')" class="px-3.5 py-2 rounded-xl bg-amberGlow-500/20 text-amberGlow-300 font-bold text-xs">🔑 Clave Temporal</button>
                        <button onclick="Swal.close(); eliminarUsuarioAdmin(${ong.id}, '${(ong.name || '').replace(/'/g, "\\'")}')" class="px-3.5 py-2 rounded-xl bg-rose-500/20 text-rose-400 font-bold text-xs">🗑️ Eliminar</button>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            background: '#0f172a',
            color: '#fff'
        });
    }

    function toggleEstadoOng(id) {
        fetch(`/admin/ong/${id}/estado`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(r => r.json()).then(data => {
            Swal.fire({ title: 'Estado Actualizado', text: data.message, icon: 'success', timer: 1200, showConfirmButton: false, background: '#0f172a', color: '#fff' })
                .then(() => window.location.reload());
        });
    }

    function resetPasswordUsuario(id, nombre) {
        Swal.fire({
            title: `¿Generar nueva clave para ${nombre}?`,
            text: 'Se generará una contraseña temporal encriptada con Bcrypt para entregársela al usuario.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            confirmButtonText: 'Generar Clave Temporal',
            cancelButtonText: 'Cancelar',
            background: '#0f172a',
            color: '#fff'
        }).then(res => {
            if (res.isConfirmed) {
                fetch(`/admin/usuario/${id}/reset-password`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.ok) {
                        Swal.fire({
                            title: 'Clave Temporal Generada',
                            html: `
                                <div class="text-left space-y-3 pt-2">
                                    <p class="text-xs text-slate-300">Entrega estas credenciales a <strong>${data.usuario}</strong>:</p>
                                    <div class="p-3.5 rounded-2xl bg-slate-950 border border-amberGlow-500/40 space-y-1.5 font-mono text-xs">
                                        <div><span class="text-slate-400">Correo:</span> <span class="text-cyanNeon-400">${data.email}</span></div>
                                        <div><span class="text-slate-400">Nueva Clave:</span> <span class="text-amberGlow-400 font-black text-sm">${data.clave_temporal}</span></div>
                                    </div>
                                </div>
                            `,
                            confirmButtonText: 'Copiar Clave y Cerrar',
                            confirmButtonColor: '#10b981',
                            background: '#0f172a',
                            color: '#fff'
                        }).then(() => { if (navigator.clipboard) navigator.clipboard.writeText(data.clave_temporal); });
                    }
                });
            }
        });
    }

    function abrirModalConvalidar(userId = '', nombreAlumno = '') {
        const opcionesAlumnos = `@foreach($voluntarios as $v)<option value="{{ $v->id }}">{{ $v->name }} (DNI: {{ $v->dni ?? '---' }})</option>@endforeach`;

        Swal.fire({
            title: 'Convalidación Oficial de Horas',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Estudiante Universitario *</label>
                        <select id="adm-conv-user" class="w-full px-3 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">${opcionesAlumnos}</select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Actividad / Convenio Institucional *</label>
                        <input type="text" id="adm-conv-proyecto" value="Proyección Social Institucional - Universidad Continental" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Horas a Convalidar *</label>
                        <input type="number" id="adm-conv-horas" value="25" min="1" max="100" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-emerald-500/40 text-emerald-400 font-black text-sm">
                    </div>
                </div>
            `,
            didOpen: () => { if (userId) document.getElementById('adm-conv-user').value = userId; },
            showCancelButton: true,
            confirmButtonText: 'Emitir Certificado QR',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#10b981',
            background: '#0f172a',
            color: '#fff',
            preConfirm: () => {
                return fetch('{{ route("admin.convalidar.horas") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        user_id: document.getElementById('adm-conv-user').value,
                        proyecto: document.getElementById('adm-conv-proyecto').value,
                        horas: document.getElementById('adm-conv-horas').value
                    })
                }).then(r => r.json());
            }
        }).then(res => {
            if (res.isConfirmed && res.value.ok) {
                Swal.fire({ title: '¡Convalidado!', text: res.value.message, icon: 'success', background: '#0f172a', color: '#fff' })
                    .then(() => window.location.reload());
            }
        });
    }

    function abrirModalNuevaOng() {
        Swal.fire({
            title: 'Registrar Nueva ONG Aliada',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Razón Social / Nombre ONG *</label>
                        <input type="text" id="adm-ong-name" placeholder="Ej. Fundación Andina Cusco" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">RUC SUNAT (Prefijo 20/10 + 9 dígitos) *</label>
                        <div class="grid grid-cols-12 gap-2">
                            <select id="adm-ong-prefijo" class="col-span-5 px-2.5 py-2 rounded-xl bg-slate-950 border border-slate-800 text-amberGlow-400 font-bold text-xs">
                                <option value="20">20 • Jurídica</option>
                                <option value="10">10 • Natural</option>
                            </select>
                            <input type="text" id="adm-ong-resto" maxlength="9" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,9)" placeholder="9 dígitos" class="col-span-7 px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white font-mono text-xs">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Distrito Sede</label>
                            <input type="text" id="adm-ong-distrito" value="Wanchaq - Cusco" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Correo Institucional *</label>
                            <input type="email" id="adm-ong-email" placeholder="contacto@ong.org" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Crear Entidad Verificada',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#f59e0b',
            background: '#0f172a',
            color: '#fff',
            preConfirm: () => {
                const rucCompleto = document.getElementById('adm-ong-prefijo').value + document.getElementById('adm-ong-resto').value;
                return fetch('{{ route("admin.crear.ong") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        name: document.getElementById('adm-ong-name').value,
                        ruc: rucCompleto,
                        distrito: document.getElementById('adm-ong-distrito').value,
                        email: document.getElementById('adm-ong-email').value
                    })
                }).then(r => r.json());
            }
        }).then(res => {
            if (res.isConfirmed) {
                if (res.value.ok) {
                    Swal.fire({ title: '¡ONG Registrada!', text: res.value.message, icon: 'success', background: '#0f172a', color: '#fff' })
                        .then(() => window.location.reload());
                } else {
                    Swal.fire({ title: 'Atención', text: res.value.message, icon: 'warning', background: '#0f172a', color: '#fff' });
                }
            }
        });
    }

    function abrirModalComunicado() {
        Swal.fire({
            title: 'Enviar Comunicado Institucional',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <p class="text-xs text-slate-400">Este aviso aparecerá en la bandeja de notificaciones de todos los usuarios registrados.</p>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Asunto del Comunicado *</label>
                        <input type="text" id="adm-com-titulo" placeholder="Ej. Cierre de convalidación Semestre 2026-II" class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Mensaje Oficial *</label>
                        <textarea id="adm-com-msg" rows="3" placeholder="Estimados estudiantes y organizaciones..." class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Difundir Ahora',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#06b6d4',
            background: '#0f172a',
            color: '#fff',
            preConfirm: () => {
                return fetch('{{ route("admin.comunicado") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        titulo: document.getElementById('adm-com-titulo').value,
                        mensaje: document.getElementById('adm-com-msg').value
                    })
                }).then(r => r.json());
            }
        }).then(res => {
            if (res.isConfirmed && res.value.ok) {
                Swal.fire({ title: '¡Comunicado Enviado!', text: res.value.message, icon: 'success', background: '#0f172a', color: '#fff' });
            }
        });
    }

    function abrirModalVerificadorQr() {
        Swal.fire({
            title: 'Verificador Oficial de Código QR',
            html: `
                <div class="text-left space-y-3 pt-2">
                    <p class="text-xs text-slate-400">Ingresa el código impreso en el diploma para abrir su acta pública de verificación.</p>
                    <input type="text" id="adm-qr-code" placeholder="Ej. VM-2026-A1B2C3" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-cyanNeon-500/40 text-cyanNeon-400 font-mono text-sm uppercase">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Verificar Autenticidad',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#06b6d4',
            background: '#0f172a',
            color: '#fff'
        }).then(res => {
            if (res.isConfirmed) {
                const code = document.getElementById('adm-qr-code').value.trim();
                if (code) window.open(`/verificar/${encodeURIComponent(code)}`, '_blank');
            }
        });
    }

    function toggleCampanaAdmin(id) {
        fetch(`/admin/campana/${id}/toggle`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(r => r.json()).then(data => {
            Swal.fire({ title: 'Moderación Aplicada', text: data.message, icon: 'success', timer: 1200, showConfirmButton: false, background: '#0f172a', color: '#fff' })
                .then(() => window.location.reload());
        });
    }

    function eliminarCampanaAdmin(id, titulo) {
        Swal.fire({
            title: `¿Eliminar campaña "${titulo}"?`,
            text: 'Se eliminará permanentemente del portal.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#0f172a',
            color: '#fff'
        }).then(res => {
            if (res.isConfirmed) {
                fetch(`/admin/campana/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(() => window.location.reload());
            }
        });
    }

    function eliminarUsuarioAdmin(id, nombre) {
        Swal.fire({
            title: `¿Eliminar cuenta de ${nombre}?`,
            text: 'Esta acción retirará al usuario del padrón del sistema.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#0f172a',
            color: '#fff'
        }).then(res => {
            if (res.isConfirmed) {
                fetch(`/admin/usuario/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                }).then(() => window.location.reload());
            }
        });
    }
</script>
@endsection