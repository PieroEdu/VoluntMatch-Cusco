@extends('layouts.app')

@section('content')
@php
    $yapeNum = $campana->yape_numero ?: '984 000 111';
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode('YAPE/PLIN SOLIDARIO - ' . $campana->ong_nombre . ' - ' . $yapeNum);
@endphp

<div class="relative min-h-[85vh] py-12 px-6 max-w-6xl mx-auto space-y-8 z-10">

    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-amberGlow-500/15 rounded-full blur-[160px]"></div>
    </div>

    <!-- CABECERA -->
    <div class="border-b border-slate-800 pb-6 relative z-10">
        <a href="{{ route('detalle-oportunidad', ['id' => $campana->id + 1000]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-amberGlow-400 transition-colors mb-2">
            <i class="bi bi-arrow-left"></i> Volver a la Campaña
        </a>
        <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight">Apoyo Solidario Voluntario</h1>
        <p class="text-xs sm:text-sm text-slate-400 mt-1">
            Tu aporte es 100% libre y voluntario. Al validar el número de operación se emitirá tu <strong class="text-emerald-400">Voucher Solidario Oficial</strong>.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">

        <!-- COLUMNA IZQUIERDA (5 COLS): DATOS DE LA CAMPAÑA Y QR OFICIAL DE LA ONG -->
        <div class="lg:col-span-5 space-y-5">
            <div class="glass-card rounded-3xl overflow-hidden border border-slate-800">
                <img src="{{ $campana->imagen }}" class="w-full h-44 object-cover">
                <div class="p-6 space-y-3">
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-amberGlow-500/20 text-amberGlow-300 border border-amberGlow-500/30">
                        Campaña Beneficiaria
                    </span>
                    <h2 class="text-lg font-black text-white leading-snug">{{ $campana->titulo }}</h2>
                    <p class="text-xs font-bold text-brand-400"><i class="bi bi-building-check me-1"></i> {{ $campana->ong_nombre }}</p>
                    @if($ong && $ong->ruc)
                        <p class="text-[11px] text-slate-400">RUC Institucional: <strong class="text-slate-200">{{ $ong->ruc }}</strong></p>
                    @endif

                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Total recaudado a la fecha:</span>
                        <span class="text-base font-black text-emerald-400">S/ {{ number_format($campana->monto_recaudado ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- TARJETA QR YAPE / PLIN DE LA ONG -->
            <div class="glass-card p-6 rounded-3xl border border-purple-500/35 bg-purple-950/10 flex items-center gap-4">
                <img src="{{ $qrUrl }}" alt="QR Yape Plin ONG" class="w-28 h-28 rounded-2xl bg-white p-1.5 shrink-0 shadow-lg">
                <div class="space-y-1.5 text-xs">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-purple-500/20 text-purple-300">QR Oficial Yape / Plin</span>
                    <p class="text-slate-200 font-bold">{{ $campana->ong_nombre }}</p>
                    <p class="text-cyanNeon-400 font-mono text-sm font-black">{{ $yapeNum }}</p>
                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        1. Realiza tu transferencia por el monto libre que desees.<br>
                        2. Copia el <strong>N° de Operación</strong> e ingrésalo al lado para generar tu voucher.
                    </p>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA (7 COLS): FORMULARIO DE DONACIÓN Y VALIDACIÓN -->
        <div class="lg:col-span-7">
            <form id="form-donacion-dedicada" onsubmit="procesarDonacionDedicada(event)" class="glass-card p-6 sm:p-8 rounded-3xl border border-amberGlow-500/30 space-y-5">
                @csrf

                <!-- SELECTOR DE MODALIDAD -->
                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-2">1. Selecciona el Medio de Aporte *</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-black">
                        <button type="button" id="tab-pay-Yape" onclick="seleccionarMetodo('Yape')" class="tab-pay py-3 rounded-xl border bg-amberGlow-400 text-slate-950 border-amberGlow-400">Yape</button>
                        <button type="button" id="tab-pay-Plin" onclick="seleccionarMetodo('Plin')" class="tab-pay py-3 rounded-xl border bg-slate-950 text-slate-300 border-slate-800">Plin</button>
                        <button type="button" id="tab-pay-Tarjeta" onclick="seleccionarMetodo('Tarjeta')" class="tab-pay py-3 rounded-xl border bg-slate-950 text-slate-300 border-slate-800">Tarjeta</button>
                        <button type="button" id="tab-pay-Especie" onclick="seleccionarMetodo('Especie')" class="tab-pay py-3 rounded-xl border bg-slate-950 text-slate-300 border-slate-800">En Especie</button>
                    </div>
                </div>

                <!-- MONTO LIBRE EN SOLES -->
                <div id="bloque-monetario" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-2">2. Monto Libre a Donar en Soles (S/) *</label>
                        <div class="grid grid-cols-5 gap-2 mb-2.5">
                            <button type="button" onclick="document.getElementById('don-monto').value=5" class="py-2 rounded-xl bg-slate-950 border border-slate-800 hover:border-emerald-400 text-xs font-bold text-white">S/ 5</button>
                            <button type="button" onclick="document.getElementById('don-monto').value=10" class="py-2 rounded-xl bg-slate-950 border border-slate-800 hover:border-emerald-400 text-xs font-bold text-white">S/ 10</button>
                            <button type="button" onclick="document.getElementById('don-monto').value=20" class="py-2 rounded-xl bg-slate-950 border border-slate-800 hover:border-emerald-400 text-xs font-bold text-white">S/ 20</button>
                            <button type="button" onclick="document.getElementById('don-monto').value=50" class="py-2 rounded-xl bg-slate-950 border border-slate-800 hover:border-emerald-400 text-xs font-bold text-white">S/ 50</button>
                            <button type="button" onclick="document.getElementById('don-monto').value=100" class="py-2 rounded-xl bg-slate-950 border border-slate-800 hover:border-emerald-400 text-xs font-bold text-white">S/ 100</button>
                        </div>
                        <input type="number" id="don-monto" min="1" step="0.50" placeholder="Escribe cualquier monto libre (Ej. 15.00)" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-emerald-500/40 text-emerald-400 font-black text-base focus:outline-none">
                    </div>

                    <!-- CAMPOS DE TARJETA (SOLO SI ELIGE TARJETA) -->
                    <div id="panel-tarjeta" class="hidden p-4 rounded-2xl bg-slate-950 border border-cyanNeon-500/30 space-y-3">
                        <span class="text-[10px] font-black uppercase text-cyanNeon-400 block">Datos de Tarjeta Visa / Mastercard</span>
                        <input type="text" maxlength="19" placeholder="Número de Tarjeta (4557 •••• •••• 8821)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" maxlength="5" placeholder="MM/AA" class="px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs">
                            <input type="password" maxlength="3" placeholder="CVV" class="px-3.5 py-2.5 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase text-amberGlow-400 mb-1.5">3. N° de Operación / Constancia de Transferencia *</label>
                        <input type="text" id="don-operacion" maxlength="20" placeholder="Ej. 08492154 (Código de operación de tu constancia Yape/Plin/Banco)" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-amberGlow-500/40 text-white font-mono text-xs focus:outline-none">
                        <span class="text-[10px] text-slate-500 mt-1 block">Este código quedará registrado en el voucher para auditoría de la ONG.</span>
                    </div>
                </div>

                <!-- BLOQUE DONACIÓN EN ESPECIE -->
                <div id="bloque-especie" class="hidden space-y-2">
                    <label class="block text-[11px] font-bold uppercase text-emerald-400 mb-1">Describe los Bienes / Insumos a Entregar *</label>
                    <textarea id="don-especie" rows="3" placeholder="Ej. 15 cuadernos cuadriculados, 3 cajas de lápices y 4 frazadas nuevas..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-emerald-500/40 text-white text-xs focus:outline-none"></textarea>
                </div>

                <!-- DATOS DEL DONANTE PARA EL VOUCHER -->
                <div class="space-y-3 pt-2 border-t border-slate-800">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nombre en el Voucher</label>
                            <input type="text" id="don-nombre" value="{{ $user->name }}" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                        </div>
                        <div class="flex items-end">
                            <label class="w-full flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs text-slate-300 cursor-pointer">
                                <input type="checkbox" id="don-anonimo" class="accent-amber-400"> Mostrar como Donante Anónimo
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Mensaje de Aliento para la Campaña (Opcional)</label>
                        <input type="text" id="don-mensaje" placeholder="Ej. ¡Mucho éxito en esta hermosa labor por Cusco!" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                    </div>
                </div>

                <!-- CONSENTIMIENTO LEGAL OBLIGATORIO -->
                <div class="p-3.5 rounded-2xl bg-slate-950/90 border border-slate-800">
                    <label class="flex items-start gap-2.5 cursor-pointer text-[11px] text-slate-300 leading-relaxed">
                        <input type="checkbox" id="acepto_legal_don" required class="mt-0.5 w-4 h-4 rounded border-slate-700 bg-slate-900 text-amberGlow-400 focus:ring-amberGlow-400">
                        <span>
                            Declaro que el aporte y el número de operación son reales, y acepto la <a href="{{ route('legal') }}#politica-privacidad" target="_blank" class="text-amberGlow-400 font-bold hover:underline">Política de Privacidad</a> y los <a href="{{ route('legal') }}#terminos-uso" target="_blank" class="text-amberGlow-400 font-bold hover:underline">Términos de Uso</a> de VoluntMatch Cusco. *
                        </span>
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <a href="{{ route('detalle-oportunidad', ['id' => $campana->id + 1000]) }}" class="px-6 py-4 rounded-2xl bg-slate-900 border border-slate-700 text-slate-300 font-bold text-xs text-center">
                        Cancelar
                    </a>
                    <button type="submit" id="btn-confirmar-donacion" class="flex-1 py-4 rounded-2xl bg-gradient-to-r from-amberGlow-400 via-amber-500 to-rose-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-xl shadow-amberGlow-500/20 hover:scale-[1.01] transition-all">
                        <i class="bi bi-receipt-cutoff me-1"></i> Validar Aporte y Generar Voucher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let metodoSeleccionado = 'Yape';

    function seleccionarMetodo(metodo) {
        metodoSeleccionado = metodo;
        document.querySelectorAll('.tab-pay').forEach(btn => {
            btn.classList.remove('bg-amberGlow-400', 'text-slate-950', 'border-amberGlow-400');
            btn.classList.add('bg-slate-950', 'text-slate-300', 'border-slate-800');
        });
        const active = document.getElementById(`tab-pay-${metodo}`);
        active.classList.remove('bg-slate-950', 'text-slate-300', 'border-slate-800');
        active.classList.add('bg-amberGlow-400', 'text-slate-950', 'border-amberGlow-400');

        document.getElementById('bloque-monetario').classList.toggle('hidden', metodo === 'Especie');
        document.getElementById('bloque-especie').classList.toggle('hidden', metodo !== 'Especie');
        document.getElementById('panel-tarjeta').classList.toggle('hidden', metodo !== 'Tarjeta');
    }

    function procesarDonacionDedicada(e) {
        e.preventDefault();

        if (!document.getElementById('acepto_legal_don').checked) {
            Swal.fire({ title: 'Consentimiento Requerido', text: 'Debes aceptar la Política de Privacidad y los Términos de Uso antes de validar tu aporte.', icon: 'warning', background: '#0f172a', color: '#fff' });
            return;
        }

        const btn = document.getElementById('btn-confirmar-donacion');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin me-1"></i> Validando y generando voucher...';

        const payload = {
            metodo_pago: metodoSeleccionado === 'Especie' ? 'Donación en Especie' : metodoSeleccionado,
            monto: document.getElementById('don-monto').value || 0,
            numero_operacion: document.getElementById('don-operacion').value.trim(),
            detalle_especie: document.getElementById('don-especie').value.trim(),
            donante_nombre: document.getElementById('don-nombre').value.trim(),
            es_anonimo: document.getElementById('don-anonimo').checked,
            mensaje_apoyo: document.getElementById('don-mensaje').value.trim()
        };

        fetch('/campana/{{ $campana->id }}/donar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload)
        })
        .then(r => r.json().then(d => {
            if (!r.ok) throw new Error(d.message || 'No se pudo validar la donación');
            return d;
        }))
        .then(data => {
            window.location.href = data.voucher_url;
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-receipt-cutoff me-1"></i> Validar Aporte y Generar Voucher';
            Swal.fire({ title: 'Verifica los datos', text: err.message, icon: 'warning', background: '#0f172a', color: '#fff' });
        });
    }
</script>
@endsection