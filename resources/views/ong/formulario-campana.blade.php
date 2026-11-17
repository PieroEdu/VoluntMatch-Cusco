@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] py-10 px-6 max-w-7xl mx-auto space-y-8 z-10">

    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-amberGlow-500/10 rounded-full blur-[160px]"></div>
    </div>

    <!-- CABECERA DE PÁGINA COMPLETA -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-6 relative z-10">
        <div class="space-y-1">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-amberGlow-400 transition-colors mb-2">
                <i class="bi bi-arrow-left"></i> Volver al Panel Institucional ONG
            </a>
            <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
                {{ $campana ? 'Editar Campaña de Voluntariado' : 'Publicar Nueva Campaña en Cusco' }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-400">
                Organización: <strong class="text-amberGlow-400">{{ $user->name }}</strong> • RUC: {{ $user->ruc ?? 'Verificado' }}
            </p>
        </div>
    </div>

    <!-- FORMULARIO DE PANTALLA COMPLETA EN 2 COLUMNAS -->
    <form id="form-campana-completa" onsubmit="guardarCampanaCompleta(event)" class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative z-10">
        @csrf
        <input type="hidden" name="imagen_base64" id="field-imagen-base64" value="">

        <!-- COLUMNA IZQUIERDA (7 COLS): DATOS, FECHAS, IA GEMINI Y DONACIONES OPCIONALES -->
        <div class="lg:col-span-7 space-y-6">

            <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl border border-slate-800 space-y-4">
                <h2 class="text-base font-black text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-amberGlow-500/20 text-amberGlow-400 flex items-center justify-center text-xs">1</span>
                    Información General y Cronograma
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Título de la Campaña *</label>
                        <input type="text" name="titulo" id="field-titulo" value="{{ $campana->titulo ?? '' }}" required placeholder="Ej. Reforestación e Impacto Ambiental en Valle Sagrado" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Categoría *</label>
                        <select name="categoria" id="field-categoria" class="w-full px-3.5 py-3 rounded-xl bg-slate-950 border border-slate-800 text-slate-200 text-xs focus:border-amberGlow-400 focus:outline-none">
                            @foreach(['Medio Ambiente', 'Tecnología', 'Educación', 'Salud Mental', 'Gestión / Marketing', 'Salud'] as $cat)
                                <option value="{{ $cat }}" {{ ($campana->categoria ?? '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-2xl bg-slate-950/80 border border-slate-800">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-rose-400 mb-1"><i class="bi bi-calendar-event me-1"></i> Fecha de Inicio *</label>
                        <input type="date" name="fecha_inicio" value="{{ $campana->fecha_inicio ?? date('Y-m-d') }}" required class="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-rose-400 mb-1"><i class="bi bi-calendar-check me-1"></i> Fecha de Fin *</label>
                        <input type="date" name="fecha_fin" value="{{ $campana->fecha_fin ?? date('Y-m-d', strtotime('+45 days')) }}" required class="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-amberGlow-400 mb-1"><i class="bi bi-hourglass-split me-1"></i> Cierre Postulaciones *</label>
                        <input type="datetime-local" name="fecha_cierre" value="{{ isset($campana->fecha_cierre) ? str_replace(' ', 'T', substr($campana->fecha_cierre, 0, 16)) : date('Y-m-d\T23:59', strtotime('+15 days')) }}" required class="w-full px-3 py-2.5 rounded-xl bg-slate-900 border border-slate-800 text-white text-xs">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Horas a Convalidar *</label>
                        <input type="number" name="horas" id="field-horas" value="{{ $campana->horas ?? 20 }}" min="1" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Vacantes / Cupos *</label>
                        <input type="number" name="cupos" value="{{ $campana->cupos ?? 10 }}" min="1" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Horario / Modalidad *</label>
                        <input type="text" name="horario" value="{{ $campana->horario ?? '¡Es Flexible! Nos acomodamos a tu horario.' }}" required class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: DESCRIPCIÓN, FUNCIONES Y REQUISITOS + IA GEMINI -->
            <div class="glass-card p-6 sm:p-7 rounded-3xl border border-slate-800 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-base font-black text-white flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-purple-500/20 text-purple-300 flex items-center justify-center text-xs">2</span>
                        Detalle del Programa y Requisitos
                    </h2>
                    <button type="button" id="btn-redactar-ia" onclick="generarTextosConIA()" class="px-4 py-2 rounded-xl bg-gradient-to-r from-purple-500 to-cyanNeon-400 text-slate-950 font-black text-xs hover:scale-105 transition-transform flex items-center gap-1.5 shadow-lg">
                        <i class="bi bi-magic"></i> Redactar con IA Gemini
                    </button>
                </div>

                <div>
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Descripción Larga del Proyecto *</label>
                    <textarea name="descripcion" id="field-descripcion" rows="4" required placeholder="Describe el impacto social o ambiental de esta campaña en Cusco..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-amberGlow-400 focus:outline-none">{{ $campana->descripcion ?? '' }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-brand-400 mb-1.5">Lo que harás (Funciones) *</label>
                        <textarea name="funciones" id="field-funciones" rows="5" required placeholder="Escribe una función por línea..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-brand-400 focus:outline-none">{{ $campana->funciones ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-cyanNeon-400 mb-1.5">Lo que buscamos en ti (Requisitos) *</label>
                        <textarea name="requisitos" id="field-requisitos" rows="5" required placeholder="Escribe un requisito por línea..." class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:border-cyanNeon-400 focus:outline-none">{{ $campana->requisitos ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 3: APOYO SOLIDARIO / DONACIONES OPCIONALES (SIN META OBLIGATORIA) -->
            @php
                $aceptaDon = (bool) ($campana->acepta_donaciones ?? false);
                $tieneMeta = (bool) ($campana->tiene_meta_donacion ?? false);
            @endphp
            <div class="glass-card p-6 sm:p-7 rounded-3xl border border-amberGlow-500/30 space-y-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-base font-black text-amberGlow-400 flex items-center gap-2">
                            <i class="bi bi-heart-pulse-fill text-rose-400"></i> Apoyo Solidario / Donaciones (Opcional)
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Si lo activas, los voluntarios y ciudadanos podrán realizar aportes voluntarios libres (en soles o en especie) y recibir un voucher oficial.
                        </p>
                    </div>
                    <input type="checkbox" name="acepta_donaciones" id="field-acepta-donaciones" value="1" {{ $aceptaDon ? 'checked' : '' }} onchange="toggleCamposDonacion()" class="w-6 h-6 accent-amber-400 rounded cursor-pointer shrink-0">
                </div>

                <div id="bloque-config-donacion" class="{{ $aceptaDon ? '' : 'hidden' }} space-y-4 pt-4 border-t border-slate-800">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1.5">Número Yape / Plin Oficial de la ONG *</label>
                            <input type="text" name="yape_numero" id="field-yape-numero" value="{{ $campana->yape_numero ?? '' }}" maxlength="9" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,9)" placeholder="Ej. 984123456" class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-white font-mono text-xs">
                        </div>

                        <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 flex items-center justify-between gap-3">
                            <div>
                                <label for="field-tiene-meta" class="text-xs font-bold text-slate-200 cursor-pointer block">¿Fijar meta referencial? (Opcional)</label>
                                <span class="text-[10px] text-slate-500">Si no lo marcas, la recaudación es libre sin límite ni barra de meta.</span>
                            </div>
                            <input type="checkbox" name="tiene_meta_donacion" id="field-tiene-meta" value="1" {{ $tieneMeta ? 'checked' : '' }} onchange="toggleMetaOpcional()" class="w-5 h-5 accent-emerald-400 rounded cursor-pointer shrink-0">
                        </div>
                    </div>

                    <div id="bloque-input-meta" class="{{ $tieneMeta ? '' : 'hidden' }}">
                        <label class="block text-[11px] font-bold uppercase text-emerald-400 mb-1.5">Monto de Meta Referencial (S/)</label>
                        <input type="number" name="meta_donacion" id="field-meta-donacion" value="{{ ($campana->meta_donacion ?? 0) > 0 ? $campana->meta_donacion : 500 }}" min="1" step="1" class="w-full sm:w-64 px-4 py-3 rounded-xl bg-slate-950 border border-emerald-500/40 text-emerald-400 font-black text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA (5 COLS): PORTADA VISUAL, MAPA DE CUSCO Y GUARDAR -->
        <div class="lg:col-span-5 space-y-6">
            <div class="sticky top-24 space-y-6">

                <!-- IMAGEN DE PORTADA -->
                <div class="glass-card p-6 rounded-3xl border border-slate-800 space-y-4">
                    <h2 class="text-base font-black text-white flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-cyanNeon-500/20 text-cyanNeon-400 flex items-center justify-center text-xs">3</span>
                        Imagen de Portada
                    </h2>

                    <div class="relative rounded-2xl overflow-hidden border border-slate-700 h-48 bg-slate-950">
                        <img id="preview-imagen-campana" src="{{ $campana->imagen ?? 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?auto=format&fit=crop&w=1000&q=80' }}" class="w-full h-full object-cover">
                        <span id="estado-foto-texto" class="absolute bottom-2 right-2 px-2.5 py-1 rounded-lg bg-slate-950/85 text-[10px] font-bold text-emerald-400 border border-emerald-500/30">
                            Vista previa lista
                        </span>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Subir foto desde tu computadora:</label>
                            <input type="file" id="input-foto-pc" accept="image/*" onchange="procesarFotoLocal(this)" class="w-full text-xs text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amberGlow-500/20 file:text-amberGlow-400 hover:file:bg-amberGlow-500/30">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">O pegar enlace URL de imagen:</label>
                            <input type="url" name="imagen_url" id="field-imagen-url" value="{{ (isset($campana->imagen) && str_starts_with($campana->imagen, 'http')) ? $campana->imagen : '' }}" oninput="actualizarPreviewUrl(this.value)" placeholder="https://..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- UBICACIÓN EXACTA EN CUSCO -->
                <div class="glass-card p-6 rounded-3xl border border-slate-800 space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-black text-white flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-brand-500/20 text-brand-400 flex items-center justify-center text-xs">4</span>
                            Ubicación en Cusco
                        </h2>
                        <span id="coords-badge" class="text-[10px] font-mono text-slate-400">
                            Lat: {{ $campana->latitud ?? '-13.53195' }}, Lng: {{ $campana->longitud ?? '-71.96746' }}
                        </span>
                    </div>

                    <div class="space-y-2.5">
                        <select name="distrito" id="select-distrito" onchange="centrarDistrito(this.value)" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                            @foreach(['Cusco Centro', 'San Jerónimo', 'Wanchaq', 'Santiago', 'San Sebastián', 'Poroy'] as $dist)
                                <option value="{{ $dist }}" {{ ($campana->distrito ?? '') === $dist ? 'selected' : '' }}>{{ $dist }}</option>
                            @endforeach
                        </select>

                        <div class="flex gap-2">
                            <input type="text" name="direccion_exacta" id="input-direccion-cusco" value="{{ $campana->direccion_exacta ?? '' }}" placeholder="Ej. Plaza de Armas de San Jerónimo, Cusco" class="flex-1 px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 text-white text-xs">
                            <button type="button" onclick="buscarDireccionCusco()" class="px-3.5 py-2.5 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 font-bold text-xs hover:bg-emerald-500 hover:text-slate-950 transition-all">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="latitud" id="input-latitud" value="{{ $campana->latitud ?? '-13.53195' }}">
                    <input type="hidden" name="longitud" id="input-longitud" value="{{ $campana->longitud ?? '-71.96746' }}">

                    <div id="mapa-selector-cusco" class="w-full h-56 rounded-2xl border border-slate-700 z-10"></div>
                </div>

                <!-- BOTONES DE ACCIÓN -->
                <div class="flex gap-3">
                    <a href="{{ route('home') }}" class="px-5 py-4 rounded-2xl bg-slate-900 border border-slate-700 text-slate-300 font-bold text-xs text-center hover:bg-slate-800 transition-all">
                        Cancelar
                    </a>
                    <button type="submit" id="btn-guardar-conv" class="flex-1 py-4 rounded-2xl bg-gradient-to-r from-amberGlow-400 via-amber-500 to-brand-500 text-slate-950 font-black text-xs uppercase tracking-wider shadow-xl shadow-amberGlow-500/20 hover:scale-[1.02] transition-all">
                        <i class="bi bi-rocket-takeoff-fill me-1"></i> {{ $campana ? 'Guardar Cambios de Campaña' : 'Publicar Campaña Oficialmente' }}
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    let mapaOng = null;
    let marcadorOng = null;

    const coordsDistritos = {
        'Cusco Centro': [-13.51676, -71.97877],
        'San Jerónimo': [-13.54361, -71.88583],
        'Wanchaq': [-13.52492, -71.96120],
        'Santiago': [-13.52450, -71.98410],
        'San Sebastián': [-13.53050, -71.93330],
        'Poroy': [-13.49580, -72.04360]
    };

    document.addEventListener('DOMContentLoaded', function() {
        const latInit = parseFloat("{{ $campana->latitud ?? '-13.53195' }}");
        const lngInit = parseFloat("{{ $campana->longitud ?? '-71.96746' }}");

        mapaOng = L.map('mapa-selector-cusco').setView([latInit, lngInit], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap Cusco'
        }).addTo(mapaOng);

        marcadorOng = L.marker([latInit, lngInit], { draggable: true }).addTo(mapaOng);
        mapaOng.on('click', (e) => actualizarCoordenadas(e.latlng.lat, e.latlng.lng));
        marcadorOng.on('dragend', (e) => {
            const pos = e.target.getLatLng();
            actualizarCoordenadas(pos.lat, pos.lng);
        });
    });

    function toggleCamposDonacion() {
        const activo = document.getElementById('field-acepta-donaciones').checked;
        document.getElementById('bloque-config-donacion').classList.toggle('hidden', !activo);
    }

    function toggleMetaOpcional() {
        const activo = document.getElementById('field-tiene-meta').checked;
        document.getElementById('bloque-input-meta').classList.toggle('hidden', !activo);
    }

    function actualizarCoordenadas(lat, lng) {
        const latFix = lat.toFixed(5);
        const lngFix = lng.toFixed(5);
        if (marcadorOng) marcadorOng.setLatLng([lat, lng]);
        document.getElementById('input-latitud').value = latFix;
        document.getElementById('input-longitud').value = lngFix;
        document.getElementById('coords-badge').textContent = `Lat: ${latFix}, Lng: ${lngFix}`;
    }

    function centrarDistrito(distrito) {
        if (coordsDistritos[distrito] && mapaOng) {
            const [lat, lng] = coordsDistritos[distrito];
            mapaOng.setView([lat, lng], 14);
            actualizarCoordenadas(lat, lng);
        }
    }

    function buscarDireccionCusco() {
        const texto = document.getElementById('input-direccion-cusco').value.trim();
        if (!texto) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(texto + ', Cusco, Peru')}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    mapaOng.setView([lat, lng], 16);
                    actualizarCoordenadas(lat, lng);
                } else {
                    Swal.fire({ title: 'No encontrado', text: 'Haz clic directamente sobre el punto en el mapa de Cusco.', icon: 'info', background: '#0f172a', color: '#fff' });
                }
            });
    }

    function procesarFotoLocal(input) {
        if (!input.files || !input.files[0]) return;
        const file = input.files[0];
        document.getElementById('estado-foto-texto').textContent = 'Optimizando imagen...';

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const maxWidth = 1200;
                let width = img.width;
                let height = img.height;

                if (width > maxWidth) {
                    height = Math.round((height * maxWidth) / width);
                    width = maxWidth;
                }

                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(img, 0, 0, width, height);

                const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
                document.getElementById('field-imagen-base64').value = dataUrl;
                document.getElementById('preview-imagen-campana').src = dataUrl;
                document.getElementById('estado-foto-texto').textContent = '¡Imagen cargada y lista!';
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function actualizarPreviewUrl(url) {
        if (url && url.startsWith('http')) {
            document.getElementById('field-imagen-base64').value = '';
            document.getElementById('preview-imagen-campana').src = url;
            document.getElementById('estado-foto-texto').textContent = 'Vista previa desde URL';
        }
    }

    async function generarTextosConIA() {
        const titulo = document.getElementById('field-titulo').value.trim();
        const categoria = document.getElementById('field-categoria').value;
        const distrito = document.getElementById('select-distrito').value;
        const horas = document.getElementById('field-horas').value;
        const btn = document.getElementById('btn-redactar-ia');

        if (!titulo) {
            Swal.fire({ title: 'Falta el título', text: 'Escribe primero el título de la campaña para que la IA genere el contenido.', icon: 'info', background: '#0f172a', color: '#fff' });
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Redactando...';

        try {
            const res = await fetch("{{ route('ia.mejorar-descripcion') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ titulo, categoria, distrito, horas })
            });
            const data = await res.json();

            if (data.ok) {
                document.getElementById('field-descripcion').value = data.descripcion;
                document.getElementById('field-funciones').value = data.funciones;
                document.getElementById('field-requisitos').value = data.requisitos;
            }
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-magic"></i> Redactar con IA Gemini';
        }
    }

    function guardarCampanaCompleta(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-guardar-conv');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin me-1"></i> Guardando campaña...';

        const url = "{{ $campana ? route('actualizar-convocatoria', ['id' => $campana->id]) : route('crear-convocatoria') }}";
        const formData = new FormData(document.getElementById('form-campana-completa'));

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            Swal.fire({
                title: '¡Campaña Guardada!',
                text: data.message,
                icon: 'success',
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#10b981'
            }).then(() => {
                window.location.href = "{{ route('home') }}";
            });
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = 'Reintentar Guardar';
        });
    }
</script>
@endsection