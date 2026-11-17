@extends('layouts.app')

@section('content')
@php
    $urlVoucher = route('donacion.voucher', ['codigo' => $donacion->codigo_comprobante]);
    $qrVoucher = 'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' . urlencode($urlVoucher);
@endphp

<div class="relative min-h-[85vh] py-12 px-6 max-w-3xl mx-auto space-y-6 z-10">

    <!-- BOTONES SUPERIORES -->
    <div class="flex flex-wrap items-center justify-between gap-3 print:hidden">
        <a href="{{ route('detalle-oportunidad', ['id' => $donacion->oportunidad_id + 1000]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-xs font-bold text-slate-200 hover:border-brand-400">
            <i class="bi bi-arrow-left"></i> Volver a la Campaña
        </a>

        <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-400 to-brand-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg hover:scale-105 transition-transform">
            <i class="bi bi-printer-fill"></i> Imprimir / Guardar Voucher PDF
        </button>
    </div>

    <!-- TARJETA TIPO VOUCHER OFICIAL SOLIDARIO -->
    <div class="rounded-3xl bg-slate-900 border-2 border-emerald-500/40 shadow-2xl overflow-hidden relative">

        <!-- FRANJA SUPERIOR INSTITUCIONAL -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-slate-950 via-emerald-950/50 to-slate-950 border-b border-dashed border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 inline-block">
                    <i class="bi bi-patch-check-fill me-1"></i> Voucher Solidario Oficial
                </span>
                <h1 class="text-2xl font-black text-white tracking-tight">VoluntMatch Cusco</h1>
                <p class="text-xs text-slate-400">Constancia Electrónica de Aporte Voluntario y Transparencia</p>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-[10px] font-bold uppercase text-slate-400 block">N° de Voucher</span>
                <span class="text-base sm:text-lg font-mono font-black text-emerald-400">{{ $donacion->codigo_comprobante }}</span>
                <span class="text-[11px] text-slate-400 block mt-0.5">
                    {{ \Carbon\Carbon::parse($donacion->created_at)->format('d/m/Y • H:i:s') }} hrs
                </span>
            </div>
        </div>

        <!-- CUERPO DEL VOUCHER -->
        <div class="p-6 sm:p-8 space-y-6">

            <!-- MONTO O DETALLE DESTACADO -->
            <div class="p-6 rounded-2xl bg-slate-950 border border-slate-800 text-center space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">
                    {{ $donacion->metodo_pago === 'Donación en Especie' ? 'Aporte Solidario en Bienes / Insumos' : 'Monto Total Donado' }}
                </span>
                @if($donacion->metodo_pago === 'Donación en Especie')
                    <p class="text-lg sm:text-xl font-black text-cyanNeon-300 pt-1">{{ $donacion->detalle_especie }}</p>
                @else
                    <p class="text-4xl sm:text-5xl font-black text-emerald-400 tracking-tight">S/ {{ number_format($donacion->monto, 2) }}</p>
                @endif
                <span class="inline-block mt-2 px-3 py-1 rounded-full text-[11px] font-bold bg-purple-500/20 text-purple-300">
                    Medio: {{ $donacion->metodo_pago }} • Op. Ref: {{ $donacion->numero_operacion }}
                </span>
            </div>

            <!-- DETALLE DE DATOS DEL DONANTE Y LA ONG -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-1.5">
                    <span class="text-[10px] font-black uppercase text-amberGlow-400 block">Datos del Donante</span>
                    <p class="text-white font-bold text-sm">{{ $donacion->donante_nombre }}</p>
                    @if(!$donacion->es_anonimo && $donacion->donante_dni)
                        <p class="text-slate-400">DNI: <strong class="text-slate-200">{{ $donacion->donante_dni }}</strong></p>
                    @endif
                    <p class="text-slate-400">Correo: <strong class="text-slate-200">{{ $donacion->donante_email ?? 'Verificado' }}</strong></p>
                </div>

                <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 space-y-1.5">
                    <span class="text-[10px] font-black uppercase text-brand-400 block">Organización Receptora</span>
                    <p class="text-white font-bold text-sm">{{ $donacion->ong_nombre }}</p>
                    <p class="text-slate-400">RUC: <strong class="text-slate-200">{{ $donacion->ong_ruc ?? 'Institución Aliada' }}</strong></p>
                    <p class="text-slate-400">Sede: <strong class="text-slate-200">{{ $donacion->campana_distrito }}, Cusco</strong></p>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-950/70 border border-slate-800 text-xs space-y-1">
                <span class="text-[10px] font-black uppercase text-slate-400 block">Campaña Beneficiaria</span>
                <p class="text-sm font-black text-white">{{ $donacion->campana_titulo }}</p>
                @if($donacion->mensaje_apoyo)
                    <p class="text-slate-400 italic pt-1">"{{ $donacion->mensaje_apoyo }}"</p>
                @endif
            </div>

            <!-- MENSAJE DE AGRADECIMIENTO Y CÓDIGO QR DE VERIFICACIÓN -->
            <div class="pt-4 border-t border-dashed border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center sm:text-left">
                    <h2 class="text-base font-black text-amberGlow-400">
                        <i class="bi bi-heart-fill text-rose-400 me-1"></i> ¡Muchas gracias por tu solidaridad con Cusco!
                    </h2>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        En nombre de <strong>{{ $donacion->ong_nombre }}</strong> y las comunidades beneficiarias, agradecemos profundamente tu generosidad. Este comprobante queda registrado en el historial transparente de la campaña.
                    </p>
                </div>

                <div class="text-center shrink-0">
                    <img src="{{ $qrVoucher }}" alt="QR Verificación Voucher" class="w-24 h-24 rounded-xl bg-white p-1 mx-auto shadow">
                    <span class="text-[9px] font-mono text-slate-400 block mt-1">Verificación Digital QR</span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof confetti === 'function') {
            confetti({ particleCount: 120, spread: 75, origin: { y: 0.6 } });
        }
    });
</script>
@endsection