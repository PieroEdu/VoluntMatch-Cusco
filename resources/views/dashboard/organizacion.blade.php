<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Organización / ONG - VoluntMatch Cusco</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)' viewBox='0 0 16 16' fill='%232E7D32'><path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5'/></svg>">
    <!-- Bootstrap 5 CSS (CDN) -->
    <link rel="stylesheet" href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css)">
    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="[https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css](https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css)">
    <!-- Hoja de estilos principal -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body style="background-color: #F8FAFC;">

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
                    <li><a href="{{ route('oportunidades') }}"><i class="bi bi-grid-fill"></i> Oportunidades</a></li>
                    <li><a href="{{ route('dashboard-organizacion') }}" class="activo"><i class="bi bi-building"></i> Panel ONG</a></li>
                    <li><a href="{{ route('contacto') }}"><i class="bi bi-envelope"></i> Soporte</a></li>
                </ul>
            </nav>

            <div class="acciones-usuario">
                <span class="d-flex align-items-center small fw-semibold text-secondary me-2">
                    <i class="bi bi-building-fill fs-5 text-warning me-1"></i> Hogar Infantil Azul Wasi
                </span>
                <a href="{{ route('logout') }}" class="btn-custom btn-outline-custom" style="padding: 0.35rem 0.8rem; font-size: 0.85rem;" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </a>
            </div>

            <button class="boton-menu-movil" aria-label="Abrir menú de navegación">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <!-- MAIN: DASHBOARD DE LA ORGANIZACIÓN -->
    <main class="py-4">
        <div class="container-ancho px-3">
            
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 p-4 bg-white rounded-3 border">
                <div>
                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 mb-2 fw-semibold">
                        <i class="bi bi-shield-check me-1"></i> Organización Social Verificada
                    </span>
                    <h1 class="h3 fw-bold mb-1" style="color: #1A202C;">Hogar Infantil Azul Wasi</h1>
                    <p class="text-muted small mb-0">
                        RUC: 20601234567 • San Jerónimo / Oropesa, Cusco • Acreditación Social 2026
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <button type="button" class="btn-custom btn-accent-custom" id="btn-abrir-crear-convocatoria">
                        <i class="bi bi-plus-circle-fill me-1"></i> Publicar Nueva Convocatoria
                    </button>
                </div>
            </div>

            <!-- INDICADORES CLAVE DE LA ONG -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="p-3 bg-white rounded-3 border shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Convocatorias Activas</div>
                                <div class="h3 fw-bold mb-0 text-success">2 Causas</div>
                            </div>
                            <div class="metrica-icono m-0" style="width: 48px; height: 48px; font-size: 1.3rem;">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="p-3 bg-white rounded-3 border shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Postulantes Recibidos</div>
                                <div class="h3 fw-bold mb-0 text-primary" id="num-postulantes-recibidos">3</div>
                            </div>
                            <div class="metrica-icono m-0" style="width: 48px; height: 48px; font-size: 1.3rem; background-color: #E1F5FE; color: #0288D1;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="p-3 bg-white rounded-3 border shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Matches Confirmados</div>
                                <div class="h3 fw-bold mb-0 text-warning" id="num-matches-confirmados">1</div>
                            </div>
                            <div class="metrica-icono m-0" style="width: 48px; height: 48px; font-size: 1.3rem; background-color: #FFF3E0; color: #E65100;">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="p-3 bg-white rounded-3 border shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small">Tasa de Cobertura</div>
                                <div class="h3 fw-bold mb-0 text-dark">75%</div>
                            </div>
                            <div class="metrica-icono m-0" style="width: 48px; height: 48px; font-size: 1.3rem; background-color: #ECEFF1; color: #455A64;">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLA DE EVALUACIÓN DE POSTULANTES -->
            <div class="bg-white rounded-3 border p-4 mb-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="h5 fw-bold mb-0" style="color: #2D3748;">
                        <i class="bi bi-person-lines-fill text-warning me-2"></i> Postulantes Universitarios Pendientes de Evaluación
                    </h3>
                    <span class="small text-muted">Panel de Gestión de Matches</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light small">
                            <tr>
                                <th>Estudiante / Voluntario</th>
                                <th>Carrera / Universidad</th>
                                <th>Convocatoria Solicitada</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones de Match</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-postulantes-ong-body">
                            <!-- Inyectado por JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- LISTADO DE CONVOCATORIAS PUBLICADAS -->
            <div class="p-4 bg-white rounded-3 border shadow-sm">
                <h3 class="h5 fw-bold mb-3" style="color: #2D3748;">
                    <i class="bi bi-collection-fill text-success me-2"></i> Mis Convocatorias Publicadas
                </h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge-categoria badge-educacion mb-1">Educación</span>
                                <h5 class="h6 fw-bold mb-1">Tutorías y Reforzamiento Escolar</h5>
                                <div class="small text-muted"><i class="bi bi-geo-alt"></i> San Jerónimo • 2 cupos restantes</div>
                            </div>
                            <span class="badge bg-success">Activa</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge-categoria badge-logistica mb-1">Logística</span>
                                <h5 class="h6 fw-bold mb-1">Apoyo en Talleres Productivos</h5>
                                <div class="small text-muted"><i class="bi bi-geo-alt"></i> San Jerónimo • 3 cupos restantes</div>
                            </div>
                            <span class="badge bg-success">Activa</span>
                        </div>
                    </div>
                </div>
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
                <p class="text-muted small">
                    Espacio de articulación entre instituciones sin fines de lucro y la comunidad universitaria cusqueña.
                </p>
            </div>
            <div class="footer-col">
                <h4>Navegación</h4>
                <ul>
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="{{ route('oportunidades') }}">Oportunidades</a></li>
                    <li><a href="{{ route('dashboard-organizacion') }}">Panel de Convocatorias</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Soporte a Organizaciones</h4>
                <ul>
                    <li><a href="{{ route('contacto') }}">Atención al Coordinador</a></li>
                    <li><a href="#">Convenios Institucionales</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Universidad Continental</h4>
                <p class="small text-muted mb-2">Proyecto de Programación Web</p>
                <p class="small text-muted">Cusco, 2026</p>
            </div>
        </div>

        <div class="footer-creditos">
            <p>© 2026 VoluntMatch Cusco. Desarrollado por el Grupo 6. Licencia MIT.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js)"></script>
    <!-- Script Principal -->
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>