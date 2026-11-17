@extends('layouts.app')

@section('content')
@php
    $horasTotalesReales = $horasTotalesReales ?? 0;
    $ongsRealesCount = $ongsRealesCount ?? 6;
    $campanasActivasReales = $campanasActivasReales ?? 3;
    $destacadasDb = $destacadasDb ?? collect();
@endphp

<!-- FONDO PARALLAX DE LA SECCIÓN HERO -->
<div class="hero-bg-image" id="bg-parallax-layer"></div>

<style>
    .hero-bg-image {
        position: fixed; inset: 0;
        background-image: url('https://images.unsplash.com/photo-1526392060635-9d6019884377?auto=format&fit=crop&w=2200&q=85');
        background-size: cover; background-position: center 35%;
        filter: brightness(0.22) contrast(1.2) saturate(1.15);
        pointer-events: none; z-index: 0;
        transition: transform 0.25s ease-out;
    }

    /* ESTILOS DEL CUBO 3D GSAP + RADAR NEÓN */
    .cube-stage {
        position: relative;
        width: 340px; height: 340px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto;
    }

    /* Ondas de Pulso Radar detrás del Cubo 3D */
    .radar-ring {
        position: absolute;
        border-radius: 9999px;
        border: 1.5px solid rgba(52, 211, 153, 0.45);
        box-shadow: 0 0 25px rgba(16, 185, 129, 0.25), inset 0 0 20px rgba(34, 211, 238, 0.15);
        pointer-events: none;
        animation: radarPulseWave 3.8s cubic-bezier(0.16, 1, 0.3, 1) infinite;
    }
    .radar-ring:nth-child(2) { animation-delay: 1.25s; border-color: rgba(34, 211, 238, 0.4); }
    .radar-ring:nth-child(3) { animation-delay: 2.5s; border-color: rgba(52, 211, 153, 0.3); }

    @keyframes radarPulseWave {
        0% { width: 180px; height: 180px; opacity: 0.9; transform: scale(0.85); }
        100% { width: 430px; height: 430px; opacity: 0; transform: scale(1.15); }
    }

    .cube-wrapper {
        width: 270px; height: 270px; perspective: 1400px;
        position: relative; z-index: 10; cursor: grab;
    }
    .cube-wrapper:active { cursor: grabbing; }

    #cube {
        height: 100%; width: 100%; position: absolute; transform-style: preserve-3d;
    }

    .face {
        position: absolute; height: 100%; width: 100%; user-select: none;
        border-radius: 1.5rem; border: 2px solid rgba(52, 211, 153, 0.55);
        box-shadow: 0 0 35px rgba(16, 185, 129, 0.3), inset 0 0 18px rgba(52, 211, 153, 0.2);
        background-size: cover; background-position: center;
    }

    /* Efecto Linterna (Spotlight) que sigue el mouse en tarjetas */
    .spotlight-card { position: relative; overflow: hidden; }
    .spotlight-card::before {
        content: ''; position: absolute;
        top: var(--mouse-y, -250px); left: var(--mouse-x, -250px);
        width: 380px; height: 380px;
        background: radial-gradient(circle, rgba(52, 211, 153, 0.28) 0%, rgba(56, 189, 248, 0.14) 45%, transparent 70%);
        transform: translate(-50%, -50%); pointer-events: none; transition: opacity 0.3s ease; opacity: 0; z-index: 2;
    }
    .spotlight-card:hover::before { opacity: 1; }

    /* Onda expansiva al hacer clic (Ripple) */
    .click-ripple {
        position: fixed;
        border-radius: 50%;
        border: 2px solid rgba(52, 211, 153, 0.8);
        background: radial-gradient(circle, rgba(34, 211, 238, 0.25), transparent 70%);
        pointer-events: none;
        z-index: 9999;
        transform: translate(-50%, -50%) scale(0);
        animation: clickRippleAnim 0.75s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes clickRippleAnim {
        0% { width: 10px; height: 10px; opacity: 1; transform: translate(-50%, -50%) scale(0.3); }
        100% { width: 150px; height: 150px; opacity: 0; transform: translate(-50%, -50%) scale(1.4); }
    }

    @keyframes shimmer {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    .text-gradient-shimmer {
        background: linear-gradient(90deg, #34d399 0%, #ffffff 50%, #38bdf8 100%);
        background-size: 200% auto; -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        animation: shimmer 4s linear infinite;
    }
</style>

<!-- RESPLANDORES NEÓN Y PARTÍCULAS INTERACTIVAS EN EL FONDO -->
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-950/65 to-slate-950/92"></div>
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1100px] h-[600px] bg-brand-500/12 rounded-full blur-[180px]"></div>
    <canvas id="canvas-home-particulas" class="w-full h-full"></canvas>
</div>

<div class="relative z-10">

    <!-- HERO PRINCIPAL CON CUBO 3D Y PULSO RADAR -->
    <section class="relative min-h-[85vh] flex items-center justify-center px-6 py-12 overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center w-full">
            
            <div class="lg:col-span-7 space-y-7 text-left z-20">
                <div class="gsap-bounce-reveal inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-brand-500/15 border border-brand-500/30 text-brand-300 text-xs font-black uppercase tracking-widest shadow-xl backdrop-blur-md">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-400 animate-ping"></span>
                    Ecosistema Universitario • Región Cusco 2026
                </div>

                <h1 class="gsap-bounce-reveal text-4xl sm:text-6xl md:text-7xl font-black text-white tracking-tight leading-[1.08]">
                    Conecta tu Talento con <br class="hidden sm:inline">
                    <span class="text-gradient-shimmer">Causas Reales en Cusco</span>
                </h1>

                <p class="gsap-bounce-reveal text-slate-300 text-base sm:text-lg max-w-2xl leading-relaxed font-light">
                    Articulación directa entre estudiantes universitarios y ONGs verificadas del Cusco. Desarrolla proyectos con impacto social, registra tus jornadas y obtén certificados digitales con código QR.
                </p>

                <!-- ACCESOS RÁPIDOS POR ÁREA DE IMPACTO + BOTÓN CATÁLOGO -->
                <div class="gsap-bounce-reveal space-y-3 pt-1">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('oportunidades') }}" class="btn-shine px-7 py-4 rounded-2xl bg-gradient-to-r from-brand-500 via-emerald-400 to-cyanNeon-400 text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-xl shadow-brand-500/25 hover:scale-105 transition-all inline-flex items-center gap-2">
                            <i class="bi bi-grid-fill text-base"></i> Explorar Convocatorias Activas
                        </a>
                        <a href="{{ route('proceso') }}" class="px-6 py-4 rounded-2xl bg-slate-900/90 hover:bg-slate-800 border border-white/15 hover:border-brand-400/50 text-white font-bold text-xs sm:text-sm transition-all hover:scale-105 inline-flex items-center gap-2">
                            <i class="bi bi-book-half text-brand-400"></i> Ver Guía de Uso
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-2 text-[11px]">
                        <span class="font-mono text-slate-400 mr-1">Áreas activas:</span>
                        <a href="{{ route('oportunidades') }}?categoria=Medio Ambiente" class="px-3 py-1 rounded-full bg-white/[0.04] hover:bg-brand-500/20 border border-white/10 hover:border-brand-500/40 text-slate-300 hover:text-brand-300 font-semibold transition-all hover:scale-105">Medio Ambiente</a>
                        <a href="{{ route('oportunidades') }}?categoria=Tecnología" class="px-3 py-1 rounded-full bg-white/[0.04] hover:bg-cyanNeon-500/20 border border-white/10 hover:border-cyanNeon-500/40 text-slate-300 hover:text-cyanNeon-300 font-semibold transition-all hover:scale-105">Tecnología</a>
                        <a href="{{ route('oportunidades') }}?categoria=Educación" class="px-3 py-1 rounded-full bg-white/[0.04] hover:bg-amberGlow-500/20 border border-white/10 hover:border-amberGlow-500/40 text-slate-300 hover:text-amberGlow-300 font-semibold transition-all hover:scale-105">Educación</a>
                        <a href="{{ route('oportunidades') }}?categoria=Salud" class="px-3 py-1 rounded-full bg-white/[0.04] hover:bg-rose-500/20 border border-white/10 hover:border-rose-500/40 text-slate-300 hover:text-rose-300 font-semibold transition-all hover:scale-105">Salud &amp; Bienestar</a>
                    </div>
                </div>

                <!-- MÉTRICAS REALES EN TIEMPO REAL DEL SISTEMA -->
                <div class="gsap-bounce-reveal grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-white/10 text-center">
                    <div class="glass-card spotlight-card p-3.5 rounded-2xl hover:-translate-y-1">
                        <span class="font-mono block text-xl sm:text-2xl font-black text-brand-400">{{ $horasTotalesReales > 0 ? $horasTotalesReales . ' hrs' : '100%' }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $horasTotalesReales > 0 ? 'Horas Validadas' : 'Trazabilidad' }}</span>
                    </div>
                    <div class="glass-card spotlight-card p-3.5 rounded-2xl hover:-translate-y-1">
                        <span class="font-mono block text-xl sm:text-2xl font-black text-cyanNeon-400">{{ max(6, $ongsRealesCount) }}+</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">ONGs Aliadas</span>
                    </div>
                    <div class="glass-card spotlight-card p-3.5 rounded-2xl hover:-translate-y-1">
                        <span class="font-mono block text-xl sm:text-2xl font-black text-amberGlow-400">{{ max(3, $campanasActivasReales) }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Campañas Activas</span>
                    </div>
                    <div class="glass-card spotlight-card p-3.5 rounded-2xl hover:-translate-y-1">
                        <span class="font-mono block text-xl sm:text-2xl font-black text-brand-300">QR 24/7</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase">Verificación</span>
                    </div>
                </div>
            </div>

            <!-- CUBO 3D GSAP INTERACTIVO CON ONDAS RADAR -->
            <div class="lg:col-span-5 relative flex items-center justify-center py-6">
                <div class="cube-stage">
                    <div class="radar-ring"></div>
                    <div class="radar-ring"></div>
                    <div class="radar-ring"></div>
                    <div class="cube-wrapper" title="Mueve el mouse para rotar el cubo 3D">
                        <div id="cube">
                            <div class="face"></div>
                            <div class="face"></div>
                            <div class="face"></div>
                            <div class="face"></div>
                            <div class="face"></div>
                            <div class="face"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CINTA CONTINUA DE ORGANIZACIONES ALIADAS EN CUSCO -->
    <section class="py-8 bg-slate-950/85 border-y border-white/[0.08] relative w-screen left-1/2 -translate-x-1/2 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 mb-4 text-center">
            <span class="font-mono text-[11px] font-bold uppercase tracking-widest text-slate-400">
                <i class="bi bi-shield-check text-brand-400 me-1"></i> Organizaciones e Instituciones Aliadas en la Región Cusco
            </span>
        </div>
        
        <div class="marquee-wrapper w-full overflow-hidden">
            <div class="marquee-track flex items-center gap-8 pr-8 py-2">
                @for($i = 0; $i < 2; $i++)
                    <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-brand-400/50 transition-colors text-slate-200 font-bold text-xs"><i class="bi bi-tree-fill text-brand-400 text-lg"></i> EcoAndina Cusco</div>
                    <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-cyanNeon-400/50 transition-colors text-slate-200 font-bold text-xs"><i class="bi bi-house-heart-fill text-cyanNeon-400 text-lg"></i> Hogar Azul Wasi</div>
                    <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-amberGlow-400/50 transition-colors text-slate-200 font-bold text-xs"><i class="bi bi-book-half text-amberGlow-400 text-lg"></i> Biblioteca Cultural Cusco</div>
                    <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-brand-300/50 transition-colors text-slate-200 font-bold text-xs"><i class="bi bi-emoji-smile-fill text-brand-300 text-lg"></i> Kusi Wasi Niñez</div>
                    <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-rose-400/50 transition-colors text-slate-200 font-bold text-xs"><i class="bi bi-heart-pulse-fill text-rose-400 text-lg"></i> Llaqta Sana Wanchaq</div>
                    <div class="flex items-center gap-3 px-6 py-3 rounded-2xl bg-slate-900/90 border border-white/10 hover:border-purple-400/50 transition-colors text-slate-200 font-bold text-xs"><i class="bi bi-flower1 text-purple-400 text-lg"></i> Manos Artesanas San Sebastián</div>
                @endfor
            </div>
        </div>
    </section>

    <!-- SECCIÓN SOBRE NOSOTROS CON LOGO UNIVERSIDAD CONTINENTAL -->
    <section id="nosotros-section" class="py-20 px-6 relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-6 gsap-3d-reveal">
                <span class="font-mono text-brand-400 font-bold text-xs uppercase tracking-widest block"><i class="bi bi-award-fill me-1"></i> Propósito Académico y Social</span>
                <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight">
                    ¿Por qué nació <span class="text-brand-400">VoluntMatch Cusco</span>?
                </h2>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Desarrollada por estudiantes de <strong class="text-white">Ingeniería de Sistemas e Informática</strong> de la <strong class="text-brand-400">Universidad Continental (Filial Cusco)</strong> para eliminar la informalidad en las convocatorias de Proyección Social y garantizar constancias digitales verificables con código QR.
                </p>
                <div class="space-y-3 pt-2">
                    <div class="glass-card spotlight-card p-4 rounded-2xl border border-white/10 flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center shrink-0 text-xl"><i class="bi bi-check-circle-fill"></i></div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Articulación Académica Real</h4>
                            <p class="text-xs text-slate-400">Conectamos el perfil profesional del estudiante con convocatorias técnicas en los 6 distritos de Cusco.</p>
                        </div>
                    </div>
                    <div class="glass-card spotlight-card p-4 rounded-2xl border border-white/10 flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-cyanNeon-500/20 text-cyanNeon-400 flex items-center justify-center shrink-0 text-xl"><i class="bi bi-patch-check-fill"></i></div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Certificación Digital con Código QR</h4>
                            <p class="text-xs text-slate-400">Cada diploma y constancia consolidada cuenta con validación pública en línea 24/7.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 gsap-3d-reveal">
                <a href="{{ route('nosotros') }}" class="block glass-card spotlight-card p-8 rounded-3xl border border-white/10 hover:border-brand-500/50 space-y-6 cursor-pointer group" data-tilt data-tilt-max="4">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Logo_Universidad_Continental.png/240px-Logo_Universidad_Continental.png"
                                     onerror="this.src='https://i.postimg.cc/43YJt89w/logo-removebg-preview.png'"
                                     alt="Logo Universidad Continental" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <span class="text-xs font-black uppercase text-white block">Universidad Continental</span>
                                <span class="text-[11px] text-brand-400 font-semibold">Filial Cusco • Ing. de Sistemas</span>
                            </div>
                        </div>
                        <span class="px-3.5 py-2 rounded-xl bg-white/[0.05] group-hover:bg-brand-500 group-hover:text-slate-950 text-xs font-bold text-slate-200 transition-all">
                            Conocer Equipo →
                        </span>
                    </div>
                    <div class="space-y-4 text-xs text-slate-300 leading-relaxed">
                        <p>
                            <strong class="text-white">VoluntMatch Cusco</strong> integra arquitectura MVC en Laravel, base de datos relacional MySQL, geolocalización interactiva con Leaflet Maps y verificación criptográfica por QR para respaldar cada hora cumplida.
                        </p>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-4 rounded-2xl bg-slate-950 border border-white/10 text-center">
                                <span class="font-mono block text-xl font-black text-brand-400 mb-0.5">100 Hrs</span>
                                <span class="text-[10px] text-slate-400 uppercase font-bold">Meta Universitaria</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-slate-950 border border-white/10 text-center">
                                <span class="font-mono block text-xl font-black text-cyanNeon-400 mb-0.5">2 Perfiles</span>
                                <span class="text-[10px] text-slate-400 uppercase font-bold">Voluntario &amp; ONG</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- SECCIÓN CÓMO UTILIZAR LA PLATAFORMA (TARJETAS 100% CLICABLES) -->
    <section id="proceso-section" class="py-20 px-6 bg-slate-900/55 border-y border-white/[0.08] relative">
        <div class="max-w-7xl mx-auto space-y-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 gsap-3d-reveal">
                <div>
                    <span class="font-mono text-brand-400 font-bold text-xs uppercase tracking-widest block mb-2"><i class="bi bi-compass-fill me-1"></i> Flujo Dual del Sistema</span>
                    <h2 class="text-3xl sm:text-5xl font-black text-white">¿Cómo Utilizar la Plataforma?</h2>
                    <p class="text-slate-400 text-sm mt-2">Haz clic en tu perfil para crear tu cuenta al instante en Cusco.</p>
                </div>
                <a href="{{ route('proceso') }}" class="px-5 py-3 rounded-xl bg-brand-500/15 hover:bg-brand-500 text-brand-300 hover:text-slate-950 border border-brand-500/35 font-black text-xs uppercase tracking-wider transition-all shrink-0 w-fit">
                    Abrir Guía Completa Paso a Paso <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- TARJETA VOLUNTARIO CLICABLE COMPLETA -->
                <a href="{{ route('registro-voluntario') }}" class="block glass-card spotlight-card p-8 rounded-3xl border border-brand-500/30 hover:border-brand-400 space-y-6 gsap-3d-reveal cursor-pointer group hover:-translate-y-1.5" data-tilt data-tilt-max="4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-lg bg-brand-500/20 text-brand-400 font-mono text-xs font-black uppercase">Perfil 01 • Estudiante</span>
                        <i class="bi bi-mortarboard-fill text-2xl text-brand-400 group-hover:scale-125 group-hover:rotate-6 transition-transform"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white group-hover:text-brand-300 transition-colors">Para Voluntarios Universitarios</h3>
                    <ol class="space-y-3 text-xs sm:text-sm text-slate-300">
                        <li class="flex gap-3"><span class="font-mono font-bold text-brand-400">01.</span> Regístrate con tu <strong>DNI de 8 dígitos</strong> y carrera profesional.</li>
                        <li class="flex gap-3"><span class="font-mono font-bold text-brand-400">02.</span> Explora campañas con fechas exactas, mapa y reloj regresivo de cierre.</li>
                        <li class="flex gap-3"><span class="font-mono font-bold text-brand-400">03.</span> Envía tu ficha académica, acumula horas en bitácora y descarga tu <strong>Certificado con QR</strong>.</li>
                    </ol>
                    <div class="pt-2">
                        <span class="btn-shine inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-brand-500 group-hover:bg-brand-400 text-slate-950 font-black text-xs uppercase transition-all shadow-lg shadow-brand-500/20">
                            Crear Cuenta Voluntario <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>
                </a>

                <!-- TARJETA ORGANIZACIÓN / ONG CLICABLE COMPLETA -->
                <a href="{{ route('registro-organizacion') }}" class="block glass-card spotlight-card p-8 rounded-3xl border border-amberGlow-500/30 hover:border-amberGlow-400 space-y-6 gsap-3d-reveal cursor-pointer group hover:-translate-y-1.5" data-tilt data-tilt-max="4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-lg bg-amberGlow-500/20 text-amberGlow-400 font-mono text-xs font-black uppercase">Perfil 02 • Organización</span>
                        <i class="bi bi-building-check text-2xl text-amberGlow-400 group-hover:scale-125 group-hover:rotate-6 transition-transform"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white group-hover:text-amberGlow-300 transition-colors">Para Empresas, Albergues y ONGs</h3>
                    <ol class="space-y-3 text-xs sm:text-sm text-slate-300">
                        <li class="flex gap-3"><span class="font-mono font-bold text-amberGlow-400">01.</span> Registra tu entidad con <strong>RUC institucional (11 dígitos)</strong> y distrito sede en Cusco.</li>
                        <li class="flex gap-3"><span class="font-mono font-bold text-amberGlow-400">02.</span> Publica campañas con vacantes automáticas, portada y punto GPS en mapa.</li>
                        <li class="flex gap-3"><span class="font-mono font-bold text-amberGlow-400">03.</span> Audita expedientes, suma horas por jornada y emite certificados oficiales con QR.</li>
                    </ol>
                    <div class="pt-2">
                        <span class="btn-shine inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-amberGlow-400 group-hover:bg-amberGlow-300 text-slate-950 font-black text-xs uppercase transition-all shadow-lg shadow-amberGlow-500/20">
                            Registrar Organización / ONG <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE CONVOCATORIAS DESTACADAS (TARJETAS 100% CLICABLES AL DETALLE DE CADA MISIÓN) -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="mb-12 flex justify-between items-end gsap-3d-reveal">
                <div>
                    <span class="font-mono text-brand-400 font-bold text-xs uppercase tracking-widest block mb-2">Convocatorias Recientes</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white">Misiones Destacadas en Cusco</h2>
                </div>
                <a href="{{ route('oportunidades') }}" class="text-brand-400 hover:text-brand-300 text-xs font-bold flex items-center gap-1">
                    Ver todo el catálogo <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @if($destacadasDb->isNotEmpty())
                    @foreach($destacadasDb as $camp)
                        <a href="{{ route('detalle-oportunidad', ['id' => $camp->id + 1000]) }}" class="glass-card spotlight-card rounded-3xl overflow-hidden p-6 flex flex-col justify-between gsap-3d-reveal cursor-pointer group hover:-translate-y-1.5" data-tilt data-tilt-max="6">
                            <div>
                                <div class="relative rounded-2xl overflow-hidden mb-4 h-48">
                                    <img src="{{ $camp->imagen ?: 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=800&q=80' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-black bg-slate-950/80 text-brand-400 uppercase border border-brand-500/30">{{ $camp->categoria }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-white group-hover:text-brand-300 transition-colors mb-1">{{ $camp->titulo }}</h3>
                                <span class="text-xs font-semibold text-brand-400 block mb-3"><i class="bi bi-building me-1"></i> {{ $camp->ong_nombre }}</span>
                                <p class="text-slate-400 text-xs leading-relaxed line-clamp-2">{{ $camp->descripcion }}</p>
                            </div>
                            <div class="pt-5 mt-5 border-t border-slate-800/80 flex items-center justify-between">
                                <span class="text-xs text-slate-400"><i class="bi bi-geo-alt text-brand-400"></i> {{ $camp->distrito }}</span>
                                <span class="btn-shine py-2.5 px-5 rounded-xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs group-hover:scale-105 transition-all flex items-center gap-1 shadow-lg shadow-brand-500/20">
                                    <i class="bi bi-eye-fill"></i> Ver Detalle / Postular
                                </span>
                            </div>
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('detalle-oportunidad', ['id' => 1]) }}" class="glass-card spotlight-card rounded-3xl overflow-hidden p-6 flex flex-col justify-between gsap-3d-reveal cursor-pointer group hover:-translate-y-1.5" data-tilt data-tilt-max="6">
                        <div>
                            <div class="relative rounded-2xl overflow-hidden mb-4 h-48">
                                <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-black bg-slate-950/80 text-brand-400 uppercase border border-brand-500/30">Medio Ambiente</span>
                            </div>
                            <h3 class="text-lg font-bold text-white group-hover:text-brand-300 transition-colors mb-1">Reforestación e Impacto Ambiental en Valle Sagrado</h3>
                            <span class="text-xs font-semibold text-brand-400 block mb-3"><i class="bi bi-building me-1"></i> EcoAndina Cusco</span>
                            <p class="text-slate-400 text-xs leading-relaxed">Apoyo activo en la plantación de especies nativas para la recuperación de microcuencas.</p>
                        </div>
                        <div class="pt-5 mt-5 border-t border-slate-800/80 flex items-center justify-between">
                            <span class="text-xs text-slate-400"><i class="bi bi-geo-alt text-brand-400"></i> San Jerónimo</span>
                            <span class="btn-shine py-2.5 px-5 rounded-xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs group-hover:scale-105 transition-all flex items-center gap-1 shadow-lg shadow-brand-500/20">
                                <i class="bi bi-eye-fill"></i> Ver Detalle / Postular
                            </span>
                        </div>
                    </a>

                    <a href="{{ route('detalle-oportunidad', ['id' => 2]) }}" class="glass-card spotlight-card rounded-3xl overflow-hidden p-6 flex flex-col justify-between gsap-3d-reveal cursor-pointer group hover:-translate-y-1.5" data-tilt data-tilt-max="6">
                        <div>
                            <div class="relative rounded-2xl overflow-hidden mb-4 h-48">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-black bg-slate-950/80 text-cyanNeon-400 uppercase border border-cyanNeon-500/30">Tecnología</span>
                            </div>
                            <h3 class="text-lg font-bold text-white group-hover:text-cyanNeon-300 transition-colors mb-1">Soporte TI y Digitalización de Archivos Históricos</h3>
                            <span class="text-xs font-semibold text-brand-400 block mb-3"><i class="bi bi-building me-1"></i> Biblioteca Cultural Cusco</span>
                            <p class="text-slate-400 text-xs leading-relaxed">Desarrollo de módulos web para catalogar documentos coloniales e implementación de backup.</p>
                        </div>
                        <div class="pt-5 mt-5 border-t border-slate-800/80 flex items-center justify-between">
                            <span class="text-xs text-slate-400"><i class="bi bi-geo-alt text-brand-400"></i> Cusco Centro</span>
                            <span class="btn-shine py-2.5 px-5 rounded-xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs group-hover:scale-105 transition-all flex items-center gap-1 shadow-lg shadow-brand-500/20">
                                <i class="bi bi-eye-fill"></i> Ver Detalle / Postular
                            </span>
                        </div>
                    </a>

                    <a href="{{ route('detalle-oportunidad', ['id' => 3]) }}" class="glass-card spotlight-card rounded-3xl overflow-hidden p-6 flex flex-col justify-between gsap-3d-reveal cursor-pointer group hover:-translate-y-1.5" data-tilt data-tilt-max="6">
                        <div>
                            <div class="relative rounded-2xl overflow-hidden mb-4 h-48">
                                <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-black bg-slate-950/80 text-amberGlow-400 uppercase border border-amberGlow-500/30">Educación</span>
                            </div>
                            <h3 class="text-lg font-bold text-white group-hover:text-amberGlow-300 transition-colors mb-1">Tutoría Académica y Refuerzo Escolar</h3>
                            <span class="text-xs font-semibold text-brand-400 block mb-3"><i class="bi bi-building me-1"></i> Kusi Wasi Niñez</span>
                            <p class="text-slate-400 text-xs leading-relaxed">Acompañamiento a niños de primaria en competencias de lectoescritura y matemática.</p>
                        </div>
                        <div class="pt-5 mt-5 border-t border-slate-800/80 flex items-center justify-between">
                            <span class="text-xs text-slate-400"><i class="bi bi-geo-alt text-brand-400"></i> Santiago</span>
                            <span class="btn-shine py-2.5 px-5 rounded-xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs group-hover:scale-105 transition-all flex items-center gap-1 shadow-lg shadow-brand-500/20">
                                <i class="bi bi-eye-fill"></i> Ver Detalle / Postular
                            </span>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <!-- SECCIÓN MAPA LEAFLET + RELOJ OFICIAL CUSCO + CLIMA -->
    <section class="py-20 px-6 bg-slate-900/50 border-t border-white/[0.08]">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-5 space-y-5 gsap-3d-reveal">
                <span class="font-mono text-brand-400 font-bold text-xs uppercase tracking-widest block"><i class="bi bi-broadcast me-1"></i> Telemetría &amp; Geolocalización Cusco</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white">Ubica las Misiones cerca de tu Sede</h2>
                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                    Sincronización en vivo con servicios meteorológicos, reloj oficial de Perú (<code class="text-cyanNeon-400">America/Lima</code>) y cartografía de Cusco.
                </p>

                <div class="glass-card spotlight-card p-5 rounded-2xl border border-cyanNeon-500/30 flex items-center justify-between">
                    <div>
                        <span class="font-mono text-[10px] font-bold uppercase text-cyanNeon-400 block">Hora Oficial Cusco (America/Lima)</span>
                        <h4 class="font-mono text-2xl font-black text-white mt-0.5" id="api-reloj-hora-grande">--:--:--</h4>
                        <span class="text-xs text-slate-400 block mt-0.5" id="api-reloj-fecha-texto">Sincronizando zona horaria...</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-cyanNeon-500/20 text-cyanNeon-400 flex items-center justify-center text-2xl">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>

                <div class="glass-card spotlight-card p-5 rounded-2xl border border-brand-500/30 flex items-center justify-between">
                    <div>
                        <span class="font-mono text-[10px] font-bold uppercase text-brand-400 block">Clima en Vivo Cusco (Open-Meteo API)</span>
                        <h4 class="font-mono text-2xl font-black text-white mt-0.5" id="clima-temp-home">15.4 °C</h4>
                        <span class="text-xs text-slate-400 block mt-0.5" id="clima-estado-home">Estación Cusco Centro • Altitud: 3,399 m</span>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center text-2xl">
                        <i class="bi bi-cloud-sun-fill"></i>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 glass-card p-4 rounded-3xl border border-white/10 gsap-3d-reveal">
                <div id="mapa-home-cusco" class="h-80 sm:h-[410px] rounded-2xl z-10 border border-slate-800"></div>
            </div>
        </div>
    </section>

