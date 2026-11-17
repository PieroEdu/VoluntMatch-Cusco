@extends('layouts.app')

@section('content')
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-950/70 to-slate-950/90"></div>
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-brand-500/15 rounded-full blur-[180px] animate-pulse-glow"></div>
</div>

<div class="relative z-10">
    <main class="max-w-7xl mx-auto px-6 py-12 w-full space-y-8 flex-grow">
        <div class="space-y-3 gsap-3d-reveal">
            <span class="px-3.5 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-500/20 text-brand-400 border border-brand-500/30 inline-flex items-center gap-1.5 shadow-md shadow-brand-500/10">
                <i class="bi bi-compass-fill animate-spin" style="animation-duration: 8s;"></i> Explorador de Misiones
            </span>
            <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">Oportunidades de Voluntariado en Cusco</h1>
            <p class="text-slate-400 text-xs sm:text-sm max-w-2xl">
                Haz clic en cualquier campaña para ver su banner completo, temporizador de cierre y ubicación, o postula directamente.
            </p>
        </div>

        <!-- BUSCADOR Y FILTROS ANIMADOS -->
        <div class="glass-card p-4 rounded-3xl border border-slate-800 flex flex-col md:flex-row gap-4 justify-between items-center gsap-3d-reveal">
            <div class="relative w-full md:w-96 group">
                <i class="bi bi-search absolute left-4 top-3.5 text-slate-500 group-focus-within:text-brand-400 transition-colors text-xs"></i>
                <input type="text" id="input-busqueda" onkeyup="filtrarTodo()" placeholder="Buscar por título, ONG o distrito..." class="w-full pl-10 pr-4 py-2.5 rounded-2xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-brand-500 focus:shadow-[0_0_20px_rgba(16,185,129,0.2)] transition-all">
            </div>
            
            <div class="flex gap-2 flex-wrap text-xs">
                <button onclick="filtrarCategoria('TODAS', this)" class="btn-filtro interactive-element px-3.5 py-2 rounded-xl bg-brand-500/20 border border-brand-500/40 text-brand-400 font-bold hover:scale-105 transition-all">Todas</button>
                <button onclick="filtrarCategoria('Medio Ambiente', this)" class="btn-filtro interactive-element px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:border-brand-500/40 hover:text-white hover:scale-105 transition-all">Medio Ambiente</button>
                <button onclick="filtrarCategoria('Tecnología', this)" class="btn-filtro interactive-element px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:border-brand-500/40 hover:text-white hover:scale-105 transition-all">Tecnología</button>
                <button onclick="filtrarCategoria('Educación', this)" class="btn-filtro interactive-element px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:border-brand-500/40 hover:text-white hover:scale-105 transition-all">Educación</button>
                <button onclick="filtrarCategoria('Salud Mental', this)" class="btn-filtro interactive-element px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:border-brand-500/40 hover:text-white hover:scale-105 transition-all">Salud Mental</button>
                <button onclick="filtrarCategoria('Gestión / Marketing', this)" class="btn-filtro interactive-element px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:border-brand-500/40 hover:text-white hover:scale-105 transition-all">Gestión / Marketing</button>
                <button onclick="filtrarCategoria('Salud', this)" class="btn-filtro interactive-element px-3.5 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:border-brand-500/40 hover:text-white hover:scale-105 transition-all">Salud</button>
            </div>
        </div>

        <!-- CATÁLOGO COMPLETO DE OPORTUNIDADES -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="grid-oportunidades">
            @foreach($oportunidades as $op)
                @php
                    $cerrada = ($op['estado'] === 'Cerrada' || $op['cupos_disponibles'] <= 0);
                    $urlDetalle = route('detalle-oportunidad', ['id' => $op['id']]);
                    $urlPostular = auth()->check() ? route('oportunidad.vista-postular', ['id' => $op['id']]) : route('login');
                @endphp
                <div onclick="window.location.href='{{ $urlDetalle }}'"
                     class="card-op card-clickable group glass-card rounded-3xl overflow-hidden flex flex-col justify-between border {{ $cerrada ? 'border-rose-500/30 opacity-85' : 'border-slate-800' }} gsap-3d-reveal"
                     data-categoria="{{ $op['categoria'] }}"
                     data-titulo="{{ strtolower($op['titulo']) }}"
                     data-ong="{{ strtolower($op['ong']) }}"
                     data-distrito="{{ strtolower($op['distrito']) }}"
                     data-tilt data-tilt-max="5" data-tilt-speed="400">
                    
                    <div class="relative z-10">
                        <div class="relative overflow-hidden h-56 border-b border-slate-800/80">
                            <img src="{{ $op['imagen'] }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110 {{ $cerrada ? 'grayscale' : '' }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-transparent to-transparent"></div>
                            
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-[10px] font-black bg-slate-950/85 text-brand-400 uppercase border border-brand-500/30 backdrop-blur-md">
                                {{ $op['categoria'] }}
                            </span>
                            
                            @if($cerrada)
                                <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-[10px] font-black bg-rose-600 text-white uppercase shadow-lg">
                                    Cupos Agotados
                                </span>
                            @else
                                <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-[10px] font-black bg-slate-950/85 text-cyanNeon-400 border border-cyanNeon-500/30 backdrop-blur-md">
                                    {{ $op['cupos_disponibles'] }} de {{ $op['cupos_totales'] }} cupos
                                </span>
                            @endif

                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between gap-2">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-brand-500/20 text-brand-300 border border-brand-500/30 backdrop-blur-md">
                                    <i class="bi bi-award-fill me-1"></i> {{ $op['horas'] }} Horas
                                </span>
                                @if($op['acepta_donaciones'] ?? false)
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black bg-amberGlow-500/25 text-amberGlow-300 border border-amberGlow-500/40 backdrop-blur-md">
                                        <i class="bi bi-heart-fill text-rose-400 me-1"></i> Apoyo Solidario
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 space-y-3">
                            <div>
                                <h3 class="text-lg font-extrabold text-white leading-snug group-hover:text-brand-300 transition-colors">{{ $op['titulo'] }}</h3>
                                <span class="text-xs font-bold text-brand-400 underline decoration-brand-500/50 underline-offset-4 mt-1 inline-block">
                                    {{ $op['ong'] }}
                                </span>
                            </div>

                            <div class="space-y-2 pt-2 text-xs text-slate-300">
                                <div class="flex items-start gap-2.5">
                                    <i class="bi bi-calendar-range text-rose-400 text-sm shrink-0 mt-0.5"></i>
                                    <span>{{ $op['rango_fechas'] }}</span>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <i class="bi bi-clock text-amberGlow-400 text-sm shrink-0 mt-0.5"></i>
                                    <span>{{ $op['horario'] }}</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <i class="bi bi-geo-alt-fill text-brand-400 text-sm shrink-0"></i>
                                    <span>{{ $op['distrito'] }}, Cusco</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative z-10 px-6 py-4 bg-slate-950/60 border-t border-slate-800/80 flex items-center justify-between gap-2">
                        <span class="text-[11px] font-bold text-slate-400 group-hover:text-brand-300 transition-colors flex items-center gap-1">
                            Ver detalles <i class="bi bi-arrow-right"></i>
                        </span>
                        @if(!auth()->check() || auth()->user()->tipo_usuario !== 'organizacion')
                            @if($cerrada)
                                <span class="px-3.5 py-2 rounded-xl bg-rose-500/15 border border-rose-500/30 text-rose-400 font-bold text-xs">
                                    Cerrada
                                </span>
                            @else
                                <a href="{{ $urlPostular }}" onclick="event.stopPropagation();" class="btn-magnetic px-4 py-2 rounded-xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs hover:scale-105 transition-transform flex items-center gap-1.5 shadow-lg shadow-brand-500/25">
                                    <i class="bi bi-send-fill"></i> Únete / Postular
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </main>
</div>

