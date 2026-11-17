@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden min-h-screen">

    <!-- ========================================================================= -->
    <!-- SECCIÓN 1: HERO PANORÁMICO CON IMAGEN DE APRENDIZAJE + PARTÍCULAS 3D      -->
    <!-- ========================================================================= -->
    <section class="relative w-full min-h-[430px] flex items-center border-b border-white/10 overflow-hidden">
        <!-- Fotografía relacionada a planificación y mentoría universitaria -->
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1800&q=85"
             alt="Guía de Uso VoluntMatch Cusco"
             class="absolute inset-0 w-full h-full object-cover object-center opacity-30 scale-105">
        
        <!-- Degradado cinematográfico + Malla técnica -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#05070d] via-[#05070d]/85 to-[#05070d]/65"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#05070d] via-transparent to-[#05070d]/70"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#34d399 1px, transparent 1px); background-size: 28px 28px;"></div>

        <!-- Canvas Interactivo de Partículas Conectadas en el Hero -->
        <canvas id="canvas-hero-guia" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-16 w-full gsap-3d-reveal">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                <div class="lg:col-span-8 space-y-5">
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-brand-500/15 border border-brand-500/35 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-brand-400 animate-ping"></span>
                        <span class="font-mono text-[11px] font-bold text-brand-300 uppercase tracking-widest">
                            Manual Interactivo del Sistema • 2026
                        </span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-[1.06] drop-shadow-2xl">
                        Guía de Uso <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-emerald-300 to-cyanNeon-400">Paso a Paso</span> para operar en Cusco.
                    </h1>
                </div>

                <div class="lg:col-span-4 p-6 rounded-2xl bg-slate-950/80 backdrop-blur-xl border border-white/10 space-y-3 shadow-2xl">
                    <span class="font-mono text-[11px] text-cyanNeon-400 uppercase tracking-wider block font-bold">
                        <i class="bi bi-diagram-3-fill me-1"></i> Flujo Dual Certificado
                    </span>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Selecciona tu rol (Estudiante o Institución ONG) para visualizar cada etapa desde el registro hasta la emisión del diploma con código QR.
                    </p>
                    <div class="pt-2 border-t border-white/10 flex items-center justify-between font-mono text-[11px] text-slate-400">
                        <span>5 Etapas Clave</span>
                        <span class="text-brand-400 font-bold">100% Trazable</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FONDO ANIMADO INFERIOR CON PARTÍCULAS -->
    <div class="pointer-events-none fixed inset-0 z-0">
        <div id="glow-guia-izq" class="absolute top-1/3 -left-32 w-[520px] h-[520px] bg-brand-500/12 rounded-full blur-[160px] transition-colors duration-700"></div>
        <div id="glow-guia-der" class="absolute bottom-10 -right-32 w-[520px] h-[520px] bg-cyanNeon-500/12 rounded-full blur-[160px] transition-colors duration-700"></div>
        <canvas id="canvas-guia-particulas" class="w-full h-full"></canvas>
    </div>

    <!-- ========================================================================= -->
    <!-- SECCIÓN 2: SELECTOR DE PERFIL Y LÍNEA DE TIEMPO INTERACTIVA               -->
    <!-- ========================================================================= -->
    <div class="relative z-10 max-w-5xl mx-auto px-6 py-14 space-y-12">

        <!-- SELECTOR DE 2 VERSIONES CON COLORES DIFERENCIADOS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl">
            <button id="tab-btn-voluntario" onclick="cambiarGuia('voluntario')"
                    class="p-4 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-left shadow-xl shadow-brand-500/25 transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-950/15 flex items-center justify-center text-xl">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <div>
                        <span class="block font-mono text-[10px] uppercase opacity-80">Perfil 01</span>
                        <span class="text-sm font-black">Voluntario / Estudiante</span>
                    </div>
                </div>
                <i class="bi bi-check-circle-fill text-lg"></i>
            </button>

            <button id="tab-btn-ong" onclick="cambiarGuia('ong')"
                    class="p-4 rounded-2xl bg-slate-900/90 border border-amberGlow-500/35 hover:border-amberGlow-400 text-slate-200 font-bold text-left transition-all flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amberGlow-500/20 text-amberGlow-400 flex items-center justify-center text-xl">
                        <i class="bi bi-building-check"></i>
                    </div>
                    <div>
                        <span class="block font-mono text-[10px] uppercase text-amberGlow-400">Perfil 02</span>
                        <span class="text-sm font-black text-white">Empresa / ONG</span>
                    </div>
                </div>
                <i class="bi bi-arrow-right-circle text-lg text-amberGlow-400 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>

        <!-- PERFIL 01: VOLUNTARIO / ESTUDIANTE -->
        <div id="guia-voluntario" class="space-y-8">
            <div class="p-6 rounded-3xl bg-gradient-to-r from-brand-500/15 via-slate-900/90 to-cyanNeon-500/10 border border-brand-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xl">
                <div class="space-y-1">
                    <span class="font-mono text-[11px] font-bold text-brand-400 uppercase tracking-wider">Ruta del Estudiante Universitario</span>
                    <h2 class="text-xl font-black text-white">Del registro con DNI al Certificado Oficial con QR</h2>
                    <p class="text-xs text-slate-300">Convalida tus 100 horas de Proyección Social en ONGs verificadas de Cusco.</p>
                </div>
                <a href="{{ route('registro-voluntario') }}"
                   class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 hover:from-brand-400 hover:to-emerald-300 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-brand-500/30 hover:scale-105 transition-all inline-flex items-center justify-center gap-2 shrink-0">
                    <i class="bi bi-person-plus-fill text-base"></i> Ir al Registro de Voluntario
                </a>
            </div>

            <div class="relative border-l-2 border-brand-500/35 ml-4 space-y-8 pl-8 sm:pl-10">
                <!-- Paso 01 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-brand-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-brand-500 ring-4 ring-[#05070d] shadow-lg shadow-brand-500/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-brand-500/20 text-brand-400 font-mono text-xs font-black">PASO 01</span>
                            <h3 class="text-lg font-black text-white">Registro Académico con Validación de DNI</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Registrarse → Soy Voluntario</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Completa tus nombres completos, <strong class="text-white">DNI de 8 dígitos</strong>, carrera profesional y correo electrónico. Estos datos quedan vinculados a tu perfil académico y se imprimen automáticamente en todos tus diplomas y constancias oficiales.
                    </p>
                </div>

                <!-- Paso 02 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-cyanNeon-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-cyanNeon-400 ring-4 ring-[#05070d] shadow-lg shadow-cyanNeon-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-cyanNeon-500/20 text-cyanNeon-400 font-mono text-xs font-black">PASO 02</span>
                            <h3 class="text-lg font-black text-white">Búsqueda en Catálogo, Fechas y Mapa de Cusco</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Menú → Oportunidades</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Filtra convocatorias por área (<span class="text-cyanNeon-300 font-semibold">Medio Ambiente, Tecnología, Educación, Salud Mental, Gestión/Marketing o Salud</span>). Al abrir una campaña verás su portada panorámica, el <strong class="text-white">rango exacto de fechas</strong>, el horario flexible, el <strong class="text-white">temporizador regresivo hasta el cierre</strong>, las vacantes libres y el mapa interactivo de Cusco.
                    </p>
                </div>

                <!-- Paso 03 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-amberGlow-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-amberGlow-400 ring-4 ring-[#05070d] shadow-lg shadow-amberGlow-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-amberGlow-500/20 text-amberGlow-400 font-mono text-xs font-black">PASO 03</span>
                            <h3 class="text-lg font-black text-white">Envío de Ficha Académica de Postulación</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Botón → Únete / Postular</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Presiona <strong class="text-brand-400">Únete / Postular</strong> para enviar tu ficha con universidad de procedencia, ciclo actual, teléfono WhatsApp, disponibilidad horaria y carta de motivación. El sistema bloquea postulaciones duplicadas y envía tu expediente a la ONG.
                    </p>
                </div>

                <!-- Paso 04 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-purple-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-purple-400 ring-4 ring-[#05070d] shadow-lg shadow-purple-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-purple-500/20 text-purple-400 font-mono text-xs font-black">PASO 04</span>
                            <h3 class="text-lg font-black text-white">Seguimiento de Bitácora y Barra de 100 Horas</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Panel → Mi Dashboard</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        En <strong class="text-white">Mi Dashboard</strong> recibes notificaciones automáticas cuando la ONG acepta tu postulación y cada vez que registra tus asistencias por jornada, viendo avanzar tu barra de progreso hacia la meta de <strong class="text-brand-400">100 horas de Proyección Social</strong>.
                    </p>
                </div>

                <!-- Paso 05 -->
                <div class="relative p-6 rounded-2xl bg-gradient-to-r from-emerald-950/60 to-slate-900/85 backdrop-blur-xl border border-emerald-500/35 space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-emerald-400 ring-4 ring-[#05070d] shadow-lg shadow-emerald-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 font-mono text-xs font-black">PASO 05</span>
                            <h3 class="text-lg font-black text-white">Descarga de Certificado Individual y Constancia Consolidada con QR</h3>
                        </div>
                        <span class="font-mono text-[11px] text-emerald-400">Validación Oficial</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Al culminar la campaña, descarga tu <strong class="text-white">Certificado Oficial con código QR único</strong> (verificable en línea por cualquier autoridad universitaria) y tu <strong class="text-white">Constancia Consolidada Acumulada</strong> lista para imprimir o guardar en PDF.
                    </p>
                </div>
            </div>
        </div>

        <!-- PERFIL 02: EMPRESA / ONG -->
        <div id="guia-ong" class="space-y-8 hidden">
            <div class="p-6 rounded-3xl bg-gradient-to-r from-amberGlow-500/15 via-slate-900/90 to-orange-500/10 border border-amberGlow-500/35 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xl">
                <div class="space-y-1">
                    <span class="font-mono text-[11px] font-bold text-amberGlow-400 uppercase tracking-wider">Ruta Institucional de Organizaciones</span>
                    <h2 class="text-xl font-black text-white">Publicación, Auditoría de Expedientes y Certificación</h2>
                    <p class="text-xs text-slate-300">Gestiona tus campañas sociales en Cusco con control automático de vacantes.</p>
                </div>
                <a href="{{ route('registro-organizacion') }}"
                   class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amberGlow-400 to-amber-500 hover:from-amberGlow-300 hover:to-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amberGlow-500/30 hover:scale-105 transition-all inline-flex items-center justify-center gap-2 shrink-0">
                    <i class="bi bi-building-add text-base"></i> Ir al Registro de Organización
                </a>
            </div>

            <div class="relative border-l-2 border-amberGlow-500/35 ml-4 space-y-8 pl-8 sm:pl-10">
                <!-- Paso 01 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-amberGlow-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-amberGlow-400 ring-4 ring-[#05070d] shadow-lg shadow-amberGlow-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-amberGlow-500/20 text-amberGlow-400 font-mono text-xs font-black">PASO 01</span>
                            <h3 class="text-lg font-black text-white">Registro Institucional con RUC y Sede en Cusco</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Registrarse → Soy Organización</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Registra la razón social de tu ONG o institución, <strong class="text-white">RUC de 11 dígitos</strong>, distrito sede en Cusco y correo oficial para activar tu <strong class="text-amberGlow-400">Panel Institucional ONG</strong>.
                    </p>
                </div>

                <!-- Paso 02 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-brand-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-brand-400 ring-4 ring-[#05070d] shadow-lg shadow-brand-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-brand-500/20 text-brand-400 font-mono text-xs font-black">PASO 02</span>
                            <h3 class="text-lg font-black text-white">Publicación de Campañas con Fechas, Temporizador y Mapa</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Botón → Publicar Nueva Campaña</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Configura título, categoría, fecha de inicio, fecha de fin, <strong class="text-white">fecha/hora límite para el reloj regresivo de cierre</strong>, vacantes disponibles, horas a certificar, portada fotográfica y ubicación exacta en el mapa de Cusco.
                    </p>
                </div>

                <!-- Paso 03 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-cyanNeon-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-cyanNeon-400 ring-4 ring-[#05070d] shadow-lg shadow-cyanNeon-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-cyanNeon-500/20 text-cyanNeon-400 font-mono text-xs font-black">PASO 03</span>
                            <h3 class="text-lg font-black text-white">Auditoría de Expedientes y Control Automático de Cupos</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Postulantes Pendientes</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Abre el expediente de cada postulante para revisar su universidad, ciclo, motivación, horas previas y reputación. Al aceptarlo (<strong class="text-brand-400">Aceptar -1 cupo</strong>), el sistema descuenta una vacante y cierra automáticamente la convocatoria al llenarse.
                    </p>
                </div>

                <!-- Paso 04 -->
                <div class="relative p-6 rounded-2xl bg-slate-900/80 backdrop-blur-xl border border-white/10 hover:border-purple-500/40 transition-all space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-purple-400 ring-4 ring-[#05070d] shadow-lg shadow-purple-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-purple-500/20 text-purple-400 font-mono text-xs font-black">PASO 04</span>
                            <h3 class="text-lg font-black text-white">Coordinación por WhatsApp y Registro de Asistencias</h3>
                        </div>
                        <span class="font-mono text-[11px] text-slate-400">Lista Oficial de Voluntarios</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Coordina directamente por <strong class="text-emerald-400">WhatsApp</strong> con tus voluntarios aceptados y registra sus horas jornada por jornada mediante <strong class="text-cyanNeon-400">+ Sumar Horas</strong>, alimentando su bitácora oficial.
                    </p>
                </div>

                <!-- Paso 05 -->
                <div class="relative p-6 rounded-2xl bg-gradient-to-r from-amberGlow-950/45 to-slate-900/85 backdrop-blur-xl border border-amberGlow-500/35 space-y-2.5">
                    <span class="absolute -left-[43px] sm:-left-[51px] top-6 w-5 h-5 rounded-full bg-emerald-400 ring-4 ring-[#05070d] shadow-lg shadow-emerald-400/50"></span>
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-300 font-mono text-xs font-black">PASO 05</span>
                            <h3 class="text-lg font-black text-white">Cierre de Campaña y Emisión de Certificado Oficial con QR</h3>
                        </div>
                        <span class="font-mono text-[11px] text-emerald-400">Finalizar y Dar Certificado</span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Confirma las horas totales cumplidas, asigna una calificación en estrellas y una reseña institucional. El sistema genera el diploma con firma digital, código único y QR verificable.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function cambiarGuia(tipo) {
        const guiaVol = document.getElementById('guia-voluntario');
        const guiaOng = document.getElementById('guia-ong');
        const btnVol = document.getElementById('tab-btn-voluntario');
        const btnOng = document.getElementById('tab-btn-ong');
        const glowIzq = document.getElementById('glow-guia-izq');
        const glowDer = document.getElementById('glow-guia-der');

        if (tipo === 'voluntario') {
            guiaVol.classList.remove('hidden');
            guiaOng.classList.add('hidden');

            btnVol.className = "p-4 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-left shadow-xl shadow-brand-500/25 transition-all flex items-center justify-between group";
            btnVol.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-950/15 flex items-center justify-center text-xl"><i class="bi bi-mortarboard-fill"></i></div>
                    <div><span class="block font-mono text-[10px] uppercase opacity-80">Perfil 01</span><span class="text-sm font-black">Voluntario / Estudiante</span></div>
                </div>
                <i class="bi bi-check-circle-fill text-lg"></i>
            `;

            btnOng.className = "p-4 rounded-2xl bg-slate-900/90 border border-amberGlow-500/35 hover:border-amberGlow-400 text-slate-200 font-bold text-left transition-all flex items-center justify-between group";
            btnOng.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amberGlow-500/20 text-amberGlow-400 flex items-center justify-center text-xl"><i class="bi bi-building-check"></i></div>
                    <div><span class="block font-mono text-[10px] uppercase text-amberGlow-400">Perfil 02</span><span class="text-sm font-black text-white">Empresa / ONG</span></div>
                </div>
                <i class="bi bi-arrow-right-circle text-lg text-amberGlow-400 group-hover:translate-x-1 transition-transform"></i>
            `;

            glowIzq.className = "absolute top-1/3 -left-32 w-[520px] h-[520px] bg-brand-500/12 rounded-full blur-[160px] transition-colors duration-700";
            glowDer.className = "absolute bottom-10 -right-32 w-[520px] h-[520px] bg-cyanNeon-500/12 rounded-full blur-[160px] transition-colors duration-700";

            if (typeof gsap !== 'undefined') {
                gsap.fromTo('#guia-voluntario > div', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.45, stagger: 0.06 });
            }
        } else {
            guiaOng.classList.remove('hidden');
            guiaVol.classList.add('hidden');

            btnOng.className = "p-4 rounded-2xl bg-gradient-to-r from-amberGlow-400 to-amber-500 text-slate-950 font-black text-left shadow-xl shadow-amberGlow-500/25 transition-all flex items-center justify-between group";
            btnOng.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-950/15 flex items-center justify-center text-xl"><i class="bi bi-building-check"></i></div>
                    <div><span class="block font-mono text-[10px] uppercase opacity-80">Perfil 02</span><span class="text-sm font-black">Empresa / ONG</span></div>
                </div>
                <i class="bi bi-check-circle-fill text-lg"></i>
            `;

            btnVol.className = "p-4 rounded-2xl bg-slate-900/90 border border-brand-500/35 hover:border-brand-400 text-slate-200 font-bold text-left transition-all flex items-center justify-between group";
            btnVol.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center text-xl"><i class="bi bi-mortarboard-fill"></i></div>
                    <div><span class="block font-mono text-[10px] uppercase text-brand-400">Perfil 01</span><span class="text-sm font-black text-white">Voluntario / Estudiante</span></div>
                </div>
                <i class="bi bi-arrow-right-circle text-lg text-brand-400 group-hover:translate-x-1 transition-transform"></i>
            `;

            glowIzq.className = "absolute top-1/3 -left-32 w-[520px] h-[520px] bg-amberGlow-500/12 rounded-full blur-[160px] transition-colors duration-700";
            glowDer.className = "absolute bottom-10 -right-32 w-[520px] h-[520px] bg-orange-500/12 rounded-full blur-[160px] transition-colors duration-700";

            if (typeof gsap !== 'undefined') {
                gsap.fromTo('#guia-ong > div', { opacity: 0, y: 18 }, { opacity: 1, y: 0, duration: 0.45, stagger: 0.06 });
            }
        }
    }

    function iniciarParticulasCanvas(id, isFullScreen, colorDot, colorLine) {
        const canvas = document.getElementById(id);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let w, h, dots = [];

        function resize() {
            w = canvas.width = isFullScreen ? window.innerWidth : canvas.offsetWidth;
            h = canvas.height = isFullScreen ? window.innerHeight : canvas.offsetHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        for (let i = 0; i < 40; i++) {
            dots.push({
                x: Math.random() * w,
                y: Math.random() * h,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                r: Math.random() * 1.9 + 0.9
            });
        }

        function draw() {
            ctx.clearRect(0, 0, w, h);
            for (let i = 0; i < dots.length; i++) {
                let d = dots[i];
                d.x += d.vx;
                d.y += d.vy;
                if (d.x < 0 || d.x > w) d.vx *= -1;
                if (d.y < 0 || d.y > h) d.vy *= -1;

                ctx.beginPath();
                ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
                ctx.fillStyle = colorDot;
                ctx.fill();

                for (let j = i + 1; j < dots.length; j++) {
                    let d2 = dots[j];
                    let dist = Math.hypot(d.x - d2.x, d.y - d2.y);
                    if (dist < 135) {
                        ctx.beginPath();
                        ctx.moveTo(d.x, d.y);
                        ctx.lineTo(d2.x, d2.y);
                        ctx.strokeStyle = `rgba(${colorLine}, ${0.15 * (1 - dist / 135)})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(draw);
        }
        draw();
    }

    document.addEventListener('DOMContentLoaded', () => {
        iniciarParticulasCanvas('canvas-hero-guia', false, 'rgba(52, 211, 153, 0.55)', '56, 189, 248');
        iniciarParticulasCanvas('canvas-guia-particulas', true, 'rgba(56, 189, 248, 0.35)', '52, 211, 153');
    });
</script>
@endsection