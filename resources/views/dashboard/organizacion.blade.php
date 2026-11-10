<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Organización / ONG - VoluntMatch Cusco</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232E7D32'><path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5'/></svg>">
    <!-- Bootstrap 5 CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
                    <li><a href="{{ route('home') }}"><i class="bi bi-speedometer2"></i> Mi Panel</a></li>
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
                                <div class="h3 fw-bold mb-0 text-success">{{ $convocatoriasActivas ?? 0 }} Causas</div>
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
                                <div class="h3 fw-bold mb-0 text-primary" id="num-postulantes-recibidos">{{ $postulantesRecibidos ?? 0 }}</div>
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
                                <div class="h3 fw-bold mb-0 text-warning" id="num-matches-confirmados">{{ $matchesConfirmados ?? 0 }}</div>
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
                                <div class="h3 fw-bold mb-0 text-dark">{{ $tasaCobertura ?? 0 }}%</div>
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
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h3 class="h5 fw-bold mb-0" style="color: #2D3748;">
                        <i class="bi bi-person-lines-fill text-warning me-2"></i> Postulantes Universitarios Pendientes de Evaluación
                    </h3>
                    <span class="small text-muted">Panel de Gestión de Matches</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
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
                            @forelse($postulaciones as $post)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $post->estudiante }}</div>
                                    <div class="small text-muted">
                                        <i class="bi bi-envelope me-1"></i>{{ $post->correo }}
                                        @if($post->telefono) • <i class="bi bi-telephone me-1"></i>{{ $post->telefono }} @endif
                                    </div>
                                    @if($post->mensaje_motivacion)
                                    <div class="small text-secondary fst-italic mt-1">
                                        <i class="bi bi-chat-quote me-1"></i>"{{ Str::limit($post->mensaje_motivacion, 80) }}"
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $post->carrera ?? 'Estudiante' }}</div>
                                    <div class="small text-muted">{{ $post->universidad ?? 'Universidad Continental' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $post->oportunidad->titulo ?? 'Voluntariado' }}</div>
                                    <span class="badge {{ $post->oportunidad->clase_insignia ?? 'badge-educacion' }}">{{ $post->oportunidad->categoria ?? 'General' }}</span>
                                </td>
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
                                <td class="text-end">
                                    @if($post->estado !== 'Aceptado')
                                    <form action="{{ route('postulaciones.estado', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="estado" value="Aceptado">
                                        <input type="hidden" name="horas" value="10">
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-semibold me-1" title="Aceptar Postulante">
                                            <i class="bi bi-check-lg"></i> Aceptar
                                        </button>
                                    </form>
                                    @endif

                                    @if($post->estado !== 'Rechazado')
                                    <form action="{{ route('postulaciones.estado', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="estado" value="Rechazado">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold" title="Rechazar Postulante" onclick="return confirm('¿Seguro que deseas rechazar este postulante?');">
                                            <i class="bi bi-x-lg"></i> Rechazar
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2 text-secondary"></i>
                                    No hay postulantes registrados por el momento.
                                </td>
                            </tr>
                            @endforelse
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
                    @forelse($oportunidades as $op)
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge-categoria {{ $op->clase_insignia }} mb-1">{{ $op->categoria }}</span>
                                <h5 class="h6 fw-bold mb-1">{{ $op->titulo }}</h5>
                                <div class="small text-muted">
                                    <i class="bi bi-geo-alt"></i> {{ $op->distrito }} • {{ $op->cupos_restantes }} cupos restantes ({{ $op->cupos }} totales)
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $op->cupos_restantes > 0 ? 'bg-success' : 'bg-secondary' }} mb-1 d-block">
                                    {{ $op->cupos_restantes > 0 ? 'Activa' : 'Cerrada' }}
                                </span>
                                <a href="{{ route('detalle-oportunidad', ['id' => $op->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill py-0 px-2 small">Ver Detalle</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center text-muted py-3">
                        No hay convocatorias publicadas aún.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL PARA PUBLICAR NUEVA CONVOCATORIA -->
    <div class="modal-overlay" id="modal-crear-convocatoria">
        <div class="modal-caja shadow-lg" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
            <button class="modal-cerrar" id="btn-cerrar-modal-convocatoria" type="button" aria-label="Cerrar">&times;</button>
            
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-2 me-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="bi bi-plus-circle-fill fs-5 text-success"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">Publicar Nueva Convocatoria</h4>
                    <span class="small text-muted">Registra una causa para recibir postulantes en Cusco</span>
                </div>
            </div>

            <form action="{{ route('oportunidades.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Título de la Convocatoria *</label>
                    <input type="text" class="form-control" name="titulo" placeholder="Ej. Taller de Lectura y Arte Infantil" required>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Organización / ONG *</label>
                        <input type="text" class="form-control" name="ong" value="Hogar Infantil Azul Wasi" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Distrito *</label>
                        <select class="form-select" name="distrito" required>
                            <option value="Cusco Centro">Cusco Centro</option>
                            <option value="San Jerónimo" selected>San Jerónimo</option>
                            <option value="Wanchaq">Wanchaq</option>
                            <option value="Santiago">Santiago</option>
                            <option value="San Sebastián">San Sebastián</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Categoría *</label>
                        <select class="form-select" name="categoria" required>
                            <option value="Educación">Educación</option>
                            <option value="Sistemas">Sistemas</option>
                            <option value="Medio Ambiente">Medio Ambiente</option>
                            <option value="Logística">Logística</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Modalidad *</label>
                        <select class="form-select" name="modalidad" required>
                            <option value="Presencial">Presencial</option>
                            <option value="Híbrido">Híbrido</option>
                            <option value="Virtual">Virtual</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Horario *</label>
                        <input type="text" class="form-control" name="horario" placeholder="Ej. Sábados 9:00 AM - 1:00 PM" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-bold">Cupos Vacantes *</label>
                        <input type="number" class="form-control" name="cupos" min="1" max="50" value="5" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Descripción de la Causa *</label>
                    <textarea class="form-control" name="descripcion" rows="3" placeholder="Describe los objetivos y actividades que realizarán los voluntarios..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Habilidades Requeridas (separadas por coma)</label>
                    <input type="text" class="form-control" name="habilidades" placeholder="Ej. Paciencia, Docencia básica, Empatía">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-secondary px-3" id="btn-cancelar-modal-convocatoria">Cancelar</button>
                    <button type="submit" class="btn btn-success px-4 fw-semibold">
                        <i class="bi bi-send me-1"></i> Publicar Convocatoria
                    </button>
                </div>
            </form>
        </div>
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Script Principal -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Modal para publicar convocatoria
        const btnAbrirConvocatoria = document.getElementById('btn-abrir-crear-convocatoria');
        const modalConvocatoria = document.getElementById('modal-crear-convocatoria');
        const btnCerrarConvocatoria = document.getElementById('btn-cerrar-modal-convocatoria');
        const btnCancelarConvocatoria = document.getElementById('btn-cancelar-modal-convocatoria');

        if (btnAbrirConvocatoria && modalConvocatoria) {
            btnAbrirConvocatoria.addEventListener('click', () => modalConvocatoria.classList.add('activo'));
        }
        if (btnCerrarConvocatoria && modalConvocatoria) {
            btnCerrarConvocatoria.addEventListener('click', () => modalConvocatoria.classList.remove('activo'));
        }
        if (btnCancelarConvocatoria && modalConvocatoria) {
            btnCancelarConvocatoria.addEventListener('click', () => modalConvocatoria.classList.remove('activo'));
        }
        if (modalConvocatoria) {
            modalConvocatoria.addEventListener('click', (e) => {
                if (e.target === modalConvocatoria) modalConvocatoria.classList.remove('activo');
            });
        }
    </script>
</body>
</html>
