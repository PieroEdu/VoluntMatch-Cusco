/**
 * ==============================================================================
 * VoluntMatch Cusco - Script Principal (main.js)
 * Asignatura: Programación Web | Universidad Continental (Sede Cusco)
 * Autores: Piero Alcca, Luis Dueñas, Patrick Leguia, Alexis Rojas
 * ==============================================================================
 * Módulo encargado del ciclo de vida de la aplicación, renderizado dinámico del
 * catálogo, control de menús móviles, modales interactivos y persistencia local.
 */

// Claves utilizadas en localStorage
const STORAGE_KEYS = {
    OPORTUNIDADES: 'voluntmatch_oportunidades',
    POSTULACIONES: 'voluntmatch_postulaciones',
    USUARIO_ACTUAL: 'voluntmatch_usuario_actual'
};

/**
 * Catálogo base de causas sociales y oportunidades emblemáticas de la región Cusco.
 * Precargadas en localStorage para la sustentación y demostración del sistema.
 */
const OPORTUNIDADES_CUSCO_INICIALES = [
    {
        id: "op-001",
        titulo: "Tutorías y Reforzamiento Escolar",
        ong: "Hogar Infantil Azul Wasi",
        distrito: "San Jerónimo",
        categoria: "Educación",
        badgeClase: "badge-educacion",
        modalidad: "Presencial",
        horario: "Sábados 9:00 AM - 1:00 PM",
        cupos: 4,
        cuposRestantes: 2,
        imagen: "https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80",
        descripcion: "Apoyo pedagógico y tutorías dinámicas en matemáticas y lectura para niños y adolescentes en situación de acogimiento en el valle sur de Cusco.",
        habilidades: ["Docencia básica", "Paciencia", "Dinámicas grupales", "Empatía"],
        destacada: true
    },
    {
        id: "op-002",
        titulo: "Digitalización e Inventario de Donaciones",
        ong: "Asociación Verde Cusco",
        distrito: "Wanchaq",
        categoria: "Sistemas",
        badgeClase: "badge-sistemas",
        modalidad: "Híbrido",
        horario: "Lunes y Miércoles 3:00 PM - 6:00 PM",
        cupos: 2,
        cuposRestantes: 1,
        imagen: "https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&w=600&q=80",
        descripcion: "Diseño e implementación de una base de datos ligera y módulo web para clasificar donaciones de ropa y materiales ecológicos en Wanchaq.",
        habilidades: ["Excel avanzado", "HTML/CSS", "Bases de datos", "Organización"],
        destacada: true
    },
    {
        id: "op-003",
        titulo: "Campaña de Reforestación y Concientización",
        ong: "Colectivo Cusco Sostenible",
        distrito: "Santiago",
        categoria: "Medio Ambiente",
        badgeClase: "badge-ambiente",
        modalidad: "Presencial",
        horario: "Domingos 7:30 AM - 12:30 PM",
        cupos: 8,
        cuposRestantes: 6,
        imagen: "https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80",
        descripcion: "Jornadas comunitarias de plantación de especies nativas (queñua y chachacomo) y talleres de reciclaje en zonas altas del distrito de Santiago.",
        habilidades: ["Trabajo en equipo", "Resistencia física", "Educación ambiental"],
        destacada: true
    },
    {
        id: "op-004",
        titulo: "Clasificación y Logística de Abrigarte Cusco",
        ong: "Colectivo Cusco Solidario",
        distrito: "Cusco Centro",
        categoria: "Logística",
        badgeClase: "badge-logistica",
        modalidad: "Presencial",
        horario: "Viernes 2:00 PM - 6:00 PM",
        cupos: 6,
        cuposRestantes: 2,
        imagen: "https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=600&q=80",
        descripcion: "Recepción, selección y empaquetado de frazadas y prendas abrigadoras para comunidades afectadas por heladas en la provincia de Cusco.",
        habilidades: ["Orden", "Logística básica", "Proactividad"],
        destacada: false
    },
    {
        id: "op-005",
        titulo: "Talleres de Alfabetización Digital para Adultos",
        ong: "Asociación Yachay Wasi",
        distrito: "San Jerónimo",
        categoria: "Educación",
        badgeClase: "badge-educacion",
        modalidad: "Presencial",
        horario: "Martes y Jueves 4:00 PM - 6:00 PM",
        cupos: 3,
        cuposRestantes: 3,
        imagen: "https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80",
        descripcion: "Enseñanza de uso básico de computadoras, trámites en línea y prevención de estafas digitales a madres líderes de comedores populares.",
        habilidades: ["Habilidades comunicativas", "Manejo de internet", "Didáctica"],
        destacada: false
    },
    {
        id: "op-006",
        titulo: "Soporte y Mantenimiento Web Comunitario",
        ong: "Red Social Cusco Activo",
        distrito: "Cusco Centro",
        categoria: "Sistemas",
        badgeClase: "badge-sistemas",
        modalidad: "Virtual",
        horario: "Flexible (4 horas semanales)",
        cupos: 2,
        cuposRestantes: 2,
        imagen: "https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80",
        descripcion: "Actualización de contenidos, soporte a servidores web y mejora de accesibilidad en el portal informativo de la red de colectivos de Cusco.",
        habilidades: ["HTML5", "CSS3", "JavaScript", "GitHub"],
        destacada: false
    }
];

