<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - VoluntMatch Cusco</title>
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%232E7D32'><path d='M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5'/></svg>">
    <!-- Bootstrap 5 CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                    <li><a href="{{ route('oportunidades') }}"><i class="bi bi-grid-fill"></i> Oportunidades</a></li>
                    <li><a href="{{ route('contacto') }}"><i class="bi bi-envelope"></i> Contacto</a></li>
                    <li><a href="{{ route('index') }}#como-funciona"><i class="bi bi-info-circle"></i> Cómo Funciona</a></li>
                </ul>
            </nav>

            <div class="acciones-usuario">
                <a href="{{ route('registro-voluntario') }}" class="btn-custom btn-primary-custom">
                    <i class="bi bi-person-plus"></i> Registrarse
                </a>
            </div>

            <button class="boton-menu-movil" aria-label="Abrir menú de navegación">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </header>

    <!-- MAIN: FORMULARIO DE INICIO DE SESIÓN -->
    <main class="py-5">
        <div class="container">
            <div class="formulario-contenedor" style="max-width: 480px;">
                <div class="formulario-cabecera">
                    <div class="metrica-icono mx-auto mb-3">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h2>Iniciar Sesión</h2>
                    <p>Accede a tu panel personalizado de VoluntMatch Cusco.</p>
                </div>

                <!-- Mensaje de Éxito al Registrarse -->
                @if(session('success'))
                    <div class="alert alert-success text-center mb-3">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    </div>
                @endif

                <!-- Mensaje de Error en Login -->
                @if(session('error'))
                    <div class="alert alert-danger text-center mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                    </div>
                @endif

                <form id="form-login" action="{{ route('logear') }}" method="POST" novalidate>
                    @csrf
                    
                    <!-- Selector de Rol -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted d-block text-center mb-2">SELECCIONA TU ROL DE ACCESO</label>
                        <div class="d-flex gap-2">
                            <label class="flex-fill p-2 border rounded text-center cursor-pointer" style="cursor: pointer; background: #FAFAFA;">
                                <input type="radio" name="rol" value="voluntario" checked class="me-1">
                                <span class="fw-semibold small"><i class="bi bi-person-fill text-success"></i> Voluntario</span>
                            </label>
                            <label class="flex-fill p-2 border rounded text-center cursor-pointer" style="cursor: pointer; background: #FAFAFA;">
                                <input type="radio" name="rol" value="ong" class="me-1">
                                <span class="fw-semibold small"><i class="bi bi-building text-warning"></i> ONG / Albergue</span>
                            </label>
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="form-campo">
                        <label for="login-correo">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="login-correo" placeholder="ejemplo@continental.edu.pe" value="{{ old('email') }}" required>
                    </div>

                    <!-- Contraseña -->
                    <div class="form-campo">
                        <label for="login-password">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="login-password" placeholder="••••••••" required>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="recordar-sesion" checked>
                            <label class="form-check-label small text-muted" for="recordar-sesion">Recordar sesión</label>
                        </div>
                        <a href="#" class="small text-decoration-none" style="color: var(--color-primario);">¿Olvidaste tu contraseña?</a>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn-custom btn-primary-custom py-2 fs-6">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar al Sistema
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <p class="small text-muted mb-2">¿Aún no tienes cuenta registrada?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('registro-voluntario') }}" class="small fw-bold" style="color: var(--color-primario);">Registro Voluntario</a>
                        <span class="text-muted">|</span>
                        <a href="{{ route('registro-organizacion') }}" class="small fw-bold" style="color: var(--color-acento);">Registro ONG</a>
                    </div>
                </div>

                <div class="mt-4 p-3 rounded bg-light border">
                    <div class="small fw-bold text-secondary mb-2 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i> Cuentas Demo para Evaluación:
                    </div>
                    <div class="small text-muted mb-1">
                        <strong>ONG Hanpinawasi:</strong> <code>ong@hanpinawasitravel.com</code> / <code>password123</code>
                    </div>
                    <div class="small text-muted mb-1">
                        <strong>ONG Azul Wasi:</strong> <code>contacto@azulwasi.org</code> / <code>password123</code>
                    </div>
                    <div class="small text-muted">
                        <strong>Estudiante:</strong> <code>voluntario@continental.edu.pe</code> / <code>password123</code>
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/validaciones.js') }}"></script>
</body>
</html>