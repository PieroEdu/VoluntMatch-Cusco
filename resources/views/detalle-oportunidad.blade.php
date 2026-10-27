<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Convocatoria - VoluntMatch Cusco</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)' viewBox='0 0 16 16' fill='%232E7D32'><path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5'/></svg>">
    <!-- Bootstrap 5 CSS (CDN) -->
    <link rel="stylesheet" href="[https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css)">
    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="[https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css](https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css)">
    <!-- Hoja de estilos principal -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
                <a href="{{ route('login') }}" class="btn-custom btn-outline-custom">
                    <i class="bi bi-box-arrow-in-right"></i> Ingresar
                </a>
                <a href="{{ route('registro-voluntario') }}" class="btn-custom btn-primary-custom">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
            </div>

            <button class="boton-menu-movil" aria-label="Abrir menú de navegación">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <!-- MAIN: DETALLE DE CONVOCATORIA -->
    <main class="seccion-detalle">
        <div class="container-ancho">
            
            <div class="mb-4">
                <a href="{{ route('oportunidades') }}" class="text-decoration-none fw-semibold" style="color: var(--color-primario);">
                    <i class="bi bi-arrow-left"></i> Volver al Catálogo de Oportunidades
                </a>
            </div>

            <div class="layout-detalle" id="contenedor-detalle-oportunidad">
                <article class="tarjeta-detalle-main">
                    
                    <div class="rounded-3 overflow-hidden mb-4" style="height: 320px; background-color: #E2E8F0;">
                        <img src="" id="detalle-imagen" alt="Foto de la causa" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>

                    <div class="detalle-cabecera">
                        <span id="detalle-categoria" class="badge-categoria badge-educacion">Categoría</span>
                        <h1 id="detalle-titulo">Cargando oportunidad...</h1>
                        
                        <div class="detalle-ong-info flex-wrap mt-2">
                            <span><i class="bi bi-building text-success me-1"></i> <strong id="detalle-ong">Organización</strong></span>
                            <span><i class="bi bi-geo-alt-fill text-danger me-1"></i> <span id="detalle-distrito">Cusco</span></span>
                            <span><i class="bi bi-patch-check-fill text-primary me-1"></i> Organización Verificada</span>
                        </div>
                    </div>

                    <div class="detalle-bloque">
                        <h3><i class="bi bi-card-text text-success me-2"></i> Descripción de la Causa</h3>
                        <p id="detalle-descripcion" class="text-secondary" style="font-size: 1.05rem; line-height: 1.8;">
                            Cargando descripción...
                        </p>
                    </div>

                    <div class="detalle-bloque">
                        <h3><i class="bi bi-stars text-warning me-2"></i> Perfil y Habilidades Requeridas</h3>
                        <ul class="lista-habilidades" id="detalle-habilidades-lista"></ul>
                    </div>

                    <div class="detalle-bloque">
                        <h3><i class="bi bi-calendar-week text-success me-2"></i> Cronograma y Disponibilidad</h3>
                        <div class="row g-3 mt-1">
                            <div class="col-sm-6">
                                <div class="p-3 rounded bg-light border">
                                    <div class="small text-muted">Modalidad</div>
                                    <div class="fw-bold" id="detalle-modalidad">Presencial</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 rounded bg-light border">
                                    <div class="small text-muted">Horario de Actividades</div>
                                    <div class="fw-bold" id="detalle-horario">Por definir</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </article>

                <aside class="aside-postulacion">
                    <div class="postulacion-caja">
                        <span class="badge bg-success-subtle text-success mb-2 px-3 py-1 fw-bold">Convocatoria Activa</span>
                        <h4>¿Listo para hacer la diferencia?</h4>
                        <p class="small text-muted">Sé parte del equipo que genera soluciones tangibles en Cusco.</p>

                        <ul class="lista-datos-clave">
                            <li>
                                <i class="bi bi-people-fill"></i>
                                <div>
                                    <div class="fw-bold">Cupos Vacantes</div>
                                    <div class="text-muted" id="detalle-cupos">Cargando cupos...</div>
                                </div>
                            </li>
                            <li>
                                <i class="bi bi-clock-history"></i>
                                <div>
                                    <div class="fw-bold">Horas de Voluntariado</div>
                                    <div class="text-muted">Acredita hasta 30 horas</div>
                                </div>
                            </li>
                        </ul>

                        <button type="button" class="btn-custom btn-primary-custom w-100 py-3 fs-6" id="btn-abrir-postulacion">
                            <i class="bi bi-send-check-fill me-2"></i> Postular Ahora
                        </button>
                    </div>
                </aside>

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
                    Plataforma universitaria orientada a la democratización del voluntariado en Cusco.
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
                <p class="small text-muted">Cusco, Perú, 2026</p>
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