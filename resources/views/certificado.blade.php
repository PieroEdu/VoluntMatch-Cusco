<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Oficial - {{ $postulacion->codigo_certificado }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Playfair+Display:ital,wght@0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .cert-box { box-shadow: none !important; border: 4px double #065f46 !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex flex-col items-center justify-center p-6 font-['Plus_Jakarta_Sans']">

    <div class="no-print max-w-4xl w-full flex flex-wrap items-center justify-between gap-3 mb-6 bg-slate-800 px-6 py-4 rounded-2xl border border-slate-700 text-white">
        <a href="{{ route('home') }}" class="text-xs font-bold text-slate-300 hover:text-white flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Volver a mi Dashboard
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('certificado.verificar', ['codigo' => $postulacion->codigo_certificado]) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-700 text-emerald-300 font-bold text-xs hover:bg-slate-600 transition-all">
                <i class="bi bi-qr-code-scan me-1"></i> Probar Validación QR
            </a>
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-slate-950 font-black text-xs uppercase tracking-wider hover:bg-emerald-400 transition-all flex items-center gap-2 shadow-lg">
                <i class="bi bi-printer-fill"></i> Imprimir / Guardar como PDF
            </button>
        </div>
    </div>

    <div class="cert-box max-w-4xl w-full bg-white text-slate-900 p-10 sm:p-14 rounded-xl shadow-2xl border-8 border-double border-emerald-800 relative overflow-hidden">
        
        <div class="flex items-center justify-between border-b-2 border-emerald-800/20 pb-6">
            <div class="flex items-center gap-3">
                <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" class="w-14 h-14 object-contain">
                <div>
                    <h3 class="font-black text-lg text-emerald-950 tracking-tight">VOLUNTMATCH CUSCO</h3>
                    <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-widest">Red Universitaria de Proyección Social</p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 text-[10px] font-black uppercase tracking-wider border border-emerald-300">
                    Constancia Verificada
                </span>
                <p class="text-xs font-mono font-bold text-slate-500 mt-1">ID: {{ $postulacion->codigo_certificado }}</p>
            </div>
        </div>

        <div class="text-center py-9 space-y-5">
            <p class="text-xs font-extrabold uppercase tracking-[0.25em] text-emerald-700">Otorga el presente</p>
            <h1 class="text-4xl sm:text-5xl font-bold font-['Playfair_Display'] text-slate-900">CERTIFICADO DE VOLUNTARIADO</h1>
            <p class="text-sm text-slate-600">A favor del estudiante universitario:</p>
            
            <div class="py-2">
                <h2 class="text-3xl font-black text-emerald-950 uppercase tracking-wide border-b-2 border-slate-300 inline-block px-8 pb-2">
                    {{ $postulacion->voluntario_nombre }}
                </h2>
                <p class="text-xs font-bold text-slate-500 mt-2">
                    DNI N° <strong class="text-slate-800">{{ $postulacion->voluntario_dni ?? '---' }}</strong> • {{ $postulacion->universidad ?? 'Estudiante Universitario' }} ({{ $postulacion->voluntario_carrera ?? 'Proyección Social' }})
                </p>
            </div>

            <p class="text-sm text-slate-700 max-w-2xl mx-auto leading-relaxed pt-1">
                Por haber cumplido satisfactoriamente su participación activa en el proyecto social denominado <strong class="text-slate-950">"{{ $postulacion->proyecto }}"</strong>, organizado por la institución aliada <strong class="text-emerald-900">{{ $postulacion->organizacion }}</strong> {{ $postulacion->ong_ruc ? '(RUC: ' . $postulacion->ong_ruc . ')' : '' }} en la región de Cusco, acumulando un total de:
            </p>

            <div class="py-2">
                <span class="px-6 py-2.5 rounded-full bg-emerald-950 text-emerald-300 font-black text-lg tracking-wider">
                    {{ $postulacion->horas_validadas }} HORAS DE PROYECCIÓN SOCIAL VALIDADAS
                </span>
            </div>

            @if($postulacion->resena_ong)
                <p class="text-xs italic text-slate-500 max-w-xl mx-auto">
                    Evaluación institucional ({{ $postulacion->calificacion ?? 5 }}/5 ★): "{{ $postulacion->resena_ong }}"
                </p>
            @endif
        </div>

        <div class="grid grid-cols-3 items-end pt-8 border-t border-slate-200 text-center text-xs">
            <div class="space-y-1">
                <div class="w-40 border-b border-slate-800 mx-auto pb-1 font-bold text-emerald-900 italic">Firmado Digitalmente</div>
                <p class="font-black text-slate-900">{{ $postulacion->organizacion }}</p>
                <p class="text-[10px] text-slate-500">Organización Aliada Cusco</p>
            </div>

            <div class="flex flex-col items-center justify-center">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=95x95&data={{ urlencode(route('certificado.verificar', ['codigo' => $postulacion->codigo_certificado])) }}" alt="QR Verificación" class="w-20 h-20 border p-1 rounded-lg">
                <span class="text-[9px] font-mono text-slate-400 mt-1">Escanea para validar autenticidad</span>
            </div>

            <div class="space-y-1">
                <div class="w-40 border-b border-slate-800 mx-auto pb-1 font-bold text-slate-700">{{ \Carbon\Carbon::parse($postulacion->updated_at)->format('d/m/Y') }}</div>
                <p class="font-black text-slate-900">Cusco, Perú</p>
                <p class="text-[10px] text-slate-500">Fecha de Emisión Oficial</p>
            </div>
        </div>
    </div>

</body>
</html>