<script>
    let categoriaActual = 'TODAS';

    function filtrarCategoria(cat, btn) {
        categoriaActual = cat;
        document.querySelectorAll('.btn-filtro').forEach(b => {
            b.classList.remove('bg-brand-500/20', 'border-brand-500/40', 'text-brand-400', 'font-bold');
            b.classList.add('bg-slate-900', 'border-slate-800', 'text-slate-300');
        });
        btn.classList.remove('bg-slate-900', 'border-slate-800', 'text-slate-300');
        btn.classList.add('bg-brand-500/20', 'border-brand-500/40', 'text-brand-400', 'font-bold');
        filtrarTodo();
    }

    function filtrarTodo() {
        const query = document.getElementById('input-busqueda').value.toLowerCase();
        const cards = document.querySelectorAll('.card-op');
        const visibles = [];

        cards.forEach(card => {
            const catCard = card.getAttribute('data-categoria');
            const titulo = card.getAttribute('data-titulo');
            const ong = card.getAttribute('data-ong');
            const distrito = card.getAttribute('data-distrito');

            const coincideCategoria = (categoriaActual === 'TODAS' || catCard === categoriaActual);
            const coincideBusqueda = (titulo.includes(query) || ong.includes(query) || distrito.includes(query));

            if (coincideCategoria && coincideBusqueda) {
                card.style.display = 'flex';
                visibles.push(card);
            } else {
                card.style.display = 'none';
            }
        });

        if (typeof gsap !== 'undefined' && visibles.length > 0) {
            gsap.fromTo(visibles,
                { opacity: 0, y: 20, scale: 0.96 },
                { opacity: 1, y: 0, scale: 1, duration: 0.45, stagger: 0.06, ease: 'power2.out', clearProps: 'transform' }
            );
        }
    }
</script>
@endsection