</div>

<script>
    let rots = [ 
        { ry: 0, rx: 0 }, { ry: 90, rx: 0 }, { ry: 180, rx: 0 },
        { ry: 270, rx: 0 }, { ry: 0, rx: 90 }, { ry: 0, rx: -90 }
    ];

    const cubeImages = [
        'https://images.unsplash.com/photo-1526392060635-9d6019884377?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80',
        'https://images.unsplash.com/photo-1509099836639-18ba1795216d?auto=format&fit=crop&w=600&q=80'
    ];

    // Cubo 3D Interactivo + Parallax de Fondo con movimiento del Mouse
    const bgLayer = document.getElementById('bg-parallax-layer');
    let autoSpinAngle = 0;
    let mouseMovedRecently = false;
    let idleTimer = null;

    gsap.timeline()
        .set(".face", {
            rotateY: (i) => rots[i].ry,
            rotateX: (i) => rots[i].rx,
            transformOrigin: "50% 50% -135px",
            z: 135,
            background: (i) => `url(${cubeImages[i]}) center/cover`
        })
        .fromTo('.cube-wrapper',
            { scale: 0.5, opacity: 0, rotationZ: -25 },
            { scale: 1, opacity: 1, rotationZ: 0, duration: 1.1, ease: "elastic.out(1, 0.55)" }
        )
        .add(function() {
            window.addEventListener('mousemove', (e) => {
                mouseMovedRecently = true;
                clearTimeout(idleTimer);
                idleTimer = setTimeout(() => { mouseMovedRecently = false; }, 2500);

                let winPercent = { x: e.clientX / window.innerWidth, y: e.clientY / window.innerHeight };
                gsap.to('#cube', {
                    duration: 1.1,
                    rotationX: -180 + 360 * winPercent.y,
                    rotationY: -180 + 360 * winPercent.x,
                    ease: "power2.out"
                });

                if (bgLayer) {
                    const moveX = (winPercent.x - 0.5) * -28;
                    const moveY = (winPercent.y - 0.5) * -28;
                    bgLayer.style.transform = `scale(1.06) translate(${moveX}px, ${moveY}px)`;
                }
            });

            // Rotación suave automática cuando el usuario no mueve el mouse
            setInterval(() => {
                if (!mouseMovedRecently) {
                    autoSpinAngle += 18;
                    gsap.to('#cube', {
                        duration: 1.8,
                        rotationY: autoSpinAngle,
                        rotationX: Math.sin(autoSpinAngle * 0.03) * 22,
                        ease: "sine.inOut"
                    });
                }
            }, 900);
        });

    // Animaciones de entrada con rebote (Bounce / Elastic)
    gsap.fromTo('.gsap-bounce-reveal',
        { opacity: 0, y: 38, scale: 0.94 },
        { opacity: 1, y: 0, scale: 1, duration: 0.9, stagger: 0.12, ease: "back.out(1.6)" }
    );

    gsap.registerPlugin(ScrollTrigger);
    gsap.utils.toArray('.gsap-3d-reveal').forEach((elem) => {
        gsap.fromTo(elem,
            { opacity: 0, y: 40, scale: 0.96 },
            { opacity: 1, y: 0, scale: 1, duration: 0.85, ease: "back.out(1.35)", scrollTrigger: { trigger: elem, start: "top 88%" } }
        );
    });

    // Onda expansiva neón al hacer clic en cualquier parte
    document.addEventListener('click', (e) => {
        const ripple = document.createElement('span');
        ripple.className = 'click-ripple';
        ripple.style.left = e.clientX + 'px';
        ripple.style.top = e.clientY + 'px';
        document.body.appendChild(ripple);
        setTimeout(() => ripple.remove(), 800);
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll('[data-tilt]'));
        }

        function actualizarRelojCusco() {
            const ahora = new Date();
            const horaStr = new Intl.DateTimeFormat('es-PE', {
                timeZone: 'America/Lima', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
            }).format(ahora);
            const fechaStr = new Intl.DateTimeFormat('es-PE', {
                timeZone: 'America/Lima', weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
            }).format(ahora);

            const elHora = document.getElementById('api-reloj-hora-grande');
            const elFecha = document.getElementById('api-reloj-fecha-texto');
            if (elHora) elHora.textContent = horaStr;
            if (elFecha) elFecha.textContent = fechaStr.charAt(0).toUpperCase() + fechaStr.slice(1);
        }
        actualizarRelojCusco();
        setInterval(actualizarRelojCusco, 1000);

        const map = L.map('mapa-home-cusco').setView([-13.5240, -71.9550], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([-13.5167, -71.9781]).addTo(map).bindPopup('<b>Cusco Centro</b><br>Sede Principal &amp; Biblioteca Cultural').openPopup();
        L.marker([-13.5365, -71.9080]).addTo(map).bindPopup('<b>Universidad Continental</b><br>Filial Cusco');

        fetch('https://api.open-meteo.com/v1/forecast?latitude=-13.5167&longitude=-71.9781&current_weather=true')
            .then(res => res.json())
            .then(data => {
                if (data.current_weather) {
                    document.getElementById('clima-temp-home').textContent = data.current_weather.temperature + ' °C';
                    document.getElementById('clima-estado-home').textContent = 'Viento: ' + data.current_weather.windspeed + ' km/h • Altitud: 3,399 m';
                }
            })
            .catch(() => {});

        // Red de Partículas Reactivas al Cursor
        const canvas = document.getElementById('canvas-home-particulas');
        if (canvas) {
            const ctx = canvas.getContext('2d');
            let w, h, pts = [];
            let mouse = { x: -9999, y: -9999 };
            window.addEventListener('mousemove', e => { mouse.x = e.clientX; mouse.y = e.clientY; });

            function resize() { w = canvas.width = window.innerWidth; h = canvas.height = window.innerHeight; }
            window.addEventListener('resize', resize); resize();
            for (let i = 0; i < 48; i++) {
                pts.push({ x: Math.random() * w, y: Math.random() * h, vx: (Math.random() - 0.5) * 0.55, vy: (Math.random() - 0.5) * 0.55, r: Math.random() * 2 + 1 });
            }
            function loop() {
                ctx.clearRect(0, 0, w, h);
                pts.forEach((p, i) => {
                    p.x += p.vx; p.y += p.vy;
                    if (p.x < 0 || p.x > w) p.vx *= -1;
                    if (p.y < 0 || p.y > h) p.vy *= -1;

                    // Atracción / conexión magnética con el mouse
                    let dm = Math.hypot(p.x - mouse.x, p.y - mouse.y);
                    if (dm < 170) {
                        ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(mouse.x, mouse.y);
                        ctx.strokeStyle = `rgba(52, 211, 153, ${0.32 * (1 - dm / 170)})`;
                        ctx.lineWidth = 1.2;
                        ctx.stroke();
                    }

                    ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(52, 211, 153, 0.45)'; ctx.fill();

                    for (let j = i + 1; j < pts.length; j++) {
                        let d = Math.hypot(p.x - pts[j].x, p.y - pts[j].y);
                        if (d < 135) {
                            ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(pts[j].x, pts[j].y);
                            ctx.strokeStyle = `rgba(56, 189, 248, ${0.14 * (1 - d / 135)})`;
                            ctx.lineWidth = 0.8;
                            ctx.stroke();
                        }
                    }
                });
                requestAnimationFrame(loop);
            }
            loop();
        }
    });

    document.querySelectorAll('.spotlight-card').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            card.style.setProperty('--mouse-x', `${e.clientX - rect.left}px`);
            card.style.setProperty('--mouse-y', `${e.clientY - rect.top}px`);
        });
    });
</script>
@endsection