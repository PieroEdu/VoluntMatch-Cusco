@extends('layouts.app')

@section('content')
<div class="fixed inset-0 pointer-events-none z-0">
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-brand-500/15 rounded-full blur-[180px]"></div>
</div>

<div class="relative z-10 flex items-center justify-center px-4 py-14">
    <div class="w-full max-w-md glass-card p-8 rounded-3xl border border-slate-700/80 shadow-2xl space-y-6" data-tilt data-tilt-max="4">
        
        <div class="text-center space-y-2">
            <div class="w-20 h-20 rounded-3xl bg-slate-950/90 border-2 border-brand-500/40 p-2.5 flex items-center justify-center mx-auto mb-3 shadow-xl shadow-brand-500/20">
                <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" alt="VoluntMatch Cusco" class="w-full h-full object-contain">
            </div>
            <span class="inline-block px-3 py-0.5 rounded-full bg-brand-500/15 border border-brand-500/30 text-brand-400 font-mono text-[10px] font-bold uppercase tracking-widest">
                VoluntMatch Cusco
            </span>
            <h1 class="text-3xl font-black text-white tracking-tight">Iniciar Sesión</h1>
            <p class="text-xs text-slate-400">Accede a tu panel de Voluntario, ONG o Coordinación en Cusco.</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-2xl bg-rose-500/15 border border-rose-500/40 text-rose-300 text-xs font-semibold text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label for="email" class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Correo Electrónico *</label>
                <div class="relative">
                    <i class="bi bi-envelope absolute left-4 top-3.5 text-brand-400 text-sm"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="ejemplo@continental.edu.pe" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-950/90 border border-slate-800 text-white placeholder-slate-600 text-xs focus:outline-none focus:border-brand-500 transition-all">
                </div>
            </div>

            <div class="space-y-1">
                <label for="password" class="block text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Contraseña *</label>
                <div class="relative">
                    <i class="bi bi-lock absolute left-4 top-3.5 text-brand-400 text-sm"></i>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-950/90 border border-slate-800 text-white placeholder-slate-600 text-xs focus:outline-none focus:border-brand-500 transition-all">
                </div>
            </div>

            <button type="submit" class="btn-shine w-full py-3.5 rounded-2xl bg-gradient-to-r from-brand-500 to-cyanNeon-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-brand-500/30 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                <i class="bi bi-box-arrow-in-right text-base"></i> Ingresar al Sistema
            </button>
        </form>

        <div class="text-center pt-4 border-t border-slate-800/80 space-y-2">
            <p class="text-xs text-slate-400">¿Aún no tienes una cuenta registrada?</p>
            <a href="{{ route('registro') }}" class="inline-block px-5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-brand-400 hover:bg-brand-500 hover:text-slate-950 font-bold text-xs transition-all">
                Crear una cuenta nueva →
            </a>
        </div>

    </div>
</div>
@endsection