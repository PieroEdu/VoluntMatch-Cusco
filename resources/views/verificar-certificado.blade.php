<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación Oficial de Certificado - VoluntMatch Cusco</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6 font-['Plus_Jakarta_Sans']">

    <div class="max-w-xl w-full bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl space-y-6">
        <div class="flex items-center gap-3 border-b border-slate-800 pb-5">
            <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" class="w-12 h-12 object-contain">
            <div>
                <h1 class="font-black text-lg text-white">VoluntMatch Cusco</h1>
                <p class="text-xs text-slate-400">Sistema Público de Validación de Certificados</p>
            </div>
        </div>

        @if($certificado)
            <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/40 flex items-center gap-3">
                <i class="bi bi-patch-check-fill text-3xl text-emerald-400"></i>
                <div>
                    <span class="block text-sm font-black text-emerald-400 uppercase">Certificado Auténtico y Verificado</span>
                    <span class="text-xs text-slate-300 font-mono">Código: {{ $certificado->codigo_certificado }}</span>
                </div>
            </div>

            <div class="space-y-3 text-xs bg-slate-950 p-5 rounded-2xl border border-slate-800">
                <div class="flex justify-between border-b border-slate-800/80 pb-2">
                    <span class="text-slate-400">Voluntario:</span>
                    <span class="font-bold text-white">{{ $certificado->voluntario_nombre }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-800/80 pb-2">
                    <span class="text-slate-400">DNI:</span>
                    <span class="font-bold text-white">{{ $certificado->voluntario_dni ?? '---' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-800/80 pb-2">
                    <span class="text-slate-400">Universidad / Carrera:</span>
                    <span class="font-bold text-white text-right">{{ $certificado->universidad ?? '' }} - {{ $certificado->voluntario_carrera ?? '' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-800/80 pb-2">
                    <span class="text-slate-400">Campaña Social:</span>
                    <span class="font-bold text-emerald-400 text-right">{{ $certificado->proyecto }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-800/80 pb-2">
                    <span class="text-slate-400">Organización Emisora:</span>
                    <span class="font-bold text-white">{{ $certificado->organizacion }} {{ $certificado->ong_ruc ? '(RUC: ' . $certificado->ong_ruc . ')' : '' }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-800/80 pb-2">
                    <span class="text-slate-400">Horas Validadas:</span>
                    <span class="font-black text-emerald-400 text-sm">{{ $certificado->horas_validadas }} Horas</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Fecha de Emisión:</span>
                    <span class="font-bold text-white">{{ \Carbon\Carbon::parse($certificado->updated_at)->format('d/m/Y') }}</span>
                </div>
            </div>
        @else
            <div class="p-5 rounded-2xl bg-rose-500/15 border border-rose-500/40 text-center space-y-2">
                <i class="bi bi-x-octagon-fill text-4xl text-rose-400"></i>
                <h2 class="text-base font-black text-rose-400">Código No Válido o Inexistente</h2>
                <p class="text-xs text-slate-300">El código <strong>{{ $codigo }}</strong> no corresponde a ningún certificado oficial emitido en VoluntMatch Cusco.</p>
            </div>
        @endif

        <div class="text-center pt-2">
            <a href="{{ route('index') }}" class="text-xs font-bold text-emerald-400 hover:underline">Ir a VoluntMatch Cusco →</a>
        </div>
    </div>

</body>
</html>