/**
 * Inicializa los datos en localStorage si es la primera vez que se ejecuta la app.
 */
function inicializarDatosStorage() {
    try {
        if (!localStorage.getItem(STORAGE_KEYS.OPORTUNIDADES)) {
            localStorage.setItem(STORAGE_KEYS.OPORTUNIDADES, JSON.stringify(OPORTUNIDADES_CUSCO_INICIALES));
        }
        if (!localStorage.getItem(STORAGE_KEYS.POSTULACIONES)) {
            // Postulaciones de prueba demostrativas
            const postulacionesDemo = [
                {
                    id: "post-001",
                    oportunidadId: "op-001",
                    titulo: "Tutorías y Reforzamiento Escolar",
                    ong: "Hogar Infantil Azul Wasi",
                    fecha: "2026-10-04",
                    estado: "Aceptado",
                    estudiante: "Piero Edu Alcca Moron",
                    horasAcumuladas: 8
                }
            ];
            localStorage.setItem(STORAGE_KEYS.POSTULACIONES, JSON.stringify(postulacionesDemo));
        }
    } catch (e) {
        console.warn("Aviso: No se pudo acceder a localStorage.", e);
    }
}

/**
 * Obtiene la lista completa de oportunidades desde el almacenamiento.
 * @returns {Array<Object>} Lista de oportunidades
 */
function obtenerOportunidades() {
    try {
        const datos = localStorage.getItem(STORAGE_KEYS.OPORTUNIDADES);
        return datos ? JSON.parse(datos) : OPORTUNIDADES_CUSCO_INICIALES;
    } catch (e) {
        return OPORTUNIDADES_CUSCO_INICIALES;
    }
}

/**
 * Genera el elemento HTML de una tarjeta de oportunidad.
 * @param {Object} op Objeto con la información de la oportunidad
 * @returns {string} Marcado HTML seguro de la tarjeta
 */
function crearMarcadoTarjeta(op) {
    const clasePocos = op.cuposRestantes <= 2 ? 'poco' : '';
    return `
        <article class="tarjeta-oportunidad" data-id="${op.id}" data-categoria="${op.categoria}" data-distrito="${op.distrito}">
            <div class="tarjeta-imagen-wrapper">
                <span class="badge-categoria ${op.badgeClase}">${op.categoria}</span>
                <img src="${op.imagen}" alt="${op.titulo}" loading="lazy">
            </div>
            <div class="tarjeta-cuerpo">
                <h3>${op.titulo}</h3>
                <div class="tarjeta-ong">
                    <i class="bi bi-building"></i> ${op.ong}
                </div>
                <p class="tarjeta-descripcion">${op.descripcion}</p>
                <div class="tarjeta-meta">
                    <div class="tarjeta-meta-item">
                        <i class="bi bi-geo-alt-fill"></i> ${op.distrito}, Cusco
                    </div>
                    <div class="tarjeta-meta-item">
                        <i class="bi bi-clock-fill"></i> ${op.horario}
                    </div>
                </div>
            </div>
            <div class="tarjeta-footer">
                <span class="cupos-texto ${clasePocos}">
                    <i class="bi bi-people-fill"></i> ${op.cuposRestantes} cupos libres
                </span>
                <a href="detalle-oportunidad.html?id=${op.id}" class="btn-custom btn-primary-custom" style="padding: 0.4rem 0.9rem; font-size: 0.85rem;">
                    Ver Detalle
                </a>
            </div>
        </article>
    `;
}

/**
 * Renderiza las oportunidades en el catálogo con soporte de filtrado en tiempo real.
 */
