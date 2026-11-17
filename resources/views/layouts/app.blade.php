<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VoluntMatch Cusco • Ecosistema Universitario de Proyección Social</title>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png">
    
    <!-- Tailwind CSS + Icons + SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Librerías 3D, Parallax, Tilt y Mapas Leaflet -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: { 400: '#34d399', 500: '#10b981', 600: '#059669' },
                        cyanNeon: { 400: '#22d3ee', 500: '#06b6d4' },
                        amberGlow: { 400: '#fbbf24', 500: '#f59e0b' }
                    }
                }
            }
        }
    </script>
    <style>
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #020617;
            color: #e2e8f0;
            overflow-x: hidden;
        }

        /* Tarjetas Glassmorphism con borde reactivo */
        .glass-card {
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), border-color 0.35s ease, box-shadow 0.35s ease;
        }
        .glass-card:hover {
            border-color: rgba(52, 211, 153, 0.35);
            box-shadow: 0 18px 45px -15px rgba(16, 185, 129, 0.22);
        }

        /* Cinta Marquee Continua */
        @keyframes marqueeScroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .marquee-track {
            width: max-content;
            animation: marqueeScroll 28s linear infinite;
        }
        .marquee-wrapper:hover .marquee-track {
            animation-play-state: paused;
        }

        /* Animaciones del Chatbot VoluntBot */
        @keyframes floatBot {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .bot-float {
            animation: floatBot 3.4s ease-in-out infinite;
        }

        @keyframes orbitSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .bot-orbit-ring {
            animation: orbitSpin 6s linear infinite;
        }

        @keyframes botPulseGlow {
            0%, 100% { box-shadow: 0 0 18px rgba(34, 211, 238, 0.35), 0 0 35px rgba(16, 185, 129, 0.2); }
            50% { box-shadow: 0 0 28px rgba(34, 211, 238, 0.65), 0 0 55px rgba(16, 185, 129, 0.4); }
        }
        .bot-glow-btn {
            animation: botPulseGlow 3s ease-in-out infinite;
        }

        /* Animación de entrada de ventana y mensajes del chat */
        @keyframes chatPanelOpen {
            0% { opacity: 0; transform: translateY(18px) scale(0.94); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .chat-panel-anim {
            animation: chatPanelOpen 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transform-origin: bottom right;
        }

        @keyframes msgPopIn {
            0% { opacity: 0; transform: translateY(10px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .chat-msg-anim {
            animation: msgPopIn 0.28s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        }

        /* Efecto Destello (Shine) en botones */
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -75%;
            width: 50%;
            height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transform: rotate(25deg);
            transition: left 0.65s ease;
            pointer-events: none;
        }
        .btn-shine:hover::after {
            left: 135%;
        }

        /* Subrayado animado en links del Navbar */
        .nav-link-anim {
            position: relative;
        }
        .nav-link-anim::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0%;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, #34d399, #22d3ee);
            transition: width 0.3s ease;
        }
        .nav-link-anim:hover::after,
        .nav-link-active::after {
            width: 100%;
        }

        /* Scrollbar estilizado para el chatbox */
        #chatbot-mensajes::-webkit-scrollbar { width: 5px; }
        #chatbot-mensajes::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.4); }
        #chatbot-mensajes::-webkit-scrollbar-thumb { background: rgba(52, 211, 153, 0.35); border-radius: 999px; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-950 text-slate-200 antialiased min-h-screen flex flex-col">

    <!-- HALO DE LUZ INTERACTIVO QUE SIGUE EL CURSOR -->
    <div id="cursor-ambient-glow" class="pointer-events-none fixed inset-0 z-0 transition-opacity duration-500 opacity-0 hidden md:block"></div>

    @php
        $authUser = Auth::user();
        $authRol = $authUser ? ($authUser->rol ?? $authUser->tipo_usuario ?? 'voluntario') : 'guest';
        $esAdmin = $authUser && ($authRol === 'admin' || $authUser->email === 'admin@voluntmatch.edu.pe');
        // Logo oficial exclusivo del Chatbot VoluntBot Cusco
        $botAvatarUrl = 'https://i.postimg.cc/ZnFw58cV/0e85b099-bb9b-4769-a4be-5d1932f26711-removebg-preview.png';
    @endphp

    <!-- NAVBAR PRINCIPAL COMPLETO -->
    <header class="sticky top-0 z-40 bg-slate-950/85 backdrop-blur-xl border-b border-slate-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            <!-- Logo del Sistema -->
            <a href="{{ route('index') }}" class="flex items-center gap-2.5 group shrink-0">
                <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" alt="VoluntMatch" class="w-9 h-9 object-contain group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <span class="font-black text-white text-lg tracking-tight group-hover:text-brand-300 transition-colors">VoluntMatch</span>
                <span class="px-2 py-0.5 rounded-md bg-brand-500/15 border border-brand-500/30 text-brand-400 font-mono text-[10px] font-bold">CUSCO</span>
            </a>

            <!-- Enlaces Principales -->
            <nav class="hidden md:flex items-center gap-6 text-xs font-bold text-slate-300">
                <a href="{{ route('index') }}" class="nav-link-anim {{ request()->routeIs('index') ? 'text-brand-400 nav-link-active' : 'hover:text-brand-400' }} transition-colors">Inicio</a>
                <a href="{{ route('oportunidades') }}" class="nav-link-anim {{ request()->routeIs('oportunidades') ? 'text-brand-400 nav-link-active' : 'hover:text-brand-400' }} transition-colors">Oportunidades</a>
                <a href="{{ route('nosotros') }}" class="nav-link-anim {{ request()->routeIs('nosotros') ? 'text-brand-400 nav-link-active' : 'hover:text-brand-400' }} transition-colors">Nosotros</a>
                <a href="{{ route('proceso') }}" class="nav-link-anim {{ request()->routeIs('proceso') ? 'text-brand-400 nav-link-active' : 'hover:text-brand-400' }} transition-colors">Guía de Uso</a>
                <a href="{{ route('contacto') }}" class="nav-link-anim {{ request()->routeIs('contacto') ? 'text-brand-400 nav-link-active' : 'hover:text-brand-400' }} transition-colors">Contacto</a>
            </nav>

            <!-- Reloj Cusco + Botones de Sesión / Cerrar Sesión -->
            <div class="flex items-center gap-2.5">
                <span class="hidden xl:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900 border border-slate-800 font-mono text-[11px] text-cyanNeon-400 shadow-inner">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> CUSCO • <span id="nav-reloj-cusco">{{ now()->format('H:i:s') }}</span>
                </span>

                @auth
                    <div class="flex items-center gap-2">
                        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-900/90 border border-slate-800 text-[11px] font-bold text-slate-300">
                            <i class="bi bi-person-circle text-brand-400"></i>
                            <span class="max-w-[120px] truncate">{{ $authUser->name }}</span>
                        </span>

                        <a href="{{ route('home') }}" class="btn-shine px-4 py-2 rounded-full bg-brand-500 hover:bg-brand-400 text-slate-950 font-black text-xs transition-all inline-flex items-center gap-1.5 shadow-lg shadow-brand-500/20 hover:scale-105">
                            <i class="bi bi-speedometer2"></i> Mi Panel
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" title="Cerrar Sesión" class="px-3.5 py-2 rounded-full bg-rose-500/15 hover:bg-rose-500 border border-rose-500/35 text-rose-300 hover:text-white font-bold text-xs transition-all inline-flex items-center gap-1.5 hover:scale-105">
                                <i class="bi bi-box-arrow-right"></i>
                                <span class="hidden sm:inline">Salir</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-3.5 py-2 text-xs font-bold text-slate-300 hover:text-white transition-colors">Ingresar</a>
                    <a href="{{ route('registro') }}" class="btn-shine px-4 py-2 rounded-full bg-white hover:bg-brand-400 text-slate-950 font-black text-xs transition-all hover:scale-105 shadow-lg">
                        Registrarse
                    </a>
                @endauth
            </div>
        </div>

        <!-- Menú Móvil Rápido -->
        <div class="md:hidden border-t border-slate-800/70 px-4 py-2 flex items-center justify-around text-[11px] font-bold text-slate-300 bg-slate-950/95">
            <a href="{{ route('index') }}" class="hover:text-brand-400">Inicio</a>
            <a href="{{ route('oportunidades') }}" class="hover:text-brand-400">Oportunidades</a>
            <a href="{{ route('nosotros') }}" class="hover:text-brand-400">Nosotros</a>
            <a href="{{ route('proceso') }}" class="hover:text-brand-400">Guía</a>
            <a href="{{ route('contacto') }}" class="hover:text-brand-400">Contacto</a>
        </div>
    </header>

    <!-- CONTENIDO DE CADA VISTA -->
    <main class="flex-1 relative z-10">
        @yield('content')
    </main>

    <!-- FOOTER INSTITUCIONAL -->
    <footer class="relative z-10 bg-slate-950/95 border-t border-slate-800/90 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-slate-800/80">
                
                <!-- Columna 1: Marca e Identidad -->
                <div class="space-y-4">
                    <a href="{{ route('index') }}" class="flex items-center gap-2.5 group">
                        <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" alt="VoluntMatch Cusco" class="w-9 h-9 object-contain group-hover:scale-105 transition-transform">
                        <span class="font-black text-white text-lg tracking-tight">VoluntMatch</span>
                        <span class="px-2 py-0.5 rounded-md bg-brand-500/15 border border-brand-500/30 text-brand-400 font-mono text-[10px] font-bold">CUSCO</span>
                    </a>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Ecosistema universitario de proyección social y voluntariado verificado en la Región Cusco. Conectamos talento estudiantil con ONGs aliadas mediante trazabilidad y certificación digital QR.
                    </p>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-[11px] font-mono text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Verificación QR Activa 24/7
                    </div>
                </div>

                <!-- Columna 2: Navegación -->
                <div class="space-y-3">
                    <h4 class="text-xs font-black uppercase tracking-widest text-white">Explorar Plataforma</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('index') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-chevron-right text-[10px] text-brand-400"></i> Inicio</a></li>
                        <li><a href="{{ route('oportunidades') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-chevron-right text-[10px] text-brand-400"></i> Catálogo de Convocatorias</a></li>
                        <li><a href="{{ route('nosotros') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-chevron-right text-[10px] text-brand-400"></i> Sobre Nosotros</a></li>
                        <li><a href="{{ route('proceso') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-chevron-right text-[10px] text-brand-400"></i> Guía de Uso Paso a Paso</a></li>
                        <li><a href="{{ route('contacto') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-chevron-right text-[10px] text-brand-400"></i> Mesa de Ayuda y Contacto</a></li>
                    </ul>
                </div>

                <!-- Columna 3: Accesos por Perfil -->
                <div class="space-y-3">
                    <h4 class="text-xs font-black uppercase tracking-widest text-white">Acceso al Sistema</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        @auth
                            <li><a href="{{ route('home') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-speedometer2 text-brand-400"></i> Ir a Mi Panel de Control</a></li>
                            @if($authRol === 'voluntario' || $authRol === 'estudiante')
                                <li><a href="{{ route('constancia.consolidada') }}" class="hover:text-cyanNeon-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-patch-check-fill text-cyanNeon-400"></i> Récord Consolidado 100 Horas</a></li>
                            @elseif($authRol === 'organizacion')
                                <li><a href="{{ route('ong.campana.crear') }}" class="hover:text-amberGlow-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-plus-circle-fill text-amberGlow-400"></i> Publicar Nueva Campaña</a></li>
                            @endif
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="hover:text-rose-400 hover:translate-x-1 transition-all flex items-center gap-1.5 text-left">
                                        <i class="bi bi-box-arrow-right text-rose-400"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-box-arrow-in-right text-brand-400"></i> Iniciar Sesión</a></li>
                            <li><a href="{{ route('registro-voluntario') }}" class="hover:text-cyanNeon-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-mortarboard-fill text-cyanNeon-400"></i> Registro Estudiante Voluntario</a></li>
                            <li><a href="{{ route('registro-organizacion') }}" class="hover:text-amberGlow-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-building-check text-amberGlow-400"></i> Registro Organización / ONG</a></li>
                        @endauth
                        <li><a href="{{ route('legal') }}" class="hover:text-brand-400 hover:translate-x-1 transition-all flex items-center gap-1.5"><i class="bi bi-shield-lock text-slate-500"></i> Políticas y Términos de Uso</a></li>
                    </ul>
                </div>

                <!-- Columna 4: Respaldo Institucional Cusco -->
                <div class="space-y-3">
                    <h4 class="text-xs font-black uppercase tracking-widest text-white">Respaldo Académico</h4>
                    <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 hover:border-brand-500/30 transition-colors space-y-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-white p-1 flex items-center justify-center shrink-0">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Logo_Universidad_Continental.png/240px-Logo_Universidad_Continental.png"
                                     onerror="this.src='https://i.postimg.cc/43YJt89w/logo-removebg-preview.png'"
                                     alt="Universidad Continental" class="w-full h-full object-contain">
                            </div>
                            <div>
                                <span class="text-xs font-black text-white block">Universidad Continental</span>
                                <span class="text-[10px] text-brand-400 font-semibold">Filial Cusco • Perú</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed">
                            Escuela Académico Profesional de Ingeniería de Sistemas e Informática • Proyección Social y Extensión Universitaria.
                        </p>
                    </div>
                </div>

            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
                <p>© {{ date('Y') }} <strong class="text-slate-300">VoluntMatch Cusco</strong>. Todos los derechos reservados.</p>
                <div class="flex items-center gap-4">
                    <span><i class="bi bi-geo-alt-fill text-brand-400 me-1"></i> Cusco, Perú (3,399 m s. n. m.)</span>
                    <a href="{{ route('legal') }}" class="hover:text-slate-300 transition-colors">Privacidad &amp; Términos</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- CHATBOT FLOTANTE CON TU LOGO OFICIAL Y ANIMACIONES NEÓN -->
    @if(!$esAdmin)
    <div id="chatbot-widget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <div id="chatbot-panel" class="hidden chat-panel-anim mb-3 w-[345px] sm:w-[400px] rounded-3xl bg-slate-900/95 backdrop-blur-2xl border border-cyanNeon-500/45 shadow-2xl shadow-cyanNeon-500/15 overflow-hidden">
            
            <!-- Cabecera del Chatbot con tu Logo Oficial -->
            <div class="p-4 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 border-b border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative w-11 h-11 rounded-2xl bg-gradient-to-br from-cyanNeon-500/20 to-brand-500/20 border border-cyanNeon-400/50 p-1 flex items-center justify-center shadow-lg shrink-0">
                        <span class="absolute inset-0 rounded-2xl border border-dashed border-brand-400/50 bot-orbit-ring pointer-events-none"></span>
                        <img src="{{ $botAvatarUrl }}" alt="VoluntBot Cusco" class="w-full h-full object-contain drop-shadow-[0_0_8px_rgba(34,211,238,0.55)]">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="text-xs font-black text-white tracking-tight">VoluntBot Cusco</h4>
                            <span class="px-1.5 py-0.5 rounded bg-cyanNeon-500/20 text-cyanNeon-400 font-mono text-[9px] font-bold flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span> IA EN VIVO
                            </span>
                        </div>
                        <span class="text-[10px] text-slate-400 block mt-0.5">
                            @if(!$authUser)
                                • Asistente VoluntMatch Cusco
                            @elseif($authRol === 'organizacion')
                                • Soporte Institucional ONG
                            @else
                                • Coach Académico 100 Horas
                            @endif
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="reiniciarChatPublico()" title="Limpiar chat" class="w-7 h-7 rounded-lg bg-slate-800/80 hover:bg-slate-700 text-slate-400 hover:text-cyanNeon-400 flex items-center justify-center text-xs transition-all hover:rotate-180 duration-300">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                    <button type="button" onclick="toggleChatbotPublico()" title="Cerrar" class="w-7 h-7 rounded-lg bg-slate-800/80 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 flex items-center justify-center text-xs transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Botones de Preguntas Frecuentes según Rol -->
            <div class="p-3 bg-slate-950/75 border-b border-slate-800/80 flex flex-wrap gap-1.5">
                @if(!$authUser)
                    <button type="button" onclick="enviarConsultaBot('¿Para qué sirve VoluntMatch Cusco?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-brand-500/20 border border-slate-700 hover:border-brand-400/50 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        ¿Para qué sirve este sistema?
                    </button>
                    <button type="button" onclick="enviarConsultaBot('¿Cómo crear cuenta de Voluntario y qué requisitos piden?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-cyanNeon-500/20 border border-slate-700 hover:border-cyanNeon-400/50 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        Crear cuenta Voluntario (requisitos)
                    </button>
                    <button type="button" onclick="enviarConsultaBot('¿Cómo registrar una ONG y qué RUC piden?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-amberGlow-500/20 border border-slate-700 hover:border-amberGlow-400/50 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        ¿Cómo ser ONG y qué pide?
                    </button>
                    <button type="button" onclick="enviarConsultaBot('¿Los certificados QR tienen validez universitaria?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-emerald-500/20 border border-slate-700 hover:border-emerald-400/50 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        Validez de certificados QR
                    </button>
                @elseif($authRol === 'organizacion')
                    <button type="button" onclick="enviarConsultaBot('¿Cómo publico una nueva convocatoria en Cusco?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-amberGlow-500/20 border border-slate-700 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        Publicar convocatoria
                    </button>
                    <button type="button" onclick="enviarConsultaBot('¿Cómo valido horas y emito certificados QR?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-emerald-500/20 border border-slate-700 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        Validar horas y QR
                    </button>
                    <button type="button" onclick="enviarConsultaBot('¿Cómo reviso los vouchers de donaciones?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-cyanNeon-500/20 border border-slate-700 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        Vouchers de donaciones
                    </button>
                @else
                    <button type="button" onclick="enviarConsultaBot('¿Cuántas horas me faltan para completar mis 100 horas?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-emerald-500/20 border border-slate-700 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        ¿Cuántas horas me faltan?
                    </button>
                    <button type="button" onclick="enviarConsultaBot('¿Cómo descargo mi Certificado QR o Récord Consolidado?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-cyanNeon-500/20 border border-slate-700 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        Descargar Certificado QR
                    </button>
                    <button type="button" onclick="enviarConsultaBot('¿Cómo contacto por WhatsApp a la ONG aceptada?')" class="px-2.5 py-1 rounded-full bg-slate-900 hover:bg-brand-500/20 border border-slate-700 text-[10px] font-bold text-slate-200 transition-all hover:scale-105">
                        Contactar ONG por WhatsApp
                    </button>
                @endif
            </div>

            <!-- Mensajes -->
            <div id="chatbot-mensajes" class="p-4 space-y-3 h-64 overflow-y-auto text-xs">
                <div class="chat-msg-anim p-3 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-slate-700 text-slate-200 leading-relaxed">
                    @if(!$authUser)
                        ¡Hola! Soy <strong class="text-cyanNeon-400">VoluntBot Cusco</strong>. Puedo explicarte para qué sirve la plataforma, qué requisitos necesitas para registrarte como <strong>Voluntario</strong> o como <strong>ONG</strong>, y cómo funcionan los certificados QR.
                    @elseif($authRol === 'organizacion')
                        Hola, <strong class="text-amberGlow-400">{{ $authUser->name }}</strong>. Soy <strong class="text-cyanNeon-400">VoluntBot Cusco</strong>; te ayudo a publicar campañas, gestionar postulantes, registrar avances de horas y emitir certificados QR.
                    @else
                        ¡Hola, <strong class="text-brand-400">{{ $authUser->name }}</strong>! Soy <strong class="text-cyanNeon-400">VoluntBot Cusco</strong>. Consúltame sobre tus horas acumuladas, campañas en Cusco o descarga de certificados QR.
                    @endif
                </div>
            </div>

            <!-- Formulario -->
            <form onsubmit="handleChatPublicoSubmit(event)" class="p-3 border-t border-slate-800 bg-slate-950/90 flex items-center gap-2">
                <input type="text" id="chatbot-input" required
                       placeholder="{{ !$authUser ? 'Pregunta sobre VoluntMatch Cusco...' : ($authRol === 'organizacion' ? 'Ej. ¿Cómo emito un certificado QR?' : 'Ej. ¿Cómo llego a mis 100 horas?') }}"
                       class="flex-1 px-3.5 py-2.5 rounded-full bg-slate-900 border border-slate-700 text-white text-xs focus:border-cyanNeon-400 focus:outline-none transition-colors">
                <button type="submit" id="chatbot-send-btn" class="btn-shine w-9 h-9 rounded-full bg-gradient-to-r from-cyanNeon-400 to-brand-400 text-slate-950 font-black flex items-center justify-center shrink-0 hover:scale-110 transition-transform shadow-lg shadow-cyanNeon-500/25">
                    <i class="bi bi-send-fill text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Botón Flotante Animado con tu Logo Oficial del Chatbot -->
        <button id="chatbot-toggle" type="button" onclick="toggleChatbotPublico()" title="Abrir VoluntBot Cusco"
                class="bot-float bot-glow-btn group flex items-center gap-2.5 pl-2.5 pr-4 py-2 rounded-full bg-slate-950/95 border-2 border-cyanNeon-400/80 hover:scale-105 transition-transform duration-300">
            <span class="relative flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-br from-cyanNeon-500/25 to-brand-500/25 border border-cyanNeon-400/50 p-1">
                <span class="absolute inset-0 rounded-full border border-dashed border-brand-400/60 bot-orbit-ring pointer-events-none"></span>
                <img src="{{ $botAvatarUrl }}" alt="VoluntBot Cusco" class="w-full h-full object-contain group-hover:rotate-12 transition-transform duration-300 drop-shadow-[0_0_8px_rgba(34,211,238,0.6)]">
                <span class="absolute -top-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-slate-950 animate-pulse"></span>
            </span>
            <div class="text-left">
                <span class="block text-xs font-black text-white leading-none group-hover:text-cyanNeon-300 transition-colors">VoluntBot</span>
                <span class="block text-[9px] font-mono font-bold text-cyanNeon-400 mt-0.5">CUSCO AI</span>
            </div>
        </button>
    </div>

    <script>
        // Reloj en vivo de Cusco
        setInterval(() => {
            const el = document.getElementById('nav-reloj-cusco');
            if (el) {
                el.textContent = new Intl.DateTimeFormat('es-PE', {
                    timeZone: 'America/Lima', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                }).format(new Date());
            }
        }, 1000);

        // Halo ambiental interactivo del cursor
        const ambientGlow = document.getElementById('cursor-ambient-glow');
        if (ambientGlow) {
            window.addEventListener('mousemove', (e) => {
                ambientGlow.style.opacity = '1';
                ambientGlow.style.background = `radial-gradient(650px circle at ${e.clientX}px ${e.clientY}px, rgba(16, 185, 129, 0.065), rgba(34, 211, 238, 0.035) 40%, transparent 75%)`;
            });
        }

        function toggleChatbotPublico() {
            const panel = document.getElementById('chatbot-panel');
            panel.classList.toggle('hidden');
            if (!panel.classList.contains('hidden')) {
                document.getElementById('chatbot-input').focus();
            }
        }

        function reiniciarChatPublico() {
            const box = document.getElementById('chatbot-mensajes');
            box.innerHTML = `<div class="chat-msg-anim p-3 rounded-2xl rounded-tl-sm bg-slate-800/90 border border-slate-700 text-slate-200">Conversación reiniciada. ¿En qué tema de <strong class="text-cyanNeon-400">VoluntMatch Cusco</strong> te ayudo ahora?</div>`;
        }

        function handleChatPublicoSubmit(e) {
            e.preventDefault();
            const inp = document.getElementById('chatbot-input');
            const texto = inp.value.trim();
            if (!texto) return;
            inp.value = '';
            enviarConsultaBot(texto);
        }

        async function enviarConsultaBot(pregunta) {
            const box = document.getElementById('chatbot-mensajes');
            const btn = document.getElementById('chatbot-send-btn');

            const userDiv = document.createElement('div');
            userDiv.className = 'flex justify-end chat-msg-anim';
            userDiv.innerHTML = `<div class="max-w-[85%] px-3.5 py-2.5 rounded-2xl rounded-tr-sm bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-semibold shadow-md">${escapeBotHtml(pregunta)}</div>`;
            box.appendChild(userDiv);

            const typing = document.createElement('div');
            typing.className = 'flex justify-start chat-msg-anim';
            typing.innerHTML = `<div class="px-3.5 py-2 rounded-2xl bg-slate-800 border border-cyanNeon-500/30 text-cyanNeon-400 font-mono text-[11px] flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-cyanNeon-400 animate-bounce"></span><span class="w-1.5 h-1.5 rounded-full bg-brand-400 animate-bounce" style="animation-delay:0.15s"></span><span class="w-1.5 h-1.5 rounded-full bg-cyanNeon-400 animate-bounce" style="animation-delay:0.3s"></span> Consultando IA Cusco...</div>`;
            box.appendChild(typing);
            box.scrollTop = box.scrollHeight;
            btn.disabled = true;

            try {
                const res = await fetch("{{ route('chatbot.preguntar') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ pregunta: pregunta })
                });
                const data = await res.json();
                typing.remove();

                const raw = data.respuesta || data.reply || 'No se pudo procesar la consulta en este momento.';
                const textoResp = raw
                    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                    .replace(/\*\*(.*?)\*\*/g, '<strong class="text-cyanNeon-400">$1</strong>')
                    .replace(/\n/g, '<br>');

                const botDiv = document.createElement('div');
                botDiv.className = 'flex justify-start chat-msg-anim';
                botDiv.innerHTML = `<div class="max-w-[90%] px-3.5 py-2.5 rounded-2xl rounded-tl-sm bg-slate-800/95 border border-slate-700/90 text-slate-100 leading-relaxed shadow-md">${textoResp}</div>`;
                box.appendChild(botDiv);
                box.scrollTop = box.scrollHeight;
            } catch (err) {
                typing.remove();
            } finally {
                btn.disabled = false;
            }
        }

        function escapeBotHtml(str) {
            const d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }
    </script>
    @endif
    @stack('scripts')
</body>
</html>