@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] py-10 px-6 max-w-7xl mx-auto space-y-8 z-10">
    
    <!-- RESPLANDORES DE FONDO -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-amberGlow-500/10 rounded-full blur-[160px]"></div>
    </div>

    <!-- TARJETA DE BIENVENIDA -->
    <div class="p-8 rounded-3xl glass-card border border-amberGlow-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10 gsap-3d-reveal">
        <div class="space-y-2">
            <span class="px-3 py-1 rounded-full text-xs font-black bg-amberGlow-500/20 text-amberGlow-400 border border-amberGlow-500/30 inline-block uppercase tracking-wider">
                <i class="bi bi-building-check me-1"></i> Panel Institucional ONG
            </span>
            <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">{{ Auth::user()->name }}</h1>
            <p class="text-slate-400 text-xs sm:text-sm">
                RUC: <strong class="text-slate-200">{{ Auth::user()->ruc ?? '20601234567' }}</strong> • Sede: <strong class="text-slate-200">{{ Auth::user()->distrito ?? 'Cusco Centro' }}</strong> • Email: <strong class="text-slate-200">{{ Auth::user()->email }}</strong>
            </p>
        </div>
        <button onclick="Swal.fire('Formulario de Convocatoria', 'Próximamente podrás publicar nuevas misiones directamente desde aquí.', 'info')" class="btn-magnetic px-6 py-3.5 rounded-2xl bg-gradient-to-r from-amberGlow-400 via-amber-500 to-brand-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amberGlow-500/20 hover:scale-105 transition-all whitespace-nowrap">
            <i class="bi bi-plus-circle-fill me-1"></i> Nueva Convocatoria
        </button>
    </div>

    <!-- MÉTRICAS DE LA ONG -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10 gsap-3d-reveal">
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-amberGlow-500/20 text-amberGlow-400 flex items-center justify-center mx-auto mb-3 text-xl"><i class="bi bi-megaphone-fill"></i></div>
            <span class="block text-3xl font-black text-amberGlow-400 mb-1">3</span>
            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Convocatorias Publicadas</span>
        </div>
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-cyanNeon-500/20 text-cyanNeon-400 flex items-center justify-center mx-auto mb-3 text-xl"><i class="bi bi-people-fill"></i></div>
            <span class="block text-3xl font-black text-cyanNeon-400 mb-1">12</span>
            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Postulantes Recibidos</span>
        </div>
        <div class="p-6 rounded-3xl glass-card text-center border border-slate-800">
            <div class="w-12 h-12 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center mx-auto mb-3 text-xl"><i class="bi bi-clock-history"></i></div>
            <span class="block text-3xl font-black text-brand-400 mb-1">180 hrs</span>
            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Horas Otorgadas</span>
        </div>
    </div>

    <!-- TABLA DE POSTULANTES REGISTRADOS EN SUS PROYECTOS -->
    <div class="glass-card rounded-3xl border border-slate-800 p-6 space-y-4 relative z-10 gsap-3d-reveal">
        <div class="flex items-center justify-between border-b border-slate-800 pb-4">
            <div>
                <h3 class="text-lg font-black text-white">Solicitudes de Voluntarios en Cusco</h3>
                <p class="text-xs text-slate-400">Estudiantes postulados a las misiones de {{ Auth::user()->name }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-brand-500/20 text-brand-400 border border-brand-500/30">3 Activos</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-300">
                <thead class="text-[11px] font-black uppercase text-slate-400 border-b border-slate-800 bg-slate-900/50">
                    <tr>
                        <th class="p-3">Voluntario</th>
                        <th class="p-3">Proyecto</th>
                        <th class="p-3">Horas</th>
                        <th class="p-3">Estado</th>
                        <th class="p-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    <tr class="hover:bg-slate-900/40 transition-colors">
                        <td class="p-3 font-bold text-white">Carlos Mendoza (Sistemas)</td>
                        <td class="p-3 text-slate-300">Digitalización de Archivos</td>
                        <td class="p-3 text-brand-400 font-bold">30 hrs</td>
                        <td class="p-3"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amberGlow-500/20 text-amberGlow-400 border border-amberGlow-500/30">En revisión</span></td>
                        <td class="p-3 text-right">
                            <button onclick="Swal.fire('Aprobado', 'Solicitud aceptada con éxito', 'success')" class="px-3 py-1 rounded-lg bg-brand-500 text-slate-950 font-bold text-[11px]">Aprobar</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-900/40 transition-colors">
                        <td class="p-3 font-bold text-white">María Quispe (Educación)</td>
                        <td class="p-3 text-slate-300">Refuerzo Escolar Infantil</td>
                        <td class="p-3 text-brand-400 font-bold">15 hrs</td>
                        <td class="p-3"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Completada</span></td>
                        <td class="p-3 text-right">
                            <span class="text-xs text-slate-500 font-semibold"><i class="bi bi-check-all text-brand-400 me-1"></i> Validado</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    gsap.registerPlugin(ScrollTrigger);
    gsap.utils.toArray('.gsap-3d-reveal').forEach((elem) => {
        gsap.fromTo(elem, { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.8 });
    });
</script>
@endsection