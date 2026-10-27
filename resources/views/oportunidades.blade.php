<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Oportunidades - VoluntMatch Cusco</title>
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

                <section>
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
                <p class="text-muted small">
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
                <p class="small text-muted">Perú, Cusco, 2026</p>
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