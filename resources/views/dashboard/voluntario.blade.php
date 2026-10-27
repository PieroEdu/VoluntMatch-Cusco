<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Voluntario - VoluntMatch Cusco</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232E7D32'><path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5'/></svg>">
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Hoja de estilos principal -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-light">

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
                    <li><a href="{{ route('home') }}" class="activo"><i class="bi bi-speedometer2"></i> Mi Panel</a></li>
                    <li><a href="{{ route('contacto') }}"><i class="bi bi-envelope"></i> Soporte</a></li>
                </ul>
            </nav>

            <div class="acciones-usuario">
                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle d-flex align-items-center gap-2 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span>{{ Auth::user()->name ?? 'Usuario' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>

            <button class="boton-menu-movil" aria-label="Abrir menú de navegación">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <!-- MAIN CONTENIDO -->
    <main class="py-4">
        <div class="container">

            <!-- BANNER DE BIENVENIDA -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                <div class="card-body p-4 p-md-5 text-white position-relative" style="background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%) !important;">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <span class="badge bg-white text-success fw-bold px-3 py-2 rounded-pill mb-2">
                                <i class="bi bi-patch-check-fill text-success me-1"></i> Estudiante Voluntario Acreditado
                            </span>
                            <h1 class="fw-bold display-6 mb-2">¡Hola, {{ strtoupper(Auth::user()->name ?? 'LUIS') }}!</h1>
                            <p class="fs-6 opacity-90 mb-3">
                                Ingeniería de Sistemas e Informática • Universidad Continental (Sede Cusco)
                            </p>
                            <a href="{{ route('oportunidades') }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm">
                                <i class="bi bi-search me-1"></i> Explorar Nuevas Convocatorias
                            </a>
                        </div>
                        <div class="col-lg-4 text-center d-none d-lg-block">
                            <i class="bi bi-person-heart text-white-50" style="font-size: 7.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TARJETAS DE MÉTRICAS -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                <i class="bi bi-clock-history fs-3"></i>
                            </div>
                            <div>
                                <span class="text-muted small fw-semibold d-block">Horas Acumuladas</span>
                                <h3 class="fw-bold mb-0 text-dark">8 hrs</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 me-3 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                <i class="bi bi-file-earmark-check fs-3"></i>
                            </div>
                            <div>
                                <span class="text-muted small fw-semibold d-block">Postulaciones Activas</span>
                                <h3 class="fw-bold mb-0 text-dark">1</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                <i class="bi bi-geo-alt-fill fs-3"></i>
                            </div>
                            <div>
                                <span class="text-muted small fw-semibold d-block">Causas en Cusco</span>
                                <h3 class="fw-bold mb-0 text-dark">2</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-body p-3 d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px;">
                                <i class="bi bi-award-fill fs-3"></i>
                            </div>
                            <div>
                                <span class="text-muted small fw-semibold d-block">Certificados</span>
                                <h3 class="fw-bold mb-0 text-success">1 Listo</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MIS POSTULACIONES -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-0"><i class="bi bi-journal-check text-success me-2"></i>Mis Postulaciones a Voluntariado</h4>
                        <p class="text-muted small mb-0">Sincronizado en tiempo real</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted small text-uppercase fw-bold">
                                    <th>Causa / Proyecto</th>
                                    <th>Organización</th>
                                    <th>Fecha de Postulación</th>
                                    <th>Estado del Match</th>
                                    <th>Horas</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">Tutorías y Reforzamiento Escolar</div>
                                        <span class="badge bg-success bg-opacity-10 text-success">Educación</span>
                                    </td>
                                    <td>Hogar Infantil Azul Wasi</td>
                                    <td>04/10/2026</td>
                                    <td>
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Aceptado</span>
                                    </td>
                                    <td>8 hrs</td>
                                    <td class="text-end">
                                        <a href="{{ route('detalle-oportunidad') }}" class="btn btn-sm btn-outline-success rounded-pill fw-semibold"><i class="bi bi-eye"></i> Detalle</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RECOMENDACIONES SEGÚN TU PERFIL -->
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-3"><i class="bi bi-stars text-warning me-2"></i>Recomendaciones según tu perfil (Sistemas e Informática)</h4>
                
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <!-- Tarjeta 1 -->
                    <div class="col">
                        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-3 bg-light p-3 text-center" style="min-width: 70px;">
                                    <i class="bi bi-laptop text-success fs-1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-info bg-opacity-10 text-info fw-bold mb-1">SISTEMAS</span>
                                    <h5 class="fw-bold text-dark mb-1">Digitalización e Inventario de Donaciones</h5>
                                    <p class="text-muted small mb-2"><i class="bi bi-building me-1"></i> Asociación Verde Cusco • Wanchaq</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-success small fw-semibold"><i class="bi bi-people-fill"></i> 1 cupo disponible</span>
                                        <a href="{{ route('detalle-oportunidad') }}" class="btn btn-sm btn-success px-3 rounded-pill fw-semibold">Ver</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta 2 -->
                    <div class="col">
                        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-3 bg-light p-3 text-center" style="min-width: 70px;">
                                    <i class="bi bi-code-slash text-success fs-1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-info bg-opacity-10 text-info fw-bold mb-1">SISTEMAS</span>
                                    <h5 class="fw-bold text-dark mb-1">Soporte y Mantenimiento Web Comunitario</h5>
                                    <p class="text-muted small mb-2"><i class="bi bi-building me-1"></i> Red Social Cusco Activo • Centro</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-success small fw-semibold"><i class="bi bi-people-fill"></i> 2 cupos disponibles</span>
                                        <a href="{{ route('detalle-oportunidad') }}" class="btn btn-sm btn-success px-3 rounded-pill fw-semibold">Ver</a>
                                    </div>
                                </div>
                            </div>
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
                    Plataforma universitaria para la articulación ética de voluntariado e iniciativas de impacto en Cusco.
                </p>
            </div>
            <div class="footer-col">
                <h4>Navegación</h4>
                <ul>
                    <li><a href="{{ route('index') }}">Inicio</a></li>
                    <li><a href="{{ route('oportunidades') }}">Oportunidades</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Soporte</h4>
                <ul>
                    <li><a href="{{ route('contacto') }}">Centro de Ayuda</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Universidad Continental</h4>
                <p class="small text-muted mb-2">Programación Web - Grupo 6</p>
                <p class="small text-muted">Cusco, 2026</p>
            </div>
        </div>

        <div class="footer-creditos">
            <p>© 2026 VoluntMatch Cusco. Desarrollado por el Grupo 6. Licencia MIT.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/validaciones.js') }}"></script>

    @if(session('login_success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Bienvenido!',
            text: "{{ session('login_success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif
</body>
</html>