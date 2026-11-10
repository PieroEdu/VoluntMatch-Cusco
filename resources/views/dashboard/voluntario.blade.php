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
                    <li><a href="{{ route('dashboard-organizacion') }}"><i class="bi bi-building"></i> Panel ONG</a></li>
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
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="bi bi-person me-2"></i>Mi Perfil</a></li>
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

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- BANNER DE BIENVENIDA -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                <div class="card-body p-4 p-md-5 text-white position-relative" style="background: linear-gradient(135deg, #1B5E20 0%, #2E7D32 100%) !important;">
                    <div class="row align-items-center">
                        <div class="col-lg-8 mb-3 mb-lg-0">
                            <span class="badge bg-white text-success fw-bold px-3 py-2 rounded-pill mb-2">
                                <i class="bi bi-patch-check-fill text-success me-1"></i> Estudiante Voluntario Acreditado
                            </span>
                            <h1 class="fw-bold display-6 mb-2">¡Hola, {{ strtoupper(Auth::user()->name ?? 'VOLUNTARIO') }}!</h1>
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
                                <h3 class="fw-bold mb-0 text-dark" id="metric-horas">{{ $horasAcumuladas ?? 0 }} hrs</h3>
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
                                <h3 class="fw-bold mb-0 text-dark" id="metric-postulaciones">{{ $postulacionesActivas ?? 0 }}</h3>
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
                                <h3 class="fw-bold mb-0 text-dark">{{ $causasCusco ?? 6 }}</h3>
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
                                <h3 class="fw-bold mb-0 text-success">{{ $certificados ?? 0 }} Listo(s)</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MIS POSTULACIONES -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold text-dark mb-0"><i class="bi bi-journal-check text-success me-2"></i>Mis Postulaciones a Voluntariado</h4>
                        <p class="text-muted small mb-0">Listado actualizado de convocatorias a las que te has postulado</p>
                    </div>
                    <div>
                        <a href="{{ route('oportunidades') }}" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i> Nueva Postulación
                        </a>
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
                            <tbody id="tabla-mis-postulaciones-body">
                                @forelse($postulaciones as $post)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $post->oportunidad->titulo ?? 'Voluntariado Cusco' }}</div>
                                        <span class="badge {{ $post->oportunidad->clase_insignia ?? 'badge-educacion' }}">{{ $post->oportunidad->categoria ?? 'General' }}</span>
                                    </td>
                                    <td>{{ $post->oportunidad->ong ?? 'Organización Social' }}</td>
                                    <td>{{ $post->fecha ? \Carbon\Carbon::parse($post->fecha)->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if($post->estado === 'Aceptado')
                                            <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i> Aceptado</span>
                                        @elseif($post->estado === 'Rechazado')
                                            <span class="badge bg-danger text-white px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill me-1"></i> Rechazado</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i> Pendiente</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->horas_acumuladas }} hrs</td>
                                    <td class="text-end">
                                        <a href="{{ route('detalle-oportunidad', ['id' => $post->oportunidad_id]) }}" class="btn btn-sm btn-outline-success rounded-pill fw-semibold me-1"><i class="bi bi-eye"></i> Detalle</a>
                                        @if($post->estado === 'Pendiente')
                                        <form action="{{ route('postulaciones.cancelar', $post->id) }}" method="POST" class="d-inline form-cancelar-postulacion">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill fw-semibold" title="Cancelar Postulación" onclick="return confirm('¿Seguro que deseas cancelar esta postulación?');">
                                                <i class="bi bi-trash"></i> Cancelar
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                        Aún no tienes postulaciones registradas en tu cuenta.
                                        <div class="mt-2">
                                            <a href="{{ route('oportunidades') }}" class="btn btn-sm btn-success rounded-pill px-3">
                                                <i class="bi bi-search me-1"></i> Explorar Convocatorias
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- RECOMENDACIONES SEGÚN TU PERFIL -->
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-3"><i class="bi bi-stars text-warning me-2"></i>Recomendaciones según tu perfil (Sistemas e Informática)</h4>
                
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @forelse($recomendaciones as $rec)
                    <div class="col">
                        <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-3 bg-light p-3 text-center" style="min-width: 70px;">
                                    <i class="bi bi-laptop text-success fs-1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-info bg-opacity-10 text-info fw-bold mb-1">{{ strtoupper($rec->categoria) }}</span>
                                    <h5 class="fw-bold text-dark mb-1">{{ $rec->titulo }}</h5>
                                    <p class="text-muted small mb-2"><i class="bi bi-building me-1"></i> {{ $rec->ong }} • {{ $rec->distrito }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-success small fw-semibold"><i class="bi bi-people-fill"></i> {{ $rec->cupos_restantes }} cupos disponibles</span>
                                        <a href="{{ route('detalle-oportunidad', ['id' => $rec->id]) }}" class="btn btn-sm btn-success px-3 rounded-pill fw-semibold">Postular</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">No hay recomendaciones adicionales en este momento.</p>
                    </div>
                    @endforelse
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
                <p class="small text-white">
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
                <p class="small text-white mb-2">Ingeniería de Sistemas e Informática</p>
                <p class="small text-white mb-2">Asignatura: Programación Web</p>
                <p class="small text-white">Cusco, 2026</p>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/validaciones.js') }}"></script>
</body>
</html>
