<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Oportunidades - VoluntMatch Cusco</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232E7D32'><path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5'/></svg>">
    <!-- Bootstrap 5 CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Hoja de estilos principal -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Leaflet Map CSS (API de Mapas OpenStreetMap) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

</head>
<body>

    <!-- CABECERA PRINCIPAL -->
    <header class="header-principal">
        <div class="header-container">
            <div class="logo-sitio">
                <a href="{{ route('index') }}" class="d-flex align-items-center text-decoration-none">
                    <i class="bi bi-people-fill me-2"></i>
                    <span style="color: var(--color-primario); font-weight: 800;">VoluntMatch</span>
                    <span class="logo-tag">Cusco</span>
                </a>
            </div>

            <nav class="nav-principal">
                <ul>
                    <li><a href="{{ route('index') }}"><i class="bi bi-house-door"></i> Inicio</a></li>
                    <li><a href="{{ route('oportunidades') }}" class="activo"><i class="bi bi-grid-fill"></i> Oportunidades</a></li>
                    <li><a href="{{ route('contacto') }}"><i class="bi bi-envelope"></i> Contacto</a></li>
                    <li><a href="{{ route('index') }}#como-funciona"><i class="bi bi-info-circle"></i> Cómo Funciona</a></li>
                </ul>
            </nav>

            <div class="acciones-usuario">
                @auth
                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle d-flex align-items-center gap-2 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-2"></i>Mi Panel</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-custom btn-outline-custom">
                    <i class="bi bi-box-arrow-in-right"></i> Ingresar
                </a>
                <a href="{{ route('registro-voluntario') }}" class="btn-custom btn-primary-custom">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
                @endauth
            </div>

            <button class="boton-menu-movil" aria-label="Abrir menú de navegación">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="seccion-catalogo">
        <div class="container-ancho">
            
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 pb-2 border-bottom">
                <div>
                    <h1 class="h3 fw-bold mb-1" style="color: #1A202C;">Oportunidades de Voluntariado en Cusco</h1>
                    <p class="text-muted small mb-0">Encuentra iniciativas sociales filtradas según tus áreas de formación.</p>
                </div>
                <div class="mt-2 mt-md-0">
                    <span class="badge px-3 py-2 fs-6 fw-semibold" style="background-color: var(--color-primario-claro); color: var(--color-primario-oscuro);" id="contador-resultados">
                        Cargando convocatorias...
                    </span>
                </div>
            </div>

            <div class="layout-catalogo">
                <aside class="aside-filtros">
                    <div class="filtro-titulo">
                        <h3><i class="bi bi-sliders2 text-success me-2"></i> Filtros</h3>
                        <a href="#" id="btn-limpiar-filtros" class="small text-danger fw-semibold text-decoration-none">
                            <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                        </a>
                    </div>

                    <div class="filtro-grupo">
                        <h4>Búsqueda Directa</h4>
                        <div class="input-group-custom p-1 border rounded bg-light d-flex align-items-center">
                            <i class="bi bi-search text-muted ms-2 me-2"></i>
                            <input type="text" id="buscar-oportunidad" class="form-control form-control-sm border-0 bg-transparent shadow-none" placeholder="Habilidad, ONG o título...">
                        </div>
                    </div>

                    <div class="filtro-grupo">
                        <h4>Área de Apoyo</h4>
                        <label class="opcion-filtro">
                            <input type="checkbox" class="filtro-check-categoria" value="Educación">
                            <span>Educación / Tutorías</span>
                        </label>
                        <label class="opcion-filtro">
                            <input type="checkbox" class="filtro-check-categoria" value="Sistemas">
                            <span>Sistemas e Informática</span>
                        </label>
                        <label class="opcion-filtro">
                            <input type="checkbox" class="filtro-check-categoria" value="Medio Ambiente">
                            <span>Medio Ambiente / Ecología</span>
                        </label>
                        <label class="opcion-filtro">
                            <input type="checkbox" class="filtro-check-categoria" value="Logística">
                            <span>Logística y Acopio de Campo</span>
                        </label>
                    </div>

                    <div class="filtro-grupo">
                        <h4>Ubicación (Distrito)</h4>
                        <label class="opcion-filtro">
                            <input type="radio" name="distrito" class="filtro-radio-distrito" value="todos" checked>
                            <span>Todo Cusco</span>
                        </label>
                        <label class="opcion-filtro">
                            <input type="radio" name="distrito" class="filtro-radio-distrito" value="Cusco Centro">
                            <span>Cusco Centro</span>
                        </label>
                        <label class="opcion-filtro">
                            <input type="radio" name="distrito" class="filtro-radio-distrito" value="San Jerónimo">
                            <span>San Jerónimo</span>
                        </label>
                        <label class="opcion-filtro">
                            <input type="radio" name="distrito" class="filtro-radio-distrito" value="Wanchaq">
                            <span>Wanchaq</span>
                        </label>
                        <label class="opcion-filtro">
                            <input type="radio" name="distrito" class="filtro-radio-distrito" value="Santiago">
                            <span>Santiago</span>
                        </label>
                    </div>
                </aside>

                <section class="flex-grow-1">
                    <!-- Selector de Vista: Cuadrícula o Mapa Interactivo -->
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-white rounded border">
                        <span class="small fw-semibold text-muted ms-2">
                            <i class="bi bi-geo-alt text-success me-1"></i> Visualización de Convocatorias:
                        </span>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" id="btn-vista-lista" class="btn btn-success active" onclick="cambiarVistaCatalogo('lista')">
                                <i class="bi bi-grid-fill me-1"></i> Lista
                            </button>
                            <button type="button" id="btn-vista-mapa" class="btn btn-outline-success" onclick="cambiarVistaCatalogo('mapa')">
                                <i class="bi bi-map-fill me-1"></i> Mapa Cusco (API)
                            </button>
                        </div>
                    </div>

                    <!-- Contenedor del Mapa Interactivo (Leaflet + Nominatim OpenStreetMap) -->
                    <div id="contenedor-mapa" class="mb-4 rounded border bg-light" style="display: none; height: 520px; width: 100%; position: relative; z-index: 1;">
                        <div class="d-flex justify-content-center align-items-center h-100 text-muted" id="mapa-cargando">
                            <div class="spinner-border spinner-border-sm text-success me-2" role="status"></div>
                            <span>Cargando mapa interactivo de Cusco...</span>
                        </div>
                    </div>

                    <div class="grid-oportunidades" id="grid-catalogo">
                        <!-- Carga dinámica -->
                    </div>
                </section>
            </div>

        </div>
    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="footer-sitio">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-people-fill text-success fs-3 me-2"></i>
                    <h4 class="mb-0 text-white">VoluntMatch Cusco</h4>
                </div>
                <p class="small text-white">
                    Canalizando el talento de estudiantes universitarios hacia causas que transforman nuestra comunidad.
                </p>
            </div>
            <div class="footer-col">
                <h4>Navegación</h4>
                <ul>
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="{{ route('oportunidades') }}">Oportunidades</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto y Soporte</a></li>
                    <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Universidad Continental</h4>
                <p class="small text-white mb-2">Ingeniería de Sistemas e Informática</p>
                <p class="small text-white mb-2">Asignatura: Programación Web</p>
                <p class="small text-white">Perú, Cusco, 2026</p>
            </div>
            <div class="footer-col">
                <h4><i class="bi bi-people-fill text-success me-1"></i> Creadores</h4>
                <ul class="creadores-lista">
                    <li><span>Piero Edu Alcca Moron</span> <span class="badge-porcentaje">100%</span></li>
                    <li><span>Luis Angel Dueñas Quintana</span> <span class="badge-porcentaje">100%</span></li>
                    <li><span>Patrick Antonio Leguia Choquemamani</span> <span class="badge-porcentaje">100%</span></li>
                    <li><span>Alexis Sebastian Rojas Lopez</span> <span class="badge-porcentaje">100%</span></li>
                </ul>
            </div>
        </div>

        <div class="footer-creditos">
            <div class="footer-creadores-banner">
                <i class="bi bi-award-fill text-success me-1"></i>
                <strong>Creadores del Proyecto:</strong>
                <span>Piero Edu Alcca Moron <strong>100%</strong></span> •
                <span>Luis Angel Dueñas Quintana <strong>100%</strong></span> •
                <span>Patrick Antonio Leguia Choquemamani <strong>100%</strong></span> •
                <span>Alexis Sebastian Rojas Lopez <strong>100%</strong></span>
            </div>
            <p class="mb-0 text-white">© 2026 VoluntMatch Cusco. Desarrollado por el Grupo 6. Universidad Continental. Licencia MIT.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js)"></script>
    <!-- Script Principal -->
    <script src="{{ asset('js/main.js') }}"></script>
    <!-- Leaflet JS & Inicialización del Mapa Interactivo -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        let mapaCusco = null;
        let marcadoresCapa = null;

        function cambiarVistaCatalogo(modo) {
            const btnLista = document.getElementById('btn-vista-lista');
            const btnMapa = document.getElementById('btn-vista-mapa');
            const gridCatalogo = document.getElementById('grid-catalogo');
            const contenedorMapa = document.getElementById('contenedor-mapa');

            if (modo === 'mapa') {
                btnLista.classList.remove('btn-success', 'active');
                btnLista.classList.add('btn-outline-success');
                btnMapa.classList.remove('btn-outline-success');
                btnMapa.classList.add('btn-success', 'active');

                gridCatalogo.style.display = 'none';
                contenedorMapa.style.display = 'block';

                if (!mapaCusco) {
                    inicializarMapaCusco();
                } else {
                    setTimeout(() => mapaCusco.invalidateSize(), 200);
                }
            } else {
                btnMapa.classList.remove('btn-success', 'active');
                btnMapa.classList.add('btn-outline-success');
                btnLista.classList.remove('btn-outline-success');
                btnLista.classList.add('btn-success', 'active');

                contenedorMapa.style.display = 'none';
                gridCatalogo.style.display = 'grid';
            }
        }

        async function inicializarMapaCusco() {
            try {
                // Coordenadas centrales de Cusco: Plaza de Armas
                mapaCusco = L.map('contenedor-mapa').setView([-13.517088, -71.978535], 13);

                // Capa de OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | VoluntMatch Cusco'
                }).addTo(mapaCusco);

                marcadoresCapa = L.layerGroup().addTo(mapaCusco);

                // Consultar API JSON de oportunidades con coordenadas
                const response = await fetch("{{ route('api.oportunidades.mapa') }}");
                const resData = await response.json();

                const cargandoEl = document.getElementById('mapa-cargando');
                if (cargandoEl) cargandoEl.remove();

                if (resData.success && resData.oportunidades.length > 0) {
                    resData.oportunidades.forEach(op => {
                        const popupContent = `
                            <div style="font-family: inherit; min-width: 200px;">
                                <span class="badge ${op.clase_insignia || 'bg-success'} mb-1">${op.categoria}</span>
                                <h6 style="margin: 4px 0 2px 0; font-weight: 700; color: #1a202c;">${op.titulo}</h6>
                                <p style="margin: 0 0 6px 0; font-size: 12px; color: #4a5568;">
                                    <i class="bi bi-building"></i> ${op.ong}<br>
                                    <i class="bi bi-geo-alt"></i> ${op.distrito} | <i class="bi bi-people"></i> ${op.cupos_restantes} cupos
                                </p>
                                <a href="${op.url_detalle}" class="btn btn-sm btn-success text-white py-1 px-2 w-100" style="font-size: 11px; text-decoration: none;">
                                    Ver Detalle y Postular &rarr;
                                </a>
                            </div>
                        `;

                        L.marker([op.lat, op.lng])
                            .addTo(marcadoresCapa)
                            .bindPopup(popupContent);
                    });
                }
            } catch (err) {
                console.error("Error al inicializar mapa:", err);
                const contenedor = document.getElementById('contenedor-mapa');
                if (contenedor) {
                    contenedor.innerHTML = '<div class="alert alert-warning m-3">No se pudo cargar la API del mapa. Por favor, revisa la conexión.</div>';
                }
            }
        }
    </script>
</body>
</html>
