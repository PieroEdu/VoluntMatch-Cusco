@extends('layouts.app')

@section('content')
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute top-20 left-1/3 w-[600px] h-[400px] bg-brand-500/15 rounded-full blur-[160px]"></div>
    <div class="absolute bottom-20 right-1/3 w-[600px] h-[400px] bg-amberGlow-500/15 rounded-full blur-[160px]"></div>
</div>

<div class="relative z-10 max-w-5xl mx-auto px-6 py-14 space-y-10">
    <div class="text-center space-y-3 max-w-2xl mx-auto">
        <div class="w-20 h-20 rounded-3xl bg-slate-950/90 border-2 border-brand-500/40 p-2.5 flex items-center justify-center mx-auto mb-2 shadow-xl shadow-brand-500/20">
            <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" alt="VoluntMatch Cusco" class="w-full h-full object-contain">
        </div>
        <span class="inline-block px-3.5 py-1 rounded-full text-[11px] font-black uppercase tracking-widest bg-brand-500/20 text-brand-400 border border-brand-500/30">
            <i class="bi bi-stars me-1"></i> Únete a VoluntMatch Cusco
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">¿Cómo deseas participar?</h1>
        <p class="text-slate-400 text-xs sm:text-sm">
            Selecciona el tipo de cuenta que corresponde a tu perfil para personalizar tu experiencia y panel de control.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- OPCIÓN 1: ESTUDIANTE VOLUNTARIO -->
        <a href="{{ route('registro-voluntario') }}" class="glass-card p-8 rounded-3xl border border-slate-800 hover:border-brand-500/60 transition-all group flex flex-col justify-between space-y-6 hover:-translate-y-1.5" data-tilt data-tilt-max="4">
            <div class="space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-brand-500/20 text-brand-400 border border-brand-500/30 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-brand-400">Comunidad Universitaria</span>
                    <h2 class="text-2xl font-black text-white mt-1">Soy Estudiante Voluntario</h2>
                </div>
                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                    Explora convocatorias sociales en Cusco según tu carrera, postula a misiones reales, acumula horas validadas y descarga tus certificados digitales oficiales.
                </p>
                <ul class="space-y-2 text-xs text-slate-300 pt-2">
                    <li><i class="bi bi-check-circle-fill text-brand-400 me-2"></i> Postulación directa a ONGs verificadas</li>
                    <li><i class="bi bi-check-circle-fill text-brand-400 me-2"></i> Control de horas de proyección social</li>
                    <li><i class="bi bi-check-circle-fill text-brand-400 me-2"></i> Certificados oficiales con código QR verificable</li>
                </ul>
            </div>
            <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-brand-400 font-black text-xs uppercase tracking-wider">
                <span>Registrarme como Voluntario</span>
                <i class="bi bi-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>

        <!-- OPCIÓN 2: ORGANIZACIÓN / ONG -->
        <a href="{{ route('registro-organizacion') }}" class="glass-card p-8 rounded-3xl border border-slate-800 hover:border-amberGlow-400/60 transition-all group flex flex-col justify-between space-y-6 hover:-translate-y-1.5" data-tilt data-tilt-max="4">
            <div class="space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-amberGlow-500/20 text-amberGlow-400 border border-amberGlow-500/30 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-building-fill-check"></i>
                </div>
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-amberGlow-400">Alianzas Institucionales</span>
                    <h2 class="text-2xl font-black text-white mt-1">Soy Organización / ONG</h2>
                </div>
                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                    Publica tus convocatorias de voluntariado con ubicación exacta en Cusco, gestiona postulantes universitarios, registra sus horas cumplidas y emite certificados.
                </p>
                <ul class="space-y-2 text-xs text-slate-300 pt-2">
                    <li><i class="bi bi-check-circle-fill text-amberGlow-400 me-2"></i> Publicación y edición de convocatorias con mapa GPS</li>
                    <li><i class="bi bi-check-circle-fill text-amberGlow-400 me-2"></i> Aprobación y gestión de estudiantes postulantes</li>
                    <li><i class="bi bi-check-circle-fill text-amberGlow-400 me-2"></i> Validación de horas y emisión de certificados QR</li>
                </ul>
            </div>
            <div class="pt-4 border-t border-slate-800/80 flex items-center justify-between text-amberGlow-400 font-black text-xs uppercase tracking-wider">
                <span>Registrar mi Organización / ONG</span>
                <i class="bi bi-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
            </div>
        </a>
    </div>

    <div class="text-center text-xs text-slate-400 pt-4">
        ¿Ya tienes una cuenta creada? <a href="{{ route('login') }}" class="text-brand-400 font-bold hover:underline">Inicia Sesión aquí</a>
    </div>
</div>
@endsection