@extends('layouts.app')

@section('content')
@php
    $logoUC = 'https://i.postimg.cc/vDRFtQn1/Universiadad-Continental-Isotipo-removebg-preview.png';
@endphp
<div class="relative overflow-hidden">

    <!-- ========================================================================= -->
    <!-- SECCIÓN 1: HERO CON IMAGEN UNIVERSITARIA + LOGO CONTINENTAL + PARTÍCULAS  -->
    <!-- ========================================================================= -->
    <section class="relative w-full min-h-[480px] flex items-center border-b border-white/10 overflow-hidden">
        <!-- Fotografía de equipo universitario trabajando en ingeniería de software -->
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1800&q=85"
             alt="Equipo Universitario Ingeniería de Sistemas - Universidad Continental Cusco"
             class="absolute inset-0 w-full h-full object-cover object-center opacity-28 scale-105">
        
        <!-- Degradado cinematográfico + Malla técnica -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#05070d] via-[#05070d]/85 to-[#05070d]/60"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#05070d] via-transparent to-[#05070d]/70"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#34d399 1px, transparent 1px); background-size: 28px 28px;"></div>

        <!-- Canvas Interactivo de Partículas Conectadas Superior -->
        <canvas id="canvas-red-nosotros" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-20 w-full gsap-3d-reveal">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-end">
                <div class="lg:col-span-8 space-y-5">
                    <!-- Badge con Logo Institucional de la Universidad Continental -->
                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-slate-950/85 border border-brand-500/35 backdrop-blur-md shadow-xl">
                        <div class="w-8 h-8 rounded-lg bg-white p-1 flex items-center justify-center shrink-0">
                            <img src="{{ $logoUC }}"
                                 onerror="this.src='https://i.postimg.cc/43YJt89w/logo-removebg-preview.png'"
                                 alt="Universidad Continental" class="w-full h-full object-contain">
                        </div>
                        <span class="font-mono text-[11px] font-bold text-brand-300 uppercase tracking-widest">
                            Universidad Continental • Filial Cusco
                        </span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-[1.06] drop-shadow-2xl">
                        Tecnología creada en <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-emerald-300 to-cyanNeon-400">Cusco</span> para conectar el talento universitario con impacto real.
                    </h1>
                </div>

                <div class="lg:col-span-4 p-6 rounded-2xl bg-slate-950/80 backdrop-blur-xl border border-white/10 space-y-3 shadow-2xl">
                    <div class="flex items-center gap-3 pb-2 border-b border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-white p-1.5 flex items-center justify-center shrink-0">
                            <img src="{{ $logoUC }}"
                                 onerror="this.src='https://i.postimg.cc/43YJt89w/logo-removebg-preview.png'"
                                 alt="Logo UC" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="font-mono text-[10px] text-cyanNeon-400 uppercase block font-bold">Facultad de Ingeniería</span>
                            <strong class="text-xs text-white">Ing. de Sistemas e Informática</strong>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Plataforma web diseñada para digitalizar, auditar y certificar con validez académica las horas de Proyección Social en toda la Región Cusco.
                    </p>
                    <div class="pt-1 flex items-center justify-between font-mono text-[11px] text-slate-400">
                        <span>Programación Web</span>
                        <span class="text-brand-400 font-bold">v2.0 Producción</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECCIÓN 2: DIAGNÓSTICO, MISIÓN, VISIÓN Y TARJETAS BENTO DE IMPACTO        -->
    <!-- ========================================================================= -->
    <section class="relative max-w-6xl mx-auto px-6 py-16 border-b border-white/[0.08] gsap-3d-reveal">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-rose-500/15 border border-rose-500/30 font-mono text-xs text-rose-400 uppercase tracking-wider">
                    <i class="bi bi-activity"></i> 01 — Diagnóstico & Motivación
                </div>
                <h2 class="text-3xl font-black text-white tracking-tight">
                    ¿Por qué nació VoluntMatch Cusco?
                </h2>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Cada ciclo universitario, miles de estudiantes cusqueños deben cumplir <strong class="text-white">100 horas de Proyección Social</strong> aplicando los conocimientos de su carrera en beneficio de la sociedad. Sin embargo, el esquema tradicional presentaba tres problemas críticos:
                </p>

                <div class="space-y-3 pt-2">
                    <div class="p-4 rounded-2xl bg-white/[0.025] border border-white/[0.08] hover:border-rose-500/40 transition-all flex gap-4">
                        <span class="font-mono text-xs font-bold text-rose-400 bg-rose-500/10 px-2.5 py-1 rounded-lg h-fit">01</span>
                        <div>
                            <h3 class="text-sm font-bold text-white">Convocatorias dispersas y sin filtros por carrera</h3>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Los estudiantes dependían de avisos informales sin conocer horarios reales, vacantes disponibles ni ubicación exacta en Cusco.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/[0.025] border border-white/[0.08] hover:border-amberGlow-500/40 transition-all flex gap-4">
                        <span class="font-mono text-xs font-bold text-amberGlow-400 bg-amberGlow-500/10 px-2.5 py-1 rounded-lg h-fit">02</span>
                        <div>
                            <h3 class="text-sm font-bold text-white">Control manual y extravío de constancias físicas</h3>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">El registro en cuadernos físicos y papeles sin validación electrónica dificultaba acreditar las horas ante la universidad.</p>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-white/[0.025] border border-white/[0.08] hover:border-cyanNeon-500/40 transition-all flex gap-4">
                        <span class="font-mono text-xs font-bold text-cyanNeon-400 bg-cyanNeon-500/10 px-2.5 py-1 rounded-lg h-fit">03</span>
                        <div>
                            <h3 class="text-sm font-bold text-white">Brecha operativa en ONGs y albergues locales</h3>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Las organizaciones sociales de Cusco carecían de una plataforma gratuita para evaluar expedientes, controlar cupos y emitir diplomas verificables.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 flex flex-col justify-between space-y-6">
                <div class="p-6 rounded-3xl bg-gradient-to-br from-brand-500/10 via-slate-900/90 to-slate-950 border border-brand-500/30 space-y-2.5 shadow-xl">
                    <span class="font-mono text-xs text-brand-400 uppercase tracking-widest block font-bold">
                        <i class="bi bi-bullseye me-1"></i> 02 — Misión
                    </span>
                    <h3 class="text-xl font-black text-white">Articulación directa y trazable</h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Conectar a estudiantes de carreras profesionales (Sistemas, Ambiental, Educación, Psicología, Administración y Salud) con convocatorias reales en ONGs de Cusco, garantizando la convalidación segura de sus horas mediante certificados digitales con QR.
                    </p>
                </div>

                <div class="p-6 rounded-3xl bg-gradient-to-br from-cyanNeon-500/10 via-slate-900/90 to-slate-950 border border-cyanNeon-500/30 space-y-2.5 shadow-xl">
                    <span class="font-mono text-xs text-cyanNeon-400 uppercase tracking-widest block font-bold">
                        <i class="bi bi-eye-fill me-1"></i> 03 — Visión
                    </span>
                    <h3 class="text-xl font-black text-white">Estándar regional en el sur del Perú</h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Consolidarnos como la infraestructura tecnológica referente de responsabilidad social universitaria en el sur del Perú, impulsando el desarrollo sostenible, la educación y la conservación ambiental en Cusco.
                    </p>
                </div>

                <!-- CUADROS BENTO LLAMATIVOS DE MÉTRICAS -->
                <div class="grid grid-cols-3 gap-3 pt-1">
                    <div class="p-4 rounded-2xl bg-gradient-to-b from-brand-500/20 to-slate-950 border border-brand-500/40 text-center shadow-lg shadow-brand-500/10 hover:-translate-y-1 transition-transform">
                        <i class="bi bi-award-fill text-brand-400 text-lg mb-1 block"></i>
                        <span class="font-mono text-2xl font-black text-white block">100h</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-300 block mt-0.5">Meta Social</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gradient-to-b from-cyanNeon-500/20 to-slate-950 border border-cyanNeon-500/40 text-center shadow-lg shadow-cyanNeon-500/10 hover:-translate-y-1 transition-transform">
                        <i class="bi bi-qr-code-scan text-cyanNeon-400 text-lg mb-1 block"></i>
                        <span class="font-mono text-2xl font-black text-white block">QR 24/7</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-cyanNeon-300 block mt-0.5">Validación</span>
                    </div>

                    <div class="p-4 rounded-2xl bg-gradient-to-b from-amberGlow-500/20 to-slate-950 border border-amberGlow-500/40 text-center shadow-lg shadow-amberGlow-500/10 hover:-translate-y-1 transition-transform">
                        <i class="bi bi-geo-alt-fill text-amberGlow-400 text-lg mb-1 block"></i>
                        <span class="font-mono text-2xl font-black text-white block">6 Zonas</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amberGlow-300 block mt-0.5">Cusco Mapa</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECCIÓN 3: CINTA INFINITA DE BORDE A BORDE (100VW) CON LOGOTIPOS REALES     -->
    <!-- ========================================================================= -->
    <section class="py-14 border-b border-white/[0.08] bg-slate-950/90 relative w-screen left-1/2 -translate-x-1/2 overflow-hidden z-10">
        <div class="max-w-6xl mx-auto px-6 mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <span class="font-mono text-xs text-brand-400 uppercase tracking-widest font-bold">
                <i class="bi bi-layers-fill me-1.5"></i> Stack Tecnológico & Arquitectura Implementada
            </span>
            <span class="font-mono text-[11px] text-slate-500">
                Pasa el cursor para pausar • Tecnologías en producción
            </span>
        </div>

        <div class="marquee-wrapper w-full overflow-hidden py-2">
            <div class="marquee-track flex items-center gap-16 pr-16">
                @php
                    $tecnologias = [
                        ['nombre' => 'Laravel 11', 'rol' => 'Framework MVC', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/laravel/laravel-original.svg'],
                        ['nombre' => 'PHP 8.2', 'rol' => 'Backend Core', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/php/php-original.svg'],
                        ['nombre' => 'MySQL', 'rol' => 'Base de Datos Relacional', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/mysql/mysql-original.svg'],
                        ['nombre' => 'Tailwind CSS', 'rol' => 'Sistema de Diseño UI', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/tailwindcss/tailwindcss-original.svg'],
                        ['nombre' => 'JavaScript ES6', 'rol' => 'Interactividad & Tiempo Real', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/javascript/javascript-original.svg'],
                        ['nombre' => 'HTML5', 'rol' => 'Estructura Semántica', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/html5/html5-original.svg'],
                        ['nombre' => 'CSS3', 'rol' => 'Animaciones & Layout', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/css3/css3-original.svg'],
                        ['nombre' => 'Git & GitHub', 'rol' => 'Control de Versiones', 'logo' => 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/git/git-original.svg'],
                    ];
                @endphp

                @for($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                    @foreach($tecnologias as $tech)
                        <div class="flex flex-col items-center justify-center gap-3 min-w-[135px] group select-none">
                            <div class="w-16 h-16 rounded-2xl bg-white/[0.03] border border-white/[0.08] group-hover:border-brand-400/50 flex items-center justify-center transition-all duration-300 group-hover:scale-110 shadow-lg">
                                <img src="{{ $tech['logo'] }}" alt="{{ $tech['nombre'] }}" class="w-10 h-10 object-contain">
                            </div>
                            <div class="text-center">
                                <span class="block text-xs font-bold text-slate-200 group-hover:text-brand-400 transition-colors">{{ $tech['nombre'] }}</span>
                                <span class="block font-mono text-[10px] text-slate-500">{{ $tech['rol'] }}</span>
                            </div>
                        </div>
                    @endforeach
                @endfor
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- SECCIÓN 4: EQUIPO CREADOR CON ANIMACIÓN DE RED DE PARTÍCULAS EN EL FONDO  -->
    <!-- ========================================================================= -->
    <section class="relative w-full py-20 overflow-hidden">
        <!-- Canvas de Partículas Animadas en la zona inferior -->
        <canvas id="canvas-red-equipo" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
        <div class="pointer-events-none absolute bottom-10 left-1/2 -translate-x-1/2 w-[900px] h-[340px] bg-brand-500/[0.08] rounded-full blur-[160px]"></div>

        <div class="relative z-10 max-w-6xl mx-auto px-6 gsap-3d-reveal">
            <!-- Cabecera + Tarjeta Destacada del Docente y Universidad -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-12">
                <div class="space-y-2">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-amberGlow-500/15 border border-amberGlow-500/30 font-mono text-xs text-amberGlow-400 uppercase tracking-wider font-bold">
                        <i class="bi bi-terminal-fill"></i> 04 — Ingeniería & Desarrollo
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight">Equipo Creador del Sistema</h2>
                    <p class="text-xs sm:text-sm text-slate-400">
                        Carrera Profesional de <strong class="text-slate-200">Ingeniería de Sistemas e Informática</strong> • <strong class="text-brand-400">Universidad Continental — Sede Cusco</strong>
                    </p>
                </div>

                <!-- Credencial Institucional del Docente con Logo de la Universidad -->
                <div class="p-5 rounded-2xl bg-slate-950/85 backdrop-blur-xl border border-cyanNeon-500/35 flex items-center gap-4 shadow-xl shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-white p-1.5 flex items-center justify-center shrink-0">
                        <img src="{{ $logoUC }}"
                             onerror="this.src='https://i.postimg.cc/43YJt89w/logo-removebg-preview.png'"
                             alt="Universidad Continental" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="font-mono text-[10px] uppercase tracking-widest text-cyanNeon-400 font-bold block">
                            Cátedra: Programación Web • 2026
                        </span>
                        <strong class="text-base font-black text-white block">Ing. Collantes Mendoza Alfredo</strong>
                        <span class="text-[11px] text-slate-400">Docente Asesor • Filial Cusco</span>
                    </div>
                </div>
            </div>

            <!-- CREDENCIALES 3D DE LOS 4 INTEGRANTES -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $integrantes = [
                        [
                            'codigo' => 'DEV // 01',
                            'nombre' => 'Alcca Moron, Piero Edu',
                            'rol' => 'Arquitectura Full Stack & Lógica Laravel',
                            'detalle' => 'Diseño de controladores MVC, motor de estados de postulación, sistema de notificaciones en tiempo real y reglas de negocio.',
                            'acento' => 'from-brand-500 to-emerald-400',
                            'borde' => 'hover:border-brand-400/60',
                            'badge' => 'bg-brand-500/15 text-brand-300 border-brand-500/30',
                            'icono' => 'bi-braces-asterisk'
                        ],
                        [
                            'codigo' => 'DEV // 02',
                            'nombre' => 'Dueñas Quintana, Luis Angel',
                            'rol' => 'Ingeniería de Base de Datos MySQL & Seguridad',
                            'detalle' => 'Modelado relacional, migraciones estructuradas, control de integridad referencial, roles de sesión y cifrado de credenciales.',
                            'acento' => 'from-cyanNeon-500 to-blue-400',
                            'borde' => 'hover:border-cyanNeon-400/60',
                            'badge' => 'bg-cyanNeon-500/15 text-cyanNeon-300 border-cyanNeon-500/30',
                            'icono' => 'bi-database-fill-lock'
                        ],
                        [
                            'codigo' => 'DEV // 03',
                            'nombre' => 'Rojas Lopez, Alexis Sebastian',
                            'rol' => 'Diseño de Interfaz UI/UX & Experiencia Interactiva',
                            'detalle' => 'Arquitectura visual responsive, temporizadores regresivos en vivo, carruseles dinámicos y optimización de usabilidad.',
                            'acento' => 'from-amberGlow-400 to-orange-400',
                            'borde' => 'hover:border-amberGlow-400/60',
                            'badge' => 'bg-amberGlow-500/15 text-amberGlow-300 border-amberGlow-500/30',
                            'icono' => 'bi-window-stack'
                        ],
                        [
                            'codigo' => 'DEV // 04',
                            'nombre' => 'Leguia Choquemamani, Patrick Antonio',
                            'rol' => 'Geolocalización Leaflet Maps & Certificación QR',
                            'detalle' => 'Integración de mapas interactivos en Cusco, bitácoras de asistencia por jornada y emisión de diplomas verificables con QR.',
                            'acento' => 'from-purple-500 to-indigo-400',
                            'borde' => 'hover:border-purple-400/60',
                            'badge' => 'bg-purple-500/15 text-purple-300 border-purple-500/30',
                            'icono' => 'bi-qr-code'
                        ],
                    ];
                @endphp

                @foreach($integrantes as $dev)
                    <div data-tilt data-tilt-max="5" data-tilt-speed="400"
                         class="relative rounded-3xl bg-slate-900/85 backdrop-blur-xl border border-white/10 {{ $dev['borde'] }} p-7 transition-all duration-300 shadow-2xl overflow-hidden group">
                        
                        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r {{ $dev['acento'] }} opacity-75 group-hover:opacity-100 transition-opacity"></div>

                        <div class="flex items-start justify-between gap-4 mb-5">
                            <div class="space-y-1">
                                <span class="font-mono text-[11px] font-bold text-slate-500 tracking-widest block">{{ $dev['codigo'] }}</span>
                                <h3 class="text-xl font-black text-white group-hover:text-brand-300 transition-colors">{{ $dev['nombre'] }}</h3>
                            </div>
                            <div class="w-11 h-11 rounded-2xl bg-white/[0.04] border border-white/10 flex items-center justify-center text-lg text-slate-200 group-hover:scale-110 transition-transform shrink-0">
                                <i class="bi {{ $dev['icono'] }}"></i>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="inline-block px-3 py-1 rounded-lg border text-xs font-bold {{ $dev['badge'] }}">
                                {{ $dev['rol'] }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-400 leading-relaxed mb-5">
                            {{ $dev['detalle'] }}
                        </p>

                        <div class="pt-4 border-t border-white/[0.07] flex items-center justify-between text-[11px] font-mono text-slate-500">
                            <span>Ing. de Sistemas e Informática</span>
                            <span class="text-slate-300">UC • Cusco</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script>
    function iniciarCanvasRed(canvasId, colorDot, colorLine) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width, height, particles = [];

        function resize() {
            width = canvas.width = canvas.offsetWidth;
            height = canvas.height = canvas.offsetHeight;
        }
        window.addEventListener('resize', resize);
        resize();

        for (let i = 0; i < 42; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.55,
                vy: (Math.random() - 0.5) * 0.55,
                r: Math.random() * 2 + 1
            });
        }

        function animate() {
            ctx.clearRect(0, 0, w, h);
            for (let i = 0; i < particles.length; i++) {
                let p = particles[i];
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = colorDot;
                ctx.fill();

                for (let j = i + 1; j < particles.length; j++) {
                    let p2 = particles[j];
                    let dist = Math.hypot(p.x - p2.x, p.y - p2.y);
                    if (dist < 135) {
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(${colorLine}, ${0.18 * (1 - dist / 135)})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animate);
        }
        animate();
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll('[data-tilt]'));
        }
        iniciarCanvasRed('canvas-red-nosotros', 'rgba(52, 211, 153, 0.55)', '56, 189, 248');
        iniciarCanvasRed('canvas-red-equipo', 'rgba(251, 191, 36, 0.5)', '52, 211, 153');
    });
</script>
@endsection