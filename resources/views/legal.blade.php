@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] py-14 px-6 max-w-5xl mx-auto space-y-10 z-10">

    <!-- Resplandor ambiental -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[750px] h-[350px] bg-brand-500/10 rounded-full blur-[160px]"></div>
    </div>

    <!-- Cabecera -->
    <div class="glass-card rounded-3xl border border-brand-500/30 p-8 sm:p-10 space-y-4 relative z-10">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-500/15 border border-brand-500/30 text-brand-300 text-xs font-black uppercase tracking-wider">
            <i class="bi bi-shield-lock-fill"></i> Marco Legal y Transparencia • Perú
        </div>
        <h1 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
            Política de Privacidad y Términos de Uso
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm leading-relaxed max-w-3xl">
            Última actualización: <strong class="text-white">Noviembre 2026</strong>. El presente documento regula el tratamiento de datos personales y las condiciones de participación dentro de la plataforma <strong class="text-brand-400">VoluntMatch Cusco</strong>, en cumplimiento de la <strong class="text-white">Ley N.° 29733 (Ley de Protección de Datos Personales del Perú)</strong>.
        </p>

        <!-- Accesos rápidos internos -->
        <div class="flex flex-wrap gap-3 pt-2">
            <a href="#politica-privacidad" class="px-4 py-2 rounded-xl bg-brand-500/20 hover:bg-brand-500 text-brand-300 hover:text-slate-950 border border-brand-500/30 text-xs font-bold transition-all">
                1. Política de Privacidad
            </a>
            <a href="#terminos-uso" class="px-4 py-2 rounded-xl bg-cyanNeon-500/20 hover:bg-cyanNeon-400 text-cyanNeon-300 hover:text-slate-950 border border-cyanNeon-500/30 text-xs font-bold transition-all">
                2. Términos y Condiciones de Uso
            </a>
        </div>
    </div>

    <!-- SECCIÓN 1: POLÍTICA DE PRIVACIDAD -->
    <section id="politica-privacidad" class="glass-card rounded-3xl border border-white/10 p-8 sm:p-10 space-y-6 relative z-10 scroll-mt-24">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
            <div class="w-11 h-11 rounded-2xl bg-brand-500/20 text-brand-400 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-file-earmark-lock2-fill"></i>
            </div>
            <div>
                <span class="font-mono text-[11px] font-bold uppercase text-brand-400">Sección 01 • Ley N.° 29733</span>
                <h2 class="text-2xl font-black text-white">Política de Privacidad y Protección de Datos</h2>
            </div>
        </div>

        <div class="space-y-4 text-xs sm:text-sm text-slate-300 leading-relaxed">
            <div>
                <h3 class="text-white font-bold text-base mb-1">1.1. Datos Personales que Recopilamos</h3>
                <p>
                    Para garantizar la trazabilidad académica y la autenticidad de las convocatorias en la Región Cusco, recopilamos únicamente los datos estrictamente necesarios:
                </p>
                <ul class="list-disc pl-5 space-y-1 mt-2 text-slate-400">
                    <li><strong class="text-slate-200">Estudiantes Voluntarios:</strong> Nombres completos, DNI (8 dígitos), correo electrónico institucional o personal, teléfono de contacto y carrera profesional.</li>
                    <li><strong class="text-slate-200">Organizaciones y ONGs:</strong> Razón social o nombre de la entidad, RUC (11 dígitos), distrito sede en Cusco, teléfono de coordinación y correo electrónico.</li>
                    <li><strong class="text-slate-200">Donantes Solidarios:</strong> Nombre del aportante, método de pago o bien entregado, monto y número de operación referencial para emisión del comprobante.</li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-bold text-base mb-1">1.2. Finalidad del Tratamiento</h3>
                <p>
                    Los datos registrados se utilizan exclusivamente para: (a) gestionar postulaciones a proyectos de proyección social, (b) llevar el control de asistencia en bitácora y validar las 100 horas universitarias, (c) emitir certificados digitales y constancias consolidadas verificables mediante código QR, y (d) generar vouchers transparentes de aportes solidarios.
                </p>
            </div>

            <div>
                <h3 class="text-white font-bold text-base mb-1">1.3. Confidencialidad y Seguridad</h3>
                <p>
                    <strong class="text-white">VoluntMatch Cusco</strong> no comercializa, alquila ni cede bases de datos a terceros. Las contraseñas se almacenan cifradas mediante algoritmos seguros y la información de contacto de los estudiantes solo es visible para la organización a la que deciden postular voluntariamente.
                </p>
            </div>
        </div>
    </section>

    <!-- SECCIÓN 2: TÉRMINOS Y CONDICIONES DE USO -->
    <section id="terminos-uso" class="glass-card rounded-3xl border border-white/10 p-8 sm:p-10 space-y-6 relative z-10 scroll-mt-24">
        <div class="flex items-center gap-3 border-b border-white/10 pb-4">
            <div class="w-11 h-11 rounded-2xl bg-cyanNeon-500/20 text-cyanNeon-400 flex items-center justify-center text-xl shrink-0">
                <i class="bi bi-journal-check"></i>
            </div>
            <div>
                <span class="font-mono text-[11px] font-bold uppercase text-cyanNeon-400">Sección 02 • Normas de Uso</span>
                <h2 class="text-2xl font-black text-white">Términos y Condiciones de Uso</h2>
            </div>
        </div>

        <div class="space-y-4 text-xs sm:text-sm text-slate-300 leading-relaxed">
            <div>
                <h3 class="text-white font-bold text-base mb-1">2.1. Compromiso de los Voluntarios Universitarios</h3>
                <p>
                    Al postular a una campaña, el estudiante declara que la información consignada en su ficha es verídica y asume el compromiso de cumplir puntualmente con las jornadas asignadas por la organización receptora. El incumplimiento injustificado puede conllevar la anulación de la postulación.
                </p>
            </div>

            <div>
                <h3 class="text-white font-bold text-base mb-1">2.2. Responsabilidad de las ONGs y Entidades</h3>
                <p>
                    Las organizaciones registradas garantizan que sus convocatorias tienen fines lícitos, sociales o ambientales en la Región Cusco, y se comprometen a registrar con exactitud en la bitácora las horas efectivamente laboradas por cada voluntario para la emisión de su certificado oficial.
                </p>
            </div>

            <div>
                <h3 class="text-white font-bold text-base mb-1">2.3. Transparencia en Donaciones y Aportes Solidarios</h3>
                <p>
                    Los aportes registrados en la plataforma son de carácter estrictamente voluntario y directo hacia las campañas publicadas. Queda terminantemente prohibido ingresar números de operación falsos, adulterados o duplicados; cualquier irregularidad faculta a la administración a anular el voucher y suspender la cuenta infractora.
                </p>
            </div>

            <div>
                <h3 class="text-white font-bold text-base mb-1">2.4. Validez de Certificados Digitales con QR</h3>
                <p>
                    Todo certificado emitido desde el sistema cuenta con un código único verificable en línea las 24 horas. Cualquier alteración externa del documento impreso invalida su autenticidad frente a la base de datos oficial.
                </p>
            </div>
        </div>

        <div class="pt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-4">
            <span class="text-xs text-slate-400">¿Tienes dudas sobre el tratamiento de tus datos?</span>
            <a href="{{ route('contacto') }}" class="px-5 py-2.5 rounded-xl bg-white/10 hover:bg-brand-500 text-white hover:text-slate-950 font-bold text-xs transition-all">
                Contactar Soporte Institucional →
            </a>
        </div>
    </section>

</div>
@endsection