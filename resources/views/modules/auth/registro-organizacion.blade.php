@extends('layouts.app')

@section('content')
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-amberGlow-500/15 rounded-full blur-[160px]"></div>
</div>

<div class="relative z-10 py-12 px-6 max-w-xl mx-auto w-full">
    <div class="glass-card p-8 rounded-3xl border border-slate-800 space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-2xl bg-slate-950/90 border border-amberGlow-500/40 p-2 flex items-center justify-center mx-auto mb-2 shadow-lg shadow-amberGlow-500/20">
                <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" alt="VoluntMatch Cusco" class="w-full h-full object-contain">
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amberGlow-500/20 text-amberGlow-400 border border-amberGlow-500/30">
                <i class="bi bi-building-add me-1"></i> Alianza Institucional Cusco
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Registro de Organización / ONG</h1>
            <p class="text-slate-400 text-xs">Publica convocatorias y gestiona voluntarios universitarios</p>
        </div>

        <form id="form-registro-ong" onsubmit="procesarRegistroOng(event)" class="space-y-4">
            @csrf
            <input type="hidden" name="tipo_usuario" value="organizacion">

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nombre de la Organización / ONG *</label>
                <input type="text" name="name" required placeholder="Ej. Asociación EcoAndina Cusco" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
            </div>

            <!-- SELECTOR INTELIGENTE DE RUC SUNAT (20 / 10 + 9 DÍGITOS) -->
            <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-400 uppercase">RUC Institucional (SUNAT - 11 dígitos) *</label>
                    <span id="preview-ruc-ong" class="text-[11px] font-mono font-black text-amberGlow-400">RUC: 20_________</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                    <div class="sm:col-span-5">
                        <select name="ruc_prefijo" id="ruc_prefijo" onchange="actualizarPreviewRuc()" class="w-full px-3 py-3 rounded-xl bg-slate-900 border border-slate-700 text-amberGlow-400 font-bold text-xs focus:border-amberGlow-400 focus:outline-none">
                            <option value="20">20 • Persona Jurídica / ONG</option>
                            <option value="10">10 • Persona Natural / Promotor</option>
                        </select>
                    </div>
                    <div class="sm:col-span-7">
                        <input type="text" name="ruc_resto" id="ruc_resto" oninput="actualizarPreviewRuc()" required maxlength="9" minlength="9" pattern="[0-9]{9}" inputmode="numeric" placeholder="Completa los 9 dígitos restantes" class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs tracking-wider focus:border-amberGlow-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Distrito Sede en Cusco *</label>
                    <select name="distrito" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs focus:border-amberGlow-400 focus:outline-none">
                        <option value="Cusco Centro">Cusco Centro</option>
                        <option value="San Jerónimo">San Jerónimo</option>
                        <option value="Wanchaq">Wanchaq</option>
                        <option value="Santiago">Santiago</option>
                        <option value="San Sebastián">San Sebastián</option>
                        <option value="Poroy">Poroy</option>
                        <option value="Saylla">Saylla</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Teléfono / WhatsApp *</label>
                    <input type="text" name="telefono" required maxlength="9" placeholder="Ej. 984123456" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Correo Institucional *</label>
                <input type="email" name="email" required placeholder="contacto@ongcusco.org" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Misión / Área de Trabajo Social</label>
                <textarea name="mision" rows="2" placeholder="Describe brevemente la labor social de su organización en Cusco..." class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Contraseña de Acceso *</label>
                <input type="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
            </div>

            <!-- CONSENTIMIENTO LEGAL OBLIGATORIO (LEY N.° 29733) -->
            <div class="p-3.5 rounded-2xl bg-slate-950/90 border border-slate-800">
                <label class="flex items-start gap-2.5 cursor-pointer text-[11px] text-slate-300 leading-relaxed">
                    <input type="checkbox" id="acepto_legal_ong" name="acepto_terminos" required class="mt-0.5 w-4 h-4 rounded border-slate-700 bg-slate-900 text-amberGlow-400 focus:ring-amberGlow-400">
                    <span>
                        Declaramos que la información institucional es verídica y aceptamos la <a href="{{ route('legal') }}#politica-privacidad" target="_blank" class="text-amberGlow-400 font-bold hover:underline">Política de Privacidad</a> (Ley N.° 29733) y los <a href="{{ route('legal') }}#terminos-uso" target="_blank" class="text-amberGlow-400 font-bold hover:underline">Términos de Uso</a> de VoluntMatch Cusco. *
                    </span>
                </label>
            </div>

            <button type="submit" id="btn-submit-ong" class="btn-shine w-full py-3.5 rounded-xl bg-gradient-to-r from-amberGlow-400 to-brand-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amberGlow-500/20 hover:scale-[1.01] transition-all mt-2">
                <i class="bi bi-check-circle-fill me-1"></i> Registrar Organización Aliada
            </button>
        </form>

        <div class="text-center pt-2 text-xs text-slate-400">
            ¿Ya tienen cuenta registrada? <a href="{{ route('login') }}" class="text-amberGlow-400 font-bold hover:underline">Iniciar Sesión</a>
        </div>
    </div>
</div>

<script>
    function actualizarPreviewRuc() {
        const prefijo = document.getElementById('ruc_prefijo').value;
        const inputResto = document.getElementById('ruc_resto');
        inputResto.value = inputResto.value.replace(/[^0-9]/g, '').slice(0, 9);
        const faltan = '_'.repeat(9 - inputResto.value.length);
        const completo = prefijo + inputResto.value + faltan;
        const badge = document.getElementById('preview-ruc-ong');
        badge.textContent = `RUC: ${completo}`;
        badge.className = inputResto.value.length === 9
            ? 'text-[11px] font-mono font-black text-emerald-400'
            : 'text-[11px] font-mono font-black text-amberGlow-400';
    }

    function procesarRegistroOng(e) {
        e.preventDefault();
        const resto = document.getElementById('ruc_resto').value;
        if (resto.length !== 9) {
            Swal.fire({ title: 'RUC Incompleto', text: 'Debes ingresar exactamente los 9 dígitos restantes del RUC.', icon: 'warning', background: '#0f172a', color: '#fff' });
            return;
        }

        if (!document.getElementById('acepto_legal_ong').checked) {
            Swal.fire({ title: 'Consentimiento Requerido', text: 'Debes aceptar la Política de Privacidad y los Términos de Uso para registrar la organización.', icon: 'warning', background: '#0f172a', color: '#fff' });
            return;
        }

        const btn = document.getElementById('btn-submit-ong');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin me-1"></i> Registrando ONG...';

        const formData = new FormData(document.getElementById('form-registro-ong'));

        fetch('{{ route("registrar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => {
            if (!res.ok) return res.json().then(err => { throw err; });
            return res.json();
        })
        .then(data => {
            if (typeof confetti === 'function') confetti({ particleCount: 140, spread: 80, origin: { y: 0.6 } });
            Swal.fire({
                title: '¡ONG Registrada con Éxito!',
                text: 'Su RUC ha sido registrado y pasará a verificación institucional. Redirigiendo al Panel...',
                icon: 'success',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Entrar al Panel ONG'
            }).then(() => {
                window.location.href = data.redirect || "{{ route('home') }}";
            });
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Registrar Organización Aliada';
            let msg = 'Revisa los datos ingresados.';
            if (err.errors) msg = Object.values(err.errors).flat().join('<br>');
            else if (err.message) msg = err.message;

            Swal.fire({
                title: 'Atención',
                html: msg,
                icon: 'warning',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#f59e0b'
            });
        });
    }
</script>
@endsection