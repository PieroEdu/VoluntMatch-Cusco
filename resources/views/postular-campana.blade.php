@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] py-12 px-6 max-w-6xl mx-auto space-y-8 z-10">

    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-brand-500/15 rounded-full blur-[160px]"></div>
    </div>

    <!-- NAVEGACIÓN SUPERIOR -->
    <div class="border-b border-slate-800 pb-6 relative z-10">
        <a href="{{ route('detalle-oportunidad', ['id' => $oportunidad['id']]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-brand-400 transition-colors mb-2">
            <i class="bi bi-arrow-left"></i> Volver al Detalle de la Campaña
        </a>
        <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight">Ficha Oficial de Postulación</h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-1">
            Completa tus datos académicos y motivación para postular directamente a la organización.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">

        <!-- COLUMNA IZQUIERDA (5 COLS): RESUMEN DE LA CAMPAÑA -->
        <div class="lg:col-span-5 space-y-5">
            <div class="glass-card rounded-3xl overflow-hidden border border-slate-800">
                <img src="{{ $oportunidad['imagen'] }}" class="w-full h-48 object-cover">
                <div class="p-6 space-y-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-brand-500/20 text-brand-400 border border-brand-500/30">
                        {{ $oportunidad['categoria'] }}
                    </span>
                    <h2 class="text-xl font-black text-white leading-snug">{{ $oportunidad['titulo'] }}</h2>
                    <p class="text-xs font-bold text-brand-400"><i class="bi bi-building me-1"></i> {{ $oportunidad['ong'] }}</p>

                    <div class="space-y-2 pt-3 border-t border-slate-800 text-xs text-slate-300">
                        <div><i class="bi bi-calendar-range text-rose-400 me-2"></i> {{ $oportunidad['rango_fechas'] }}</div>
                        <div><i class="bi bi-clock text-amberGlow-400 me-2"></i> {{ $oportunidad['horario'] }}</div>
                        <div><i class="bi bi-geo-alt-fill text-brand-400 me-2"></i> {{ $oportunidad['distrito'] }}, Cusco</div>
                        <div><i class="bi bi-award-fill text-cyanNeon-400 me-2"></i> <strong>{{ $oportunidad['horas'] }} Horas Oficiales</strong></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA (7 COLS): FORMULARIO DEDICADO -->
        <div class="lg:col-span-7">
            <form id="form-postulacion-dedicada" onsubmit="enviarPostulacionDedicada(event)" class="glass-card p-6 sm:p-8 rounded-3xl border border-brand-500/30 space-y-5">
                @csrf
                <div class="p-4 rounded-2xl bg-slate-950/90 border border-slate-800 flex items-center justify-between text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase">Postulante</span>
                        <strong class="text-white text-sm">{{ $user->name }}</strong>
                        <span class="block text-slate-400">{{ $user->email }} • DNI: {{ $user->dni ?? 'Registrado' }}</span>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-400 font-bold text-[10px]">Cuenta Verificada</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Universidad / Institución *</label>
                        <select id="post-universidad" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-400 focus:outline-none">
                            <option value="Universidad Continental - Cusco">Universidad Continental - Cusco</option>
                            <option value="UNSAAC - Cusco">UNSAAC - Cusco</option>
                            <option value="Universidad Andina del Cusco (UAC)">Universidad Andina del Cusco (UAC)</option>
                            <option value="Otra Institución Superior">Otra Institución Superior</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Ciclo Académico *</label>
                        <select id="post-ciclo" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-400 focus:outline-none">
                            <option value="1° - 3° Ciclo">1° - 3° Ciclo</option>
                            <option value="4° - 6° Ciclo" selected>4° - 6° Ciclo</option>
                            <option value="7° - 9° Ciclo">7° - 9° Ciclo</option>
                            <option value="10° Ciclo / Egresado">10° Ciclo / Egresado</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Celular / WhatsApp de Contacto *</label>
                        <input type="text" id="post-telefono" required maxlength="9" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,9)" placeholder="Ej. 984123456" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white font-mono text-xs focus:border-brand-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Disponibilidad Horaria *</label>
                        <select id="post-disponibilidad" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-400 focus:outline-none">
                            <option value="Sábados y Domingos">Sábados y Domingos</option>
                            <option value="Tardes (Lunes a Viernes)">Tardes (Lunes a Viernes)</option>
                            <option value="Mañanas (Lunes a Viernes)">Mañanas (Lunes a Viernes)</option>
                            <option value="Horario Flexible">Horario Flexible</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Experiencia Previa y Carta de Motivación *</label>
                    <textarea id="post-motivacion" rows="4" required minlength="10" placeholder="Cuéntale a {{ $oportunidad['ong'] }} por qué deseas sumarte a esta misión y qué habilidades aportarás..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-400 focus:outline-none"></textarea>
                </div>

                <!-- CONSENTIMIENTO LEGAL OBLIGATORIO -->
                <div class="p-3.5 rounded-2xl bg-slate-950/90 border border-slate-800">
                    <label class="flex items-start gap-2.5 cursor-pointer text-[11px] text-slate-300 leading-relaxed">
                        <input type="checkbox" id="acepto_legal_post" required class="mt-0.5 w-4 h-4 rounded border-slate-700 bg-slate-900 text-brand-500 focus:ring-brand-500">
                        <span>
                            Autorizo el envío de mi ficha académica a la organización y acepto la <a href="{{ route('legal') }}#politica-privacidad" target="_blank" class="text-brand-400 font-bold hover:underline">Política de Privacidad</a> y los <a href="{{ route('legal') }}#terminos-uso" target="_blank" class="text-brand-400 font-bold hover:underline">Términos de Uso</a> (compromiso de asistencia). *
                        </span>
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a href="{{ route('detalle-oportunidad', ['id' => $oportunidad['id']]) }}" class="px-6 py-3.5 rounded-2xl bg-slate-900 border border-slate-700 text-slate-300 font-bold text-xs text-center">
                        Cancelar
                    </a>
                    <button type="submit" id="btn-enviar-postulacion" class="flex-1 py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-xl shadow-brand-500/20 hover:scale-[1.01] transition-all">
                        <i class="bi bi-send-fill me-1"></i> Confirmar y Enviar Postulación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function enviarPostulacionDedicada(e) {
        e.preventDefault();

        if (!document.getElementById('acepto_legal_post').checked) {
            Swal.fire({ title: 'Consentimiento Requerido', text: 'Debes aceptar la Política de Privacidad y los Términos de Uso antes de postular.', icon: 'warning', background: '#0f172a', color: '#fff' });
            return;
        }

        const btn = document.getElementById('btn-enviar-postulacion');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin me-1"></i> Enviando ficha...';

        fetch('/postular/{{ $oportunidad["id"] }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                titulo: "{{ addslashes($oportunidad['titulo']) }}",
                organizacion: "{{ addslashes($oportunidad['ong']) }}",
                horas: {{ $oportunidad['horas'] }},
                universidad: document.getElementById('post-universidad').value,
                ciclo: document.getElementById('post-ciclo').value,
                telefono_contacto: document.getElementById('post-telefono').value.trim(),
                disponibilidad: document.getElementById('post-disponibilidad').value,
                motivacion: document.getElementById('post-motivacion').value.trim()
            })
        })
        .then(res => res.json().then(data => {
            if (!res.ok) throw new Error(data.message || 'Error al registrar postulación');
            return data;
        }))
        .then(data => {
            if (typeof confetti === 'function') confetti({ particleCount: 150, spread: 80, origin: { y: 0.6 } });
            Swal.fire({
                title: '¡Postulación Enviada!',
                text: data.message,
                icon: 'success',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Ir a mi Panel de Voluntario'
            }).then(() => {
                window.location.href = "{{ route('home') }}";
            });
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Confirmar y Enviar Postulación';
            Swal.fire({ title: 'Atención', text: err.message, icon: 'warning', background: '#0f172a', color: '#fff' });
        });
    }
</script>
@endsection