/**
 * ==============================================================================
 * VoluntMatch Cusco - Script Principal (main.js)
 * Asignatura: Programación Web | Universidad Continental (Sede Cusco)
 * Autores: Piero Alcca, Luis Dueñas, Patrick Leguia, Alexis Rojas
 * ==============================================================================
 * Control de navegación, renderizado dinámico del catálogo,
 * filtros reactivos, modal de postulación y persistencia con localStorage.
 */

// Claves del almacenamiento local (localStorage)
const CLAVES_ALMACENAMIENTO = {
    OPORTUNIDADES: 'voluntmatch_oportunidades',
    POSTULACIONES: 'voluntmatch_postulaciones',
    USUARIO_ACTUAL: 'voluntmatch_usuario_actual'
};

// Alias para compatibilidad
const STORAGE_KEYS = CLAVES_ALMACENAMIENTO;

// Catálogo inicial de voluntariados en Cusco
const OPORTUNIDADES_INICIALES = [
    {
        id: "op-001",
        titulo: "Tutorías y Reforzamiento Escolar",
        ong: "Hogar Infantil Azul Wasi",
        distrito: "San Jerónimo",
        categoria: "Educación",
        claseInsignia: "badge-educacion",
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
        claseInsignia: "badge-sistemas",
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
        claseInsignia: "badge-ambiente",
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
        claseInsignia: "badge-logistica",
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
        claseInsignia: "badge-educacion",
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
        claseInsignia: "badge-sistemas",
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

// Alias para compatibilidad
const OPORTUNIDADES_CUSCO_INICIALES = OPORTUNIDADES_INICIALES;

/**
 * Guarda datos iniciales en localStorage si es la primera vez que se abre la web.
 */
function inicializarDatos() {
    try {
        if (!localStorage.getItem(CLAVES_ALMACENAMIENTO.OPORTUNIDADES)) {
            localStorage.setItem(CLAVES_ALMACENAMIENTO.OPORTUNIDADES, JSON.stringify(OPORTUNIDADES_INICIALES));
        }
        if (!localStorage.getItem(CLAVES_ALMACENAMIENTO.POSTULACIONES)) {
            const postulacionEjemplo = [
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
            localStorage.setItem(CLAVES_ALMACENAMIENTO.POSTULACIONES, JSON.stringify(postulacionEjemplo));
        }
    } catch (error) {
        console.warn("No se pudo acceder al almacenamiento local:", error);
    }
}

/**
 * Obtiene las oportunidades guardadas.
 * @returns {Array<Object>} Lista de oportunidades
 */
function obtenerOportunidades() {
    try {
        const guardadas = localStorage.getItem(CLAVES_ALMACENAMIENTO.OPORTUNIDADES);
        return guardadas ? JSON.parse(guardadas) : OPORTUNIDADES_INICIALES;
    } catch (error) {
        return OPORTUNIDADES_INICIALES;
    }
}

/**
 * Genera el código HTML para una tarjeta de oportunidad.
 * @param {Object} oportunidad Datos de la oportunidad
 * @returns {string} Código HTML
 */
function crearTarjetaHtml(oportunidad) {
    const clasePocosCupos = oportunidad.cuposRestantes <= 2 ? 'poco' : '';
    const claseInsignia = oportunidad.claseInsignia || oportunidad.badgeClase || 'badge-educacion';

    return `
        <article class="tarjeta-oportunidad" data-id="${oportunidad.id}">
            <div class="tarjeta-imagen-wrapper">
                <span class="badge-categoria ${claseInsignia}">${oportunidad.categoria}</span>
                <img src="${oportunidad.imagen}" alt="${oportunidad.titulo}" loading="lazy">
            </div>
            <div class="tarjeta-cuerpo">
                <h3>${oportunidad.titulo}</h3>
                <div class="tarjeta-ong">
                    <i class="bi bi-building"></i> ${oportunidad.ong}
                </div>
                <p class="tarjeta-descripcion">${oportunidad.descripcion}</p>
                <div class="tarjeta-meta">
                    <div class="tarjeta-meta-item">
                        <i class="bi bi-geo-alt-fill"></i> ${oportunidad.distrito}, Cusco
                    </div>
                    <div class="tarjeta-meta-item">
                        <i class="bi bi-clock-fill"></i> ${oportunidad.horario}
                    </div>
                </div>
            </div>
            <div class="tarjeta-footer">
                <span class="cupos-texto ${clasePocosCupos}">
                    <i class="bi bi-people-fill"></i> ${oportunidad.cuposRestantes} cupos libres
                </span>
                <a href="detalle-oportunidad.html?id=${oportunidad.id}" class="btn-custom btn-primary-custom" style="padding: 0.4rem 0.9rem; font-size: 0.85rem;">
                    Ver Detalle
                </a>
            </div>
        </article>
    `;
}

/**
 * Configura los filtros y el catálogo en oportunidades.html.
 */
function configurarCatalogo() {
    const contenedor = document.getElementById('grid-catalogo');
    const contador = document.getElementById('contador-resultados');
    const campoBusqueda = document.getElementById('buscar-oportunidad');
    const casillasCategoria = document.querySelectorAll('.filtro-check-categoria');
    const radiosDistrito = document.querySelectorAll('.filtro-radio-distrito');
    const botonLimpiar = document.getElementById('btn-limpiar-filtros');

    if (!contenedor) return;

    const listaOportunidades = obtenerOportunidades();

    function filtrarYRenderizar() {
        const texto = campoBusqueda ? campoBusqueda.value.toLowerCase().trim() : '';

        // Categorías seleccionadas
        const categoriasSeleccionadas = [];
        casillasCategoria.forEach(casilla => {
            if (casilla.checked) categoriasSeleccionadas.push(casilla.value);
        });

        // Distrito seleccionado
        let distritoSeleccionado = 'todos';
        radiosDistrito.forEach(radio => {
            if (radio.checked) distritoSeleccionado = radio.value;
        });

        // Filtrado simple
        const filtradas = listaOportunidades.filter(item => {
            const coincideTexto = !texto ||
                item.titulo.toLowerCase().includes(texto) ||
                item.ong.toLowerCase().includes(texto) ||
                item.descripcion.toLowerCase().includes(texto);

            const coincideCategoria = categoriasSeleccionadas.length === 0 ||
                categoriasSeleccionadas.includes(item.categoria);

            const coincideDistrito = distritoSeleccionado === 'todos' ||
                item.distrito === distritoSeleccionado;

            return coincideTexto && coincideCategoria && coincideDistrito;
        });

        // Renderizado
        if (filtradas.length === 0) {
            contenedor.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: #fff; border-radius: 8px;">
                    <i class="bi bi-search" style="font-size: 2.5rem; color: #9E9E9E;"></i>
                    <h3 style="margin-top: 1rem; color: #424242;">No se encontraron oportunidades</h3>
                    <p style="color: #757575;">Prueba cambiando los filtros o el término de búsqueda.</p>
                </div>
            `;
        } else {
            contenedor.innerHTML = filtradas.map(crearTarjetaHtml).join('');
        }

        if (contador) {
            contador.textContent = `${filtradas.length} oportunidades activas`;
        }
    }

    if (campoBusqueda) campoBusqueda.addEventListener('input', filtrarYRenderizar);
    casillasCategoria.forEach(casilla => casilla.addEventListener('change', filtrarYRenderizar));
    radiosDistrito.forEach(radio => radio.addEventListener('change', filtrarYRenderizar));

    if (botonLimpiar) {
        botonLimpiar.addEventListener('click', (evento) => {
            evento.preventDefault();
            if (campoBusqueda) campoBusqueda.value = '';
            casillasCategoria.forEach(casilla => casilla.checked = false);
            const radioTodos = document.querySelector('input[name="distrito"][value="todos"]');
            if (radioTodos) radioTodos.checked = true;
            filtrarYRenderizar();
        });
    }

    filtrarYRenderizar();
}

/**
 * Muestra las oportunidades destacadas en la página de inicio (index.html).
 */
function configurarDestacadasInicio() {
    const contenedor = document.getElementById('grid-destacadas-index');
    if (!contenedor) return;

    const lista = obtenerOportunidades();
    const destacadas = lista.filter(item => item.destacada).slice(0, 3);
    contenedor.innerHTML = destacadas.map(crearTarjetaHtml).join('');
}

/**
 * Carga los datos de la oportunidad en detalle-oportunidad.html.
 */
function configurarDetalleOportunidad() {
    const contenedor = document.getElementById('contenedor-detalle-oportunidad');
    if (!contenedor) return;

    const parametros = new URLSearchParams(window.location.search);
    const identificador = parametros.get('id') || 'op-001';

    const lista = obtenerOportunidades();
    const seleccionada = lista.find(item => item.id === identificador) || lista[0];

    // Asignar textos
    document.getElementById('detalle-titulo').textContent = seleccionada.titulo;
    document.getElementById('detalle-ong').textContent = seleccionada.ong;
    document.getElementById('detalle-distrito').textContent = `${seleccionada.distrito}, Cusco`;

    const elementoCategoria = document.getElementById('detalle-categoria');
    elementoCategoria.textContent = seleccionada.categoria;
    elementoCategoria.className = `badge-categoria ${seleccionada.claseInsignia || seleccionada.badgeClase}`;

    document.getElementById('detalle-descripcion').textContent = seleccionada.descripcion;
    document.getElementById('detalle-horario').textContent = seleccionada.horario;
    document.getElementById('detalle-modalidad').textContent = seleccionada.modalidad;
    document.getElementById('detalle-cupos').textContent = `${seleccionada.cuposRestantes} cupos de ${seleccionada.cupos} totales`;

    const imagen = document.getElementById('detalle-imagen');
    if (imagen) imagen.src = seleccionada.imagen;

    // Habilidades requeridas
    const listaHabilidades = document.getElementById('detalle-habilidades-lista');
    if (listaHabilidades) {
        listaHabilidades.innerHTML = seleccionada.habilidades
            .map(habilidad => `<li class="item-habilidad"><i class="bi bi-check-circle-fill" style="color: var(--color-primario, #2E7D32); margin-right: 4px;"></i>${habilidad}</li>`)
            .join('');
    }

    // Modal de postulación
    const botonAbrir = document.getElementById('btn-abrir-postulacion');
    const modal = document.getElementById('modal-postulacion');
    const botonCerrar = document.getElementById('btn-cerrar-modal');
    const formulario = document.getElementById('form-postulacion-rapida');

    if (botonAbrir && modal) {
        botonAbrir.addEventListener('click', () => modal.classList.add('activo'));
    }
    if (botonCerrar && modal) {
        botonCerrar.addEventListener('click', () => modal.classList.remove('activo'));
    }
    if (modal) {
        modal.addEventListener('click', (evento) => {
            if (evento.target === modal) modal.classList.remove('activo');
        });
    }

    if (formulario) {
        formulario.addEventListener('submit', (evento) => {
            evento.preventDefault();
            const campoNombre = document.getElementById('postulante-nombre');
            const campoCorreo = document.getElementById('postulante-correo');
            const nombre = campoNombre ? campoNombre.value.trim() : 'Estudiante';
            const correo = campoCorreo ? campoCorreo.value.trim() : '';

            // Guardar postulación
            try {
                let postulaciones = JSON.parse(localStorage.getItem(CLAVES_ALMACENAMIENTO.POSTULACIONES) || '[]');
                postulaciones.push({
                    id: 'post-' + Date.now(),
                    oportunidadId: seleccionada.id,
                    titulo: seleccionada.titulo,
                    ong: seleccionada.ong,
                    fecha: new Date().toISOString().split('T')[0],
                    estado: 'Pendiente',
                    estudiante: nombre,
                    correo: correo,
                    horasAcumuladas: 0
                });
                localStorage.setItem(CLAVES_ALMACENAMIENTO.POSTULACIONES, JSON.stringify(postulaciones));
            } catch (error) {
                console.error("Error al registrar postulación:", error);
            }

            modal.classList.remove('activo');
            alert(`¡Felicitaciones ${nombre}!\nTu postulación a "${seleccionada.titulo}" fue registrada exitosamente.`);
            window.location.href = 'dashboard-voluntario.html';
        });
    }
}

/**
 * Control del menú de navegación para dispositivos móviles.
 */
function configurarMenuMovil() {
    const botonMenu = document.querySelector('.boton-menu-movil');
    const menuNavegacion = document.querySelector('.nav-principal');

    if (botonMenu && menuNavegacion) {
        botonMenu.addEventListener('click', () => {
            menuNavegacion.classList.toggle('activo');
            const icono = botonMenu.querySelector('i');
            if (icono) {
                if (menuNavegacion.classList.contains('activo')) {
                    icono.classList.replace('bi-list', 'bi-x-lg');
                } else {
                    icono.classList.replace('bi-x-lg', 'bi-list');
                }
            }
        });
    }
}

// Inicialización general al cargar el DOM
document.addEventListener('DOMContentLoaded', () => {
    inicializarDatos();
    configurarMenuMovil();
    configurarDestacadasInicio();
    configurarCatalogo();
    configurarDetalleOportunidad();
});