function configurarCatalogo() {
    const contenedorGrid = document.getElementById('grid-catalogo');
    const contadorResultados = document.getElementById('contador-resultados');
    const inputBusqueda = document.getElementById('buscar-oportunidad');
    const checkboxesCategoria = document.querySelectorAll('.filtro-check-categoria');
    const radioDistrito = document.querySelectorAll('.filtro-radio-distrito');
    const btnLimpiarFiltros = document.getElementById('btn-limpiar-filtros');

    if (!contenedorGrid) return;

    const oportunidades = obtenerOportunidades();

    function renderizar() {
        const textoBusqueda = inputBusqueda ? inputBusqueda.value.toLowerCase().trim() : '';
        
        // Obtener categorías seleccionadas
        const categoriasSeleccionadas = [];
        checkboxesCategoria.forEach(cb => {
            if (cb.checked) categoriasSeleccionadas.push(cb.value);
        });

        // Obtener distrito seleccionado
        let distritoSeleccionado = 'todos';
        radioDistrito.forEach(r => {
            if (r.checked) distritoSeleccionado = r.value;
        });

        const filtradas = oportunidades.filter(op => {
            // Filtro por texto (título, ong o descripción)
            const coincideTexto = !textoBusqueda || 
                op.titulo.toLowerCase().includes(textoBusqueda) ||
                op.ong.toLowerCase().includes(textoBusqueda) ||
                op.descripcion.toLowerCase().includes(textoBusqueda);

            // Filtro por categoría
            const coincideCategoria = categoriasSeleccionadas.length === 0 || 
                categoriasSeleccionadas.includes(op.categoria);

            // Filtro por distrito
            const coincideDistrito = distritoSeleccionado === 'todos' || 
                op.distrito === distritoSeleccionado;

            return coincideTexto && coincideCategoria && coincideDistrito;
        });

        if (filtradas.length === 0) {
            contenedorGrid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: #fff; border-radius: 8px;">
                    <i class="bi bi-search" style="font-size: 2.5rem; color: #9E9E9E;"></i>
                    <h3 style="margin-top: 1rem; color: #424242;">No se encontraron oportunidades</h3>
                    <p style="color: #757575;">Prueba cambiando los filtros o el término de búsqueda para ver más causas en Cusco.</p>
                </div>
            `;
        } else {
            contenedorGrid.innerHTML = filtradas.map(crearMarcadoTarjeta).join('');
        }

        if (contadorResultados) {
            contadorResultados.textContent = `${filtradas.length} oportunidades activas`;
        }
    }

    // Escuchadores de eventos para interactividad
    if (inputBusqueda) inputBusqueda.addEventListener('input', renderizar);
    checkboxesCategoria.forEach(cb => cb.addEventListener('change', renderizar));
    radioDistrito.forEach(r => r.addEventListener('change', renderizar));

    if (btnLimpiarFiltros) {
        btnLimpiarFiltros.addEventListener('click', (e) => {
            e.preventDefault();
            if (inputBusqueda) inputBusqueda.value = '';
            checkboxesCategoria.forEach(cb => cb.checked = false);
            const radioTodos = document.querySelector('input[name="distrito"][value="todos"]');
            if (radioTodos) radioTodos.checked = true;
            renderizar();
        });
    }

    renderizar();
}

/**
 * Configura la sección de oportunidades destacadas en la Landing Page (index.html).
 */
function configurarDestacadasIndex() {
    const contenedor = document.getElementById('grid-destacadas-index');
    if (!contenedor) return;

    const oportunidades = obtenerOportunidades();
    const destacadas = oportunidades.filter(op => op.destacada).slice(0, 3);
    contenedor.innerHTML = destacadas.map(crearMarcadoTarjeta).join('');
}

/**
 * Carga la información de la oportunidad seleccionada en la vista de detalle.
 */
function configurarVistaDetalle() {
    const detalleContenedor = document.getElementById('contenedor-detalle-oportunidad');
    if (!detalleContenedor) return;

    const urlParams = new URLSearchParams(window.location.search);
    const oportunidadId = urlParams.get('id') || 'op-001';

    const oportunidades = obtenerOportunidades();
    const oportunidad = oportunidades.find(op => op.id === oportunidadId) || oportunidades[0];

    // Llenar campos de la interfaz
    document.getElementById('detalle-titulo').textContent = oportunidad.titulo;
    document.getElementById('detalle-ong').textContent = oportunidad.ong;
    document.getElementById('detalle-distrito').textContent = `${oportunidad.distrito}, Cusco`;
    document.getElementById('detalle-categoria').textContent = oportunidad.categoria;
    document.getElementById('detalle-categoria').className = `badge-categoria ${oportunidad.badgeClase}`;
    document.getElementById('detalle-descripcion').textContent = oportunidad.descripcion;
    document.getElementById('detalle-horario').textContent = oportunidad.horario;
    document.getElementById('detalle-modalidad').textContent = oportunidad.modalidad;
    document.getElementById('detalle-cupos').textContent = `${oportunidad.cuposRestantes} cupos de ${oportunidad.cupos} totales`;

    const imgElement = document.getElementById('detalle-imagen');
    if (imgElement) imgElement.src = oportunidad.imagen;

    // Lista de habilidades
    const ulHabilidades = document.getElementById('detalle-habilidades-lista');
    if (ulHabilidades) {
        ulHabilidades.innerHTML = oportunidad.habilidades
            .map(h => `<li class="item-habilidad"><i class="bi bi-check-circle-fill" style="color: var(--color-primary); margin-right: 4px;"></i>${h}</li>`)
            .join('');
    }

    // Configurar modal de postulación
    const btnAbrirModal = document.getElementById('btn-abrir-postulacion');
    const modalPostulacion = document.getElementById('modal-postulacion');
    const btnCerrarModal = document.getElementById('btn-cerrar-modal');
    const formPostular = document.getElementById('form-postulacion-rapida');

    if (btnAbrirModal && modalPostulacion) {
        btnAbrirModal.addEventListener('click', () => {
            modalPostulacion.classList.add('activo');
        });
    }

    if (btnCerrarModal && modalPostulacion) {
        btnCerrarModal.addEventListener('click', () => {
            modalPostulacion.classList.remove('activo');
        });
    }

    // Cerrar al hacer click fuera
    if (modalPostulacion) {
        modalPostulacion.addEventListener('click', (e) => {
            if (e.target === modalPostulacion) {
                modalPostulacion.classList.remove('activo');
            }
        });
    }

    // Confirmación de postulación
    if (formPostular) {
        formPostular.addEventListener('submit', (e) => {
            e.preventDefault();
            const inputNombre = document.getElementById('postulante-nombre');
            const inputCorreo = document.getElementById('postulante-correo');
            const nombre = inputNombre ? inputNombre.value.trim() : 'Estudiante';
            const correo = inputCorreo ? inputCorreo.value.trim() : '';

            // Guardar postulación en localStorage
            try {
                let postulaciones = JSON.parse(localStorage.getItem(STORAGE_KEYS.POSTULACIONES) || '[]');
                postulaciones.push({
                    id: 'post-' + Date.now(),
                    oportunidadId: oportunidad.id,
                    titulo: oportunidad.titulo,
                    ong: oportunidad.ong,
                    fecha: new Date().toISOString().split('T')[0],
                    estado: 'Pendiente',
                    estudiante: nombre,
                    correo: correo,
                    horasAcumuladas: 0
                });
                localStorage.setItem(STORAGE_KEYS.POSTULACIONES, JSON.stringify(postulaciones));
            } catch (err) {
                console.error("Error al registrar postulación:", err);
            }

            modalPostulacion.classList.remove('activo');
            alert(`¡Felicitaciones ${nombre}!\nTu postulación a "${oportunidad.titulo}" con ${oportunidad.ong} fue enviada con éxito.\nPuedes seguir su estado en tu Panel de Voluntario.`);
            window.location.href = 'dashboard-voluntario.html';
        });
    }
}

/**
 * Control del menú de navegación móvil (Menú Hamburguesa).
 */
function configurarMenuMovil() {
    const btnHamburguesa = document.querySelector('.boton-menu-movil');
    const navMenu = document.querySelector('.nav-principal');

    if (btnHamburguesa && navMenu) {
        btnHamburguesa.addEventListener('click', () => {
            navMenu.classList.toggle('activo');
            const icono = btnHamburguesa.querySelector('i');
            if (icono) {
                if (navMenu.classList.contains('activo')) {
                    icono.classList.replace('bi-list', 'bi-x-lg');
                } else {
                    icono.classList.replace('bi-x-lg', 'bi-list');
                }
            }
        });
    }
}

// INICIALIZACIÓN GENERAL CUANDO EL DOM ESTÁ LISTO
document.addEventListener('DOMContentLoaded', () => {
    inicializarDatosStorage();
    configurarMenuMovil();
    configurarDestacadasIndex();
    configurarCatalogo();
    configurarVistaDetalle();
});
