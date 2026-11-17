@extends('layouts.app')

@section('content')
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-brand-500/15 rounded-full blur-[160px]"></div>
</div>

<div class="relative z-10 py-12 px-6 max-w-xl mx-auto w-full">
    <div class="glass-card p-8 rounded-3xl border border-slate-800 space-y-6">
        
        <div class="text-center space-y-2">
            <div class="w-16 h-16 rounded-2xl bg-slate-950/90 border border-brand-500/40 p-2 flex items-center justify-center mx-auto mb-2 shadow-lg shadow-brand-500/20">
                <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" alt="VoluntMatch Cusco" class="w-full h-full object-contain">
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-brand-500/20 text-brand-400 border border-brand-500/30">
                <i class="bi bi-person-heart me-1"></i> Comunidad Estudiantil
            </span>
            <h1 class="text-2xl sm:text-3xl font-black text-white">Registro de Voluntario</h1>
            <p class="text-slate-400 text-xs">Convalida tus 100 horas de proyección social en la Región Cusco</p>
        </div>

        <form id="form-registro-voluntario" onsubmit="procesarRegistroVoluntario(event)" class="space-y-4">
            @csrf
            <input type="hidden" name="tipo_usuario" value="estudiante">

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Nombre Completo *</label>
                <input type="text" name="name" required placeholder="Ej. Luis Fernando Quispe" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">DNI (8 dígitos exactos) *</label>
                    <input type="text" name="dni" id="vol_dni" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 8)" required maxlength="8" minlength="8" pattern="[0-9]{8}" inputmode="numeric" placeholder="Ej. 74859612" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white font-mono text-xs focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Carrera Profesional *</label>
                    <select name="carrera" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs focus:border-brand-500 focus:outline-none">
                        <option value="Ingeniería de Sistemas">Ingeniería de Sistemas</option>
                        <option value="Ingeniería Ambiental">Ingeniería Ambiental</option>
                        <option value="Ingeniería Civil">Ingeniería Civil</option>
                        <option value="Derecho">Derecho</option>
                        <option value="Psicología">Psicología</option>
                        <option value="Educación">Educación</option>
                        <option value="Administración">Administración</option>
                        <option value="Contabilidad">Contabilidad</option>
                        <option value="Medicina / Enfermería">Medicina / Enfermería</option>
                        <option value="Arquitectura">Arquitectura</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Teléfono / Celular</label>
                    <input type="text" name="telefono" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 9)" maxlength="9" placeholder="Ej. 984556677" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Distrito en Cusco</label>
                    <select name="distrito" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-300 text-xs focus:border-brand-500 focus:outline-none">
                        <option value="Cusco Centro">Cusco Centro</option>
                        <option value="San Jerónimo">San Jerónimo</option>
                        <option value="Wanchaq">Wanchaq</option>
                        <option value="Santiago">Santiago</option>
                        <option value="San Sebastián">San Sebastián</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Correo Institucional / Personal *</label>
                <input type="email" name="email" required placeholder="estudiante@continental.edu.pe" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Contraseña de Acceso *</label>
                <input type="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-500 focus:outline-none">
            </div>

            <!-- CONSENTIMIENTO LEGAL OBLIGATORIO (LEY N.° 29733) -->
            <div class="p-3.5 rounded-2xl bg-slate-950/90 border border-slate-800">
                <label class="flex items-start gap-2.5 cursor-pointer text-[11px] text-slate-300 leading-relaxed">
                    <input type="checkbox" id="acepto_legal_vol" name="acepto_terminos" required class="mt-0.5 w-4 h-4 rounded border-slate-700 bg-slate-900 text-brand-500 focus:ring-brand-500">
                    <span>
                        He leído y acepto la <a href="{{ route('legal') }}#politica-privacidad" target="_blank" class="text-brand-400 font-bold hover:underline">Política de Privacidad</a> (Ley N.° 29733) y los <a href="{{ route('legal') }}#terminos-uso" target="_blank" class="text-brand-400 font-bold hover:underline">Términos de Uso</a> de VoluntMatch Cusco. *
                    </span>
                </label>
            </div>

            <button type="submit" id="btn-submit-voluntario" class="btn-shine w-full py-3.5 rounded-xl bg-gradient-to-r from-brand-500 to-emerald-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-brand-500/20 hover:scale-[1.01] transition-all mt-2">
                <i class="bi bi-check-circle-fill me-1"></i> Registrarme como Voluntario
            </button>
        </form>

        <div class="text-center pt-2 text-xs text-slate-400">
            ¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="text-brand-400 font-bold hover:underline">Iniciar Sesión</a>
        </div>
    </div>
</div>

<script>
    function procesarRegistroVoluntario(e) {
        e.preventDefault();
        const dni = document.getElementById('vol_dni').value;
        if (dni.length !== 8) {
            Swal.fire({ title: 'DNI Inválido', text: 'El DNI debe contener exactamente 8 dígitos numéricos.', icon: 'warning', background: '#0f172a', color: '#fff' });
            return;
        }

        if (!document.getElementById('acepto_legal_vol').checked) {
            Swal.fire({ title: 'Consentimiento Requerido', text: 'Debes aceptar la Política de Privacidad y los Términos de Uso para continuar.', icon: 'warning', background: '#0f172a', color: '#fff' });
            return;
        }

        const btn = document.getElementById('btn-submit-voluntario');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin me-1"></i> Creando Perfil...';

        const formData = new FormData(document.getElementById('form-registro-voluntario'));

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
            if (data.status === 'success' || data.redirect) {
                if (typeof confetti === 'function') confetti({ particleCount: 140, spread: 80, origin: { y: 0.6 } });
                Swal.fire({
                    title: '¡Bienvenido a VoluntMatch!',
                    text: 'Perfil de voluntario creado con éxito. Redirigiendo a tu Dashboard...',
                    icon: 'success',
                    background: '#0f172a',
                    color: '#fff',
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'Ir a mi Panel'
                }).then(() => {
                    window.location.href = data.redirect || "{{ route('home') }}";
                });
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Registrarme como Voluntario';
            let mensajeError = 'Ocurrió un problema al procesar el registro.';
            if (err.errors) mensajeError = Object.values(err.errors).flat().join('<br>');
            else if (err.message) mensajeError = err.message;

            Swal.fire({
                title: 'Atención',
                html: mensajeError,
                icon: 'warning',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#10b981'
            });
        });
    }
</script>
@endsection