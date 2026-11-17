@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden min-h-screen">

    <!-- ========================================================================= -->
    <!-- SECCIÓN 1: HERO PANORÁMICO CON IMAGEN DE SOPORTE Y CONECTIVIDAD EN CUSCO  -->
    <!-- ========================================================================= -->
    <section class="relative w-full min-h-[430px] flex items-center border-b border-white/10 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1800&q=85"
             alt="Centro de Atención VoluntMatch Cusco"
             class="absolute inset-0 w-full h-full object-cover object-center opacity-30 scale-105">
        
        <div class="absolute inset-0 bg-gradient-to-r from-[#05070d] via-[#05070d]/85 to-[#05070d]/65"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#05070d] via-transparent to-[#05070d]/70"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#38bdf8 1px, transparent 1px); background-size: 28px 28px;"></div>

        <canvas id="canvas-hero-contacto" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

        <div class="relative z-10 max-w-6xl mx-auto px-6 py-16 w-full gsap-3d-reveal">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                <div class="lg:col-span-8 space-y-5">
                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-brand-500/15 border border-brand-500/35 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-brand-400 animate-ping"></span>
                        <span class="font-mono text-[11px] font-bold text-brand-300 uppercase tracking-widest">
                            Soporte Académico & Institucional • Cusco
                        </span>
                    </div>

                    <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-[1.06] drop-shadow-2xl">
                        Centro de Atención <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 via-emerald-300 to-cyanNeon-400">VoluntMatch</span> en tiempo real.
                    </h1>
                </div>

                <div class="lg:col-span-4 p-6 rounded-2xl bg-slate-950/80 backdrop-blur-xl border border-white/10 space-y-3 shadow-2xl">
                    <div class="flex items-center gap-3 pb-2 border-b border-white/10">
                        <div class="w-9 h-9 rounded-xl bg-white p-1.5 flex items-center justify-center shrink-0">
                            <img src="https://i.postimg.cc/vDRFtQn1/Universiadad-Continental-Isotipo-removebg-preview.png"
                                 onerror="this.src='https://i.postimg.cc/43YJt89w/logo-removebg-preview.png'"
                                 alt="Universidad Continental" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <span class="font-mono text-[10px] text-brand-400 uppercase font-bold block">Sede Operativa</span>
                            <strong class="text-xs text-white block">Universidad Continental — Cusco</strong>
                        </div>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Canal directo con la Coordinación de Proyección Social. Las respuestas llegan directamente a tu panel de usuario.
                    </p>
                    <div class="pt-1 flex items-center justify-between font-mono text-[11px] text-slate-400">
                        <span>Atención Digital</span>
                        <span class="text-emerald-400 font-bold">Respuesta en Dashboard</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-brand-500/10 rounded-full blur-[160px]"></div>
        <div class="absolute bottom-10 right-10 w-[500px] h-[400px] bg-cyanNeon-500/10 rounded-full blur-[150px]"></div>
        <canvas id="canvas-contacto-particulas" class="w-full h-full"></canvas>
    </div>

    <!-- ========================================================================= -->
    <!-- SECCIÓN 2: FORMULARIO DE ATENCIÓN + RELOJ OFICIAL + CLIMA + MAPA CUSCO    -->
    <!-- ========================================================================= -->
    <div class="relative z-10 py-14 px-6 max-w-7xl mx-auto w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- FORMULARIO DE CONTACTO -->
            <div class="lg:col-span-7 glass-card p-8 rounded-3xl border border-white/10 space-y-6 gsap-3d-reveal">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <h2 class="text-xl font-black text-white flex items-center gap-2.5">
                        <i class="bi bi-send-fill text-brand-400"></i> Envíanos una Consulta
                    </h2>
                    <span class="font-mono text-[11px] text-emerald-400 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Conectado al Admin
                    </span>
                </div>

                @auth
                    <div class="p-3.5 rounded-2xl bg-brand-500/10 border border-brand-500/30 text-xs text-brand-200 flex items-center gap-2.5">
                        <i class="bi bi-person-check-fill text-brand-400 text-base"></i>
                        <span>Sesión identificada como <strong>{{ Auth::user()->name }}</strong>. La respuesta del Administrador aparecerá en las notificaciones de tu Dashboard.</span>
                    </div>
                @endauth

                <form onsubmit="enviarContacto(event)" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-mono text-[11px] font-bold text-slate-400 uppercase mb-2">Nombre Completo *</label>
                            <input type="text" id="nombre" required value="{{ Auth::user()->name ?? '' }}" placeholder="Ej. Piero Edu Alcca" class="w-full px-4 py-3 rounded-xl bg-slate-950/90 border border-white/10 text-white text-xs focus:border-brand-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-mono text-[11px] font-bold text-slate-400 uppercase mb-2">Correo Institucional o Personal *</label>
                            <input type="email" id="correo" required value="{{ Auth::user()->email ?? '' }}" {{ Auth::check() ? 'readonly' : '' }} placeholder="estudiante@continental.edu.pe" class="w-full px-4 py-3 rounded-xl bg-slate-950/90 border border-white/10 text-white text-xs focus:border-brand-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-mono text-[11px] font-bold text-slate-400 uppercase mb-2">Asunto / Tipo de Consulta *</label>
                        <select id="asunto" class="w-full px-4 py-3 rounded-xl bg-slate-950/90 border border-white/10 text-white text-xs focus:border-brand-500 focus:outline-none">
                            <option value="Acreditación de Horas y Certificados QR">Acreditación de 100 Horas y Certificados QR</option>
                            <option value="Consulta de Organización / ONG">Consulta de Organización / ONG Aliada</option>
                            <option value="Soporte Técnico de Cuenta o Expediente">Soporte Técnico de Cuenta o Expediente</option>
                            <option value="Consulta sobre Donaciones y Vouchers">Consulta sobre Donaciones y Vouchers</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-mono text-[11px] font-bold text-slate-400 uppercase mb-2">Mensaje Detallado *</label>
                        <textarea id="mensaje" rows="4" required placeholder="Describe tu consulta o solicitud para la Coordinación..." class="w-full px-4 py-3 rounded-xl bg-slate-950/90 border border-white/10 text-white text-xs focus:border-brand-500 focus:outline-none"></textarea>
                    </div>

                    <button type="submit" id="btn-enviar-contacto" class="w-full py-4 rounded-2xl bg-gradient-to-r from-brand-500 via-emerald-400 to-cyanNeon-400 text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-xl shadow-brand-500/25 hover:scale-[1.01] transition-all flex items-center justify-center gap-2">
                        <i class="bi bi-send-check-fill text-base"></i> Enviar Consulta al Administrador
                    </button>
                </form>
            </div>

            <!-- COLUMNA DERECHA: RELOJ EN VIVO + API CLIMA + MAPA CUSCO -->
            <div class="lg:col-span-5 space-y-5 gsap-3d-reveal">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="glass-card p-5 rounded-3xl border border-cyanNeon-500/30 space-y-1">
                        <div class="flex items-center justify-between text-cyanNeon-400">
                            <span class="font-mono text-[10px] font-bold uppercase">Reloj Cusco</span>
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <h4 class="font-mono text-xl font-black text-white" id="api-reloj-hora-grande">--:--:--</h4>
                        <span class="text-[11px] text-slate-400 block" id="api-reloj-fecha-texto">Perú (UTC-5)</span>
                    </div>

                    <div class="glass-card p-5 rounded-3xl border border-brand-500/30 space-y-1">
                        <div class="flex items-center justify-between text-brand-400">
                            <span class="font-mono text-[10px] font-bold uppercase">Clima Cusco</span>
                            <i class="bi bi-cloud-sun-fill"></i>
                        </div>
                        <h4 class="font-mono text-xl font-black text-white" id="clima-temp">Cargando...</h4>
                        <span class="text-[11px] text-slate-400 block" id="clima-estado">Sincronizando...</span>
                    </div>
                </div>

                <div class="glass-card p-4 rounded-3xl border border-white/10 space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <span class="text-xs font-extrabold text-white"><i class="bi bi-geo-alt-fill text-brand-400 me-1"></i> Ubicación Sede - Cusco, Perú</span>
                        <span class="font-mono text-[10px] text-slate-400">-13.5167°, -71.9781°</span>
                    </div>
                    <div id="mapa-cusco" class="h-64 rounded-2xl z-10 border border-slate-800"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function actualizarRelojCuscoContacto() {
        const elHora = document.getElementById('api-reloj-hora-grande');
        const elFecha = document.getElementById('api-reloj-fecha-texto');
        const ahora = new Date();

        if (elHora) {
            elHora.textContent = ahora.toLocaleTimeString('es-PE', {
                timeZone: 'America/Lima',
                hour12: false,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
        if (elFecha) {
            const fechaStr = ahora.toLocaleDateString('es-PE', {
                timeZone: 'America/Lima',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            elFecha.textContent = `${fechaStr} • Perú (UTC-5)`;
        }
    }

    function iniciarParticulasContacto(id, isFullScreen, colorDot, colorLine) {
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

        function animate() {
            ctx.clearRect(0, 0, w, h);
            dots.forEach((d, i) => {
                d.x += d.vx;
                d.y += d.vy;
                if (d.x < 0 || d.x > w) d.vx *= -1;
                if (d.y < 0 || d.y > h) d.vy *= -1;

                ctx.beginPath();
                ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
                ctx.fillStyle = colorDot;
                ctx.fill();

                for (let j = i + 1; j < dots.length; j++) {
                    let dist = Math.hypot(d.x - dots[j].x, d.y - dots[j].y);
                    if (dist < 135) {
                        ctx.beginPath();
                        ctx.moveTo(d.x, d.y);
                        ctx.lineTo(dots[j].x, dots[j].y);
                        ctx.strokeStyle = `rgba(${colorLine}, ${0.14 * (1 - dist / 135)})`;
                        ctx.lineWidth = 1;
                        ctx.stroke();
                    }
                }
            });
            requestAnimationFrame(animate);
        }
        animate();
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Inicia el reloj de Cusco inmediatamente y cada segundo
        actualizarRelojCuscoContacto();
        setInterval(actualizarRelojCuscoContacto, 1000);

        if (typeof L !== 'undefined' && document.getElementById('mapa-cusco')) {
            const map = L.map('mapa-cusco').setView([-13.5167, -71.9781], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
            L.marker([-13.5167, -71.9781]).addTo(map).bindPopup('<b>VoluntMatch Cusco</b><br>Centro Histórico • Cusco').openPopup();
        }

        fetch('https://api.open-meteo.com/v1/forecast?latitude=-13.5167&longitude=-71.9781&current_weather=true')
            .then(res => res.json())
            .then(data => {
                if(data.current_weather) {
                    document.getElementById('clima-temp').textContent = data.current_weather.temperature + ' °C';
                    document.getElementById('clima-estado').textContent = 'Viento: ' + data.current_weather.windspeed + ' km/h';
                }
            });

        iniciarParticulasContacto('canvas-hero-contacto', false, 'rgba(56, 189, 248, 0.55)', '52, 211, 153');
        iniciarParticulasContacto('canvas-contacto-particulas', true, 'rgba(52, 211, 153, 0.35)', '56, 189, 248');
    });

    async function enviarContacto(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-enviar-contacto');
        btn.disabled = true;

        try {
            const res = await fetch("{{ route('contacto.enviar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nombre: document.getElementById('nombre').value.trim(),
                    correo: document.getElementById('correo').value.trim(),
                    asunto: document.getElementById('asunto').value,
                    mensaje: document.getElementById('mensaje').value.trim()
                })
            });

            const data = await res.json();
            if (data.ok) {
                if (typeof confetti === 'function') confetti({ particleCount: 120, spread: 80, origin: { y: 0.6 } });
                Swal.fire({
                    title: '¡Consulta Enviada al Administrador!',
                    text: data.message,
                    icon: 'success',
                    background: '#0f172a',
                    color: '#fff',
                    confirmButtonColor: '#10b981'
                });
                document.getElementById('mensaje').value = '';
            } else {
                Swal.fire({ title: 'Atención', text: data.message || 'Verifica los campos.', icon: 'warning', background: '#0f172a', color: '#fff' });
            }
        } catch (err) {
            Swal.fire({ title: 'Error', text: 'No se pudo enviar la consulta en este momento.', icon: 'error', background: '#0f172a', color: '#fff' });
        } finally {
            btn.disabled = false;
        }
    }
</script>
@endsection