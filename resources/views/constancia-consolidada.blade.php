<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Récord Consolidado de Proyección Social - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @media print { .no-print { display: none !important; } body { background: white !important; padding: 0 !important; } }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex flex-col items-center p-6 font-['Plus_Jakarta_Sans']">

    <div class="no-print max-w-4xl w-full flex items-center justify-between mb-6 bg-slate-800 px-6 py-4 rounded-2xl border border-slate-700 text-white">
        <a href="{{ route('home') }}" class="text-xs font-bold text-slate-300 hover:text-white flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Volver a mi Dashboard
        </a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-emerald-500 text-slate-950 font-black text-xs uppercase tracking-wider hover:bg-emerald-400 flex items-center gap-2">
            <i class="bi bi-printer-fill"></i> Imprimir Reporte para Universidad
        </button>
    </div>

    <div class="max-w-4xl w-full bg-white text-slate-900 p-10 rounded-xl shadow-2xl border-2 border-slate-300 space-y-6">
        <div class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
            <div class="flex items-center gap-3">
                <img src="https://i.postimg.cc/43YJt89w/logo-removebg-preview.png" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="font-black text-lg text-slate-900">RÉCORD CONSOLIDADO DE VOLUNTARIADO</h1>
                    <p class="text-xs text-slate-500">VoluntMatch Cusco • Reporte Oficial de Horas Acumuladas</p>
                </div>
            </div>
            <div class="text-right text-xs">
                <p class="font-bold text-slate-700">Fecha: {{ date('d/m/Y') }}</p>
                <p class="text-emerald-700 font-black">Total Acumulado: {{ $totalHoras }} Horas</p>
            </div>
        </div>

        <div class="bg-slate-100 p-4 rounded-xl text-xs grid grid-cols-2 gap-3">
            <p><strong>Estudiante:</strong> {{ $user->name }}</p>
            <p><strong>DNI:</strong> {{ $user->dni ?? '---' }}</p>
            <p><strong>Carrera:</strong> {{ $user->carrera ?? 'Estudiante Universitario' }}</p>
            <p><strong>Correo Institucional:</strong> {{ $user->email }}</p>
        </div>

        <table class="w-full text-left text-xs border-collapse border border-slate-300">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="p-2.5 border border-slate-300">Código Certificado</th>
                    <th class="p-2.5 border border-slate-300">Campaña / Proyecto Social</th>
                    <th class="p-2.5 border border-slate-300">Organización Aliada</th>
                    <th class="p-2.5 border border-slate-300 text-center">Horas</th>
                    <th class="p-2.5 border border-slate-300 text-center">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completadas as $item)
                    <tr>
                        <td class="p-2.5 border border-slate-300 font-mono font-bold">{{ $item->codigo_certificado }}</td>
                        <td class="p-2.5 border border-slate-300 font-semibold">{{ $item->proyecto }}</td>
                        <td class="p-2.5 border border-slate-300">{{ $item->organizacion }}</td>
                        <td class="p-2.5 border border-slate-300 text-center font-black">{{ $item->horas_validadas }} hrs</td>
                        <td class="p-2.5 border border-slate-300 text-center">{{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-slate-500">Aún no cuenta con campañas finalizadas y certificadas.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-emerald-50 font-black">
                    <td colspan="3" class="p-2.5 border border-slate-300 text-right">TOTAL HORAS CONVALIDABLES:</td>
                    <td class="p-2.5 border border-slate-300 text-center text-emerald-800 text-sm">{{ $totalHoras }} hrs</td>
                    <td class="p-2.5 border border-slate-300"></td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>