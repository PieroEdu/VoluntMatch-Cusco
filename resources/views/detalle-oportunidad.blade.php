@extends('layouts.app')

@section('content')
@php
    $cerrada = ($oportunidad['estado'] === 'Cerrada' || $oportunidad['cupos_disponibles'] <= 0);
@endphp

<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/85 via-slate-950/75 to-slate-950/95"></div>
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1100px] h-[500px] bg-brand-500/15 rounded-full blur-[180px]"></div>
</div>

<!-- HERO BANNER PANORÁMICO GIGANTE -->
<section class="relative z-10 w-full h-[380px] sm:h-[460px] overflow-hidden border-b border-slate-800">
    <img src="{{ $oportunidad['imagen'] }}" alt="{{ $oportunidad['titulo'] }}" class="w-full h-full object-cover object-center {{ $cerrada ? 'grayscale' : '' }}">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/55 to-slate-950/30"></div>

    <div class="absolute inset-0 max-w-7xl mx-auto px-6 py-8 flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <a href="{{ route('oportunidades') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-950/75 backdrop-blur-md border border-slate-700/80 text-xs font-bold text-slate-200 hover:text-brand-400 hover:border-brand-500/40 transition-all">
                <i class="bi bi-arrow-left"></i> Volver a Oportunidades
            </a>

            <span class="px-3.5 py-1.5 rounded-full text-xs font-black bg-slate-950/80 backdrop-blur-md text-brand-400 border border-brand-500/40 uppercase tracking-wider">
                {{ $oportunidad['categoria'] }}
            </span>
        </div>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-2">
            <div class="space-y-2 max-w-3xl">
                <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight drop-shadow-lg leading-tight">
                    {{ $oportunidad['titulo'] }}
                </h1>
                <p class="text-sm sm:text-base font-bold text-brand-300 underline decoration-brand-400/60 underline-offset-4 flex items-center gap-2">
                    <i class="bi bi-building"></i> {{ $oportunidad['ong'] }}
                </p>
            </div>

            <div class="inline-flex items-center gap-2.5 px-5 py-3 rounded-2xl bg-slate-950/85 backdrop-blur-xl border {{ $cerrada ? 'border-rose-500/50 text-rose-300' : 'border-brand-500/40 text-white' }} shadow-2xl shrink-0">
                <i class="bi {{ $cerrada ? 'bi-heartbreak-fill text-rose-400' : 'bi-heart-fill text-rose-400 animate-pulse' }} text-lg"></i>
                @if($cerrada)
                    <span class="text-xs sm:text-sm font-black uppercase">0 cupos disponibles (Cerrada)</span>
                @else
                    <span class="text-xs sm:text-sm font-bold">
                        <strong class="text-brand-400 text-base">{{ $oportunidad['cupos_disponibles'] }}</strong> cupos disponibles de <strong class="text-white">{{ $oportunidad['cupos_totales'] }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CONTENIDO PRINCIPAL -->
<main class="relative z-10 max-w-7xl mx-auto px-6 py-12 w-full space-y-12">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- COLUMNA IZQUIERDA -->
        <div class="lg:col-span-7 space-y-8">
            
            <div class="glass-card p-6 rounded-3xl border border-slate-800 space-y-4">
                <div class="flex items-center gap-3 text-sm text-slate-200 font-semibold">
                    <div class="w-9 h-9 rounded-xl bg-rose-500/15 text-rose-400 flex items-center justify-center shrink-0">
                        <i class="bi bi-calendar-range-fill text-base"></i>
                    </div>
                    <span>{{ $oportunidad['rango_fechas'] }}</span>
                </div>

                <div class="flex items-center gap-3 text-sm text-slate-200 font-semibold">
                    <div class="w-9 h-9 rounded-xl bg-amberGlow-500/15 text-amberGlow-400 flex items-center justify-center shrink-0">
                        <i class="bi bi-clock-fill text-base"></i>
                    </div>
                    <span>{{ $oportunidad['horario'] }}</span>
                </div>

                <div class="flex items-center gap-3 text-sm text-slate-200 font-semibold">
                    <div class="w-9 h-9 rounded-xl bg-brand-500/15 text-brand-400 flex items-center justify-center shrink-0">
                        <i class="bi bi-geo-alt-fill text-base"></i>
                    </div>
                    <div>
                        <span class="block text-[11px] uppercase font-bold text-slate-400">Dirección / Punto de Encuentro</span>
                        <span>{{ $oportunidad['direccion_exacta'] }} ({{ $oportunidad['distrito'] }}, Cusco)</span>
                    </div>
                </div>
            </div>

            <div class="glass-card p-7 rounded-3xl border border-slate-800 space-y-4">
                <h2 class="text-xl font-black text-white">Sobre el programa</h2>
                <div class="text-slate-300 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                    {{ $oportunidad['descripcion'] }}
                </div>
            </div>

            <div class="glass-card p-7 rounded-3xl border border-slate-800 space-y-6">
                <h2 class="text-xl font-black text-white">Requisitos e Indicaciones</h2>

                <div class="space-y-3">
                    <h3 class="text-sm font-black text-amberGlow-400 flex items-center gap-2">
                        <i class="bi bi-stars"></i> Lo que harás:
                    </h3>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-300 list-disc list-inside">
                        @foreach($oportunidad['funciones'] as $funcion)
                            <li>{{ $funcion }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="space-y-3 pt-4 border-t border-slate-800">
                    <h3 class="text-sm font-black text-cyanNeon-400 flex items-center gap-2">
                        <i class="bi bi-person-check-fill"></i> Lo que buscamos en ti:
                    </h3>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-300 list-disc list-inside">
                        @foreach($oportunidad['requisitos'] as $req)
                            <li>{{ $req }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="glass-card p-6 rounded-3xl border border-slate-800 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-black text-white"><i class="bi bi-pin-map-fill text-brand-400 me-2"></i> Ubicación en el Mapa de Cusco</h3>
                    <span class="text-xs text-slate-400">{{ $oportunidad['distrito'] }}</span>
                </div>
                <div id="mapa-detalle-cusco" class="w-full h-64 rounded-2xl border border-slate-800 z-10"></div>
            </div>
        </div>

        <!-- COLUMNA DERECHA -->
        <div class="lg:col-span-5 space-y-6">
            <div class="sticky top-28 space-y-5">
                
                <!-- BOTÓN ÚNETE (VA A PANTALLA COMPLETA DE POSTULACIÓN) Y COMPARTIR -->
                <div class="flex gap-3">
                    @auth
                        @if(auth()->user()->tipo_usuario === 'organizacion')
                            <a href="{{ route('home') }}" class="flex-1 py-4 rounded-2xl bg-amberGlow-400 text-slate-950 font-black text-sm text-center shadow-lg">
                                Gestionar en Panel ONG
                            </a>
                        @elseif($cerrada)
                            <button disabled class="flex-1 py-4 rounded-2xl bg-rose-500/20 border border-rose-500/40 text-rose-400 font-black text-sm cursor-not-allowed">
                                Postulaciones Cerradas
                            </button>
                        @else
                            <a href="{{ route('oportunidad.vista-postular', ['id' => $oportunidad['id']]) }}" class="btn-shine flex-1 py-4 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-base text-center shadow-xl shadow-brand-500/25 hover:scale-[1.02] transition-all">
                                Únete / Postular
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-shine flex-1 py-4 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-sm text-center shadow-xl hover:scale-[1.02] transition-all">
                            Inicia Sesión para Unirte
                        </a>
                    @endauth

                    <button onclick="compartirCampana()" class="px-6 py-4 rounded-2xl bg-slate-900 border border-slate-700 text-white font-bold text-sm hover:border-brand-400 hover:text-brand-400 transition-all flex items-center gap-2">
                        <i class="bi bi-share-fill"></i> Compartir
                    </button>
                </div>

                <!-- TEMPORIZADOR REGRESIVO -->
                <div class="glass-card p-6 rounded-3xl border border-slate-800 text-center space-y-4">
                    <p class="text-xs sm:text-sm font-extrabold text-slate-300 uppercase tracking-wider">
                        Cierre de postulaciones en
                    </p>

                    <div id="countdown-box" class="grid grid-cols-4 gap-2 max-w-sm mx-auto">
                        <div class="p-3 rounded-2xl bg-slate-950 border border-slate-800">
                            <span id="cd-dias" class="block text-2xl sm:text-3xl font-black text-white">--</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">días</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-950 border border-slate-800">
                            <span id="cd-horas" class="block text-2xl sm:text-3xl font-black text-white">--</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">horas</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-950 border border-slate-800">
                            <span id="cd-minutos" class="block text-2xl sm:text-3xl font-black text-white">--</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">minutos</span>
                        </div>
                        <div class="p-3 rounded-2xl bg-slate-950 border border-slate-800">
                            <span id="cd-segundos" class="block text-2xl sm:text-3xl font-black text-brand-400">--</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">seg</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Horas a convalidar:</span>
                        <span class="font-black text-brand-400 text-sm">{{ $oportunidad['horas'] }} Horas Oficiales</span>
                    </div>
                </div>

                <!-- APOYO SOLIDARIO OPCIONAL (SOLO BOTÓN DE DONACIÓN VOLUNTARIA) -->
                @if(($oportunidad['acepta_donaciones'] ?? false) && !empty($oportunidad['real_id']))
                <div class="glass-card p-6 rounded-3xl border border-amberGlow-500/40 bg-gradient-to-b from-amberGlow-500/10 to-slate-950/90 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-amberGlow-500/20 text-amberGlow-300 border border-amberGlow-500/30">
                            <i class="bi bi-heart-fill text-rose-400 me-1"></i> Apoyo Solidario Opcional
                        </span>
                        <span class="text-[11px] text-slate-400 font-semibold">Aporte libre</span>
                    </div>

                    <p class="text-xs text-slate-300 leading-relaxed">
                        Si deseas respaldar los materiales y logística social de <strong class="text-white">{{ $oportunidad['ong'] }}</strong> en Cusco, puedes realizar un aporte voluntario.
                    </p>

                    @auth
                        @if(auth()->user()->tipo_usuario !== 'organizacion')
                            <a href="{{ route('campana.vista-donar', ['id' => $oportunidad['real_id']]) }}" class="btn-shine w-full py-3.5 rounded-2xl bg-gradient-to-r from-amberGlow-400 via-amber-500 to-rose-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amberGlow-500/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-wallet2 text-base"></i> Realizar Donación Voluntaria
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full py-3.5 rounded-2xl bg-slate-900 border border-amberGlow-500/40 text-amberGlow-300 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 hover:bg-amberGlow-500/10 transition-colors">
                            <i class="bi bi-box-arrow-in-right"></i> Inicia Sesión para Donar
                        </a>
                    @endauth
                </div>
                @endif

                <div class="glass-card p-5 rounded-3xl border border-slate-800 text-xs text-slate-400 space-y-1.5">
                    <p class="font-bold text-white flex items-center gap-2">
                        <i class="bi bi-shield-check text-brand-400 text-base"></i> Certificado Digital con QR Verificable
                    </p>
                    <p class="leading-relaxed">
                        Al completar las {{ $oportunidad['horas'] }} horas de labor en Cusco, <strong>{{ $oportunidad['ong'] }}</strong> validará tu asistencia y emitirá tu diploma oficial.
                    </p>
                </div>

            </div>
        </div>

    </div>
</main>

@if($recomendadas->isNotEmpty())
<section class="relative z-10 border-t border-slate-800 bg-slate-900/60 backdrop-blur-xl py-16 px-6 mt-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        
        <div class="lg:col-span-5 space-y-6">
            <h2 class="text-3xl sm:text-4xl font-light text-white leading-tight">
                También tenemos estas <strong class="font-black text-brand-400 block mt-1">campañas de voluntariado</strong> para ti.
            </h2>
            <p class="text-xs sm:text-sm text-slate-400">
                Explora otras iniciativas sociales de <strong class="text-slate-200">{{ $oportunidad['ong'] }}</strong> y organizaciones aliadas en Cusco.
            </p>
            <div>
                <a href="{{ route('oportunidades') }}" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-brand-500/20 hover:scale-105 transition-all">
                    Ver más campañas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="lg:col-span-7 relative flex items-center gap-3">
            <button onclick="moverCarrusel(-1)" class="w-11 h-11 rounded-full bg-slate-950 border border-slate-700 text-brand-400 hover:bg-brand-500 hover:text-slate-950 flex items-center justify-center shrink-0 transition-all shadow-lg">
                <i class="bi bi-chevron-left text-lg"></i>
            </button>

            <div id="carrusel-recomendadas" class="flex gap-5 overflow-x-auto scroll-smooth pb-3 snap-x snap-mandatory w-full" style="scrollbar-width: none;">
                @foreach($recomendadas as $rec)
                    <div onclick="window.location.href='{{ route('detalle-oportunidad', ['id' => $rec['id']]) }}'"
                         class="snap-start shrink-0 w-72 sm:w-80 glass-card cursor-pointer rounded-3xl overflow-hidden border border-slate-800 hover:border-brand-500/50 group">
                        <div class="relative h-44 overflow-hidden">
                            <img src="{{ $rec['imagen'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <span class="absolute top-3 left-3 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-slate-950/85 text-brand-400 uppercase border border-brand-500/30">
                                {{ $rec['categoria'] }}
                            </span>
                        </div>
                        <div class="p-5 space-y-2.5">
                            <h3 class="text-base font-extrabold text-white line-clamp-2 group-hover:text-brand-300 transition-colors">
                                {{ $rec['titulo'] }}
                            </h3>
                            <span class="text-xs font-bold text-brand-400 underline decoration-brand-500/50 underline-offset-4 block">
                                {{ $rec['ong'] }}
                            </span>
                            <div class="flex items-start gap-2 text-xs text-slate-300 pt-1">
                                <i class="bi bi-calendar-range text-rose-400 shrink-0 mt-0.5"></i>
                                <span class="line-clamp-2">{{ $rec['rango_fechas'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button onclick="moverCarrusel(1)" class="w-11 h-11 rounded-full bg-slate-950 border border-slate-700 text-brand-400 hover:bg-brand-500 hover:text-slate-950 flex items-center justify-center shrink-0 transition-all shadow-lg">
                <i class="bi bi-chevron-right text-lg"></i>
            </button>
        </div>

    </div>
</section>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = parseFloat("{{ $oportunidad['latitud'] }}");
        const lng = parseFloat("{{ $oportunidad['longitud'] }}");

        const mapaDetalle = L.map('mapa-detalle-cusco').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap Cusco'
        }).addTo(mapaDetalle);

        L.marker([lat, lng]).addTo(mapaDetalle)
            .bindPopup("<b>{{ addslashes($oportunidad['ong']) }}</b><br>{{ addslashes($oportunidad['direccion_exacta']) }}")
            .openPopup();
    });

    const fechaCierreMs = new Date("{{ $oportunidad['fecha_cierre_iso'] }}").getTime();

    function actualizarCountdown() {
        const ahora = new Date().getTime();
        const diferencia = fechaCierreMs - ahora;

        if (diferencia <= 0) {
            document.getElementById('cd-dias').textContent = '00';
            document.getElementById('cd-horas').textContent = '00';
            document.getElementById('cd-minutos').textContent = '00';
            document.getElementById('cd-segundos').textContent = '00';
            return;
        }

        const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
        const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);

        document.getElementById('cd-dias').textContent = String(dias).padStart(2, '0');
        document.getElementById('cd-horas').textContent = String(horas).padStart(2, '0');
        document.getElementById('cd-minutos').textContent = String(minutos).padStart(2, '0');
        document.getElementById('cd-segundos').textContent = String(segundos).padStart(2, '0');
    }

    actualizarCountdown();
    setInterval(actualizarCountdown, 1000);

    function moverCarrusel(direccion) {
        const cont = document.getElementById('carrusel-recomendadas');
        if (cont) {
            cont.scrollBy({ left: direccion * 320, behavior: 'smooth' });
        }
    }

    function compartirCampana() {
        navigator.clipboard.writeText(window.location.href);
        Swal.fire({
            title: '¡Enlace Copiado!',
            text: 'Ya puedes compartir esta campaña de voluntariado por WhatsApp o redes.',
            icon: 'success',
            timer: 1800,
            showConfirmButton: false,
            background: '#0f172a',
            color: '#fff'
        });
    }
</script>
@endsection