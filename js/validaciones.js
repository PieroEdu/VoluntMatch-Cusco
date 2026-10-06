/**
 * ==============================================================================
 * VoluntMatch Cusco - Módulo de Validaciones en el Cliente (validaciones.js)
 * Asignatura: Programación Web | Universidad Continental (Sede Cusco)
 * Autores: Piero Alcca, Luis Dueñas, Patrick Leguia, Alexis Rojas
 * ==============================================================================
 * Manejo de validaciones dinámicas con expresiones regulares (RegEx),
 * control del evento submit y feedback accesible en tiempo real.
 */

/**
 * Patrones de expresiones regulares según normativas y estándares
 */
const PATRONES_VALIDACION = {
    CORREO_GENERAL: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    CORREO_UNIVERSITARIO: /^[a-zA-Z0-9._%+-]+@continental\.edu\.pe$/,
    TELEFONO_PERU: /^9\d{8}$/,           // 9 dígitos empezando con 9
    DNI_PERU: /^\d{8}$/,                // 8 dígitos numéricos
    RUC_PERU: /^(10|20)\d{9}$/,         // 11 dígitos empezando por 10 o 20
    PASSWORD_SEGURO: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/ // Mínimo 8 caracteres, mayúscula, minúscula y número
};

/**
 * Muestra el mensaje de error visual en un campo del formulario.
 * @param {HTMLElement} inputElement Elemento del campo analizado
 * @param {string} mensaje Texto explicativo del error
 */
function mostrarErrorCampo(inputElement, mensaje) {
    const contenedor = inputElement.closest('.form-campo');
    if (!contenedor) return;

    contenedor.classList.add('error');
    let spanError = contenedor.querySelector('.form-mensaje-error');
    if (!spanError) {
        spanError = document.createElement('span');
        spanError.className = 'form-mensaje-error';
        contenedor.appendChild(spanError);
    }
    spanError.textContent = mensaje;
}

/**
 * Limpia el estado de error de un campo.
 * @param {HTMLElement} inputElement Elemento del campo
 */
function limpiarErrorCampo(inputElement) {
    const contenedor = inputElement.closest('.form-campo');
    if (!contenedor) return;

    contenedor.classList.remove('error');
    const spanError = contenedor.querySelector('.form-mensaje-error');
    if (spanError) {
        spanError.textContent = '';
    }
}

/**
 * Asigna validación en tiempo real (evento blur e input) para limpiar errores al escribir.
 * @param {HTMLFormElement} formulario Formulario a monitorear
 */
function inicializarFeedbackEnTiempoReal(formulario) {
    if (!formulario) return;
    const campos = formulario.querySelectorAll('input, select, textarea');
    campos.forEach(campo => {
        campo.addEventListener('input', () => limpiarErrorCampo(campo));
        campo.addEventListener('change', () => limpiarErrorCampo(campo));
    });
}

/**
 * Configuración de validación para el formulario de Registro de Voluntarios
 */
function configurarValidacionVoluntario() {
    const form = document.getElementById('form-registro-voluntario');
    if (!form) return;

    inicializarFeedbackEnTiempoReal(form);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let esValido = true;

        const nombre = document.getElementById('reg-nombre');
        const dni = document.getElementById('reg-dni');
        const correo = document.getElementById('reg-correo');
        const telefono = document.getElementById('reg-telefono');
        const carrera = document.getElementById('reg-carrera');
        const password = document.getElementById('reg-password');
        const terminos = document.getElementById('reg-terminos');

        // Validar Nombre
        if (!nombre.value.trim() || nombre.value.trim().length < 3) {
            mostrarErrorCampo(nombre, 'Ingresa tu nombre completo (mínimo 3 caracteres).');
            esValido = false;
        }

        // Validar DNI
        if (!PATRONES_VALIDACION.DNI_PERU.test(dni.value.trim())) {
            mostrarErrorCampo(dni, 'El DNI debe contener exactamente 8 dígitos numéricos.');
            esValido = false;
        }

        // Validar Correo Institucional
        if (!PATRONES_VALIDACION.CORREO_GENERAL.test(correo.value.trim())) {
            mostrarErrorCampo(correo, 'Ingresa un formato de correo electrónico válido.');
            esValido = false;
        }

        // Validar Teléfono
        if (!PATRONES_VALIDACION.TELEFONO_PERU.test(telefono.value.trim())) {
            mostrarErrorCampo(telefono, 'Ingresa un celular válido de 9 dígitos que inicie con 9.');
            esValido = false;
        }

        // Validar Carrera
        if (!carrera.value) {
            mostrarErrorCampo(carrera, 'Por favor selecciona tu carrera universitaria.');
            esValido = false;
        }

        // Validar Contraseña
        if (!PATRONES_VALIDACION.PASSWORD_SEGURO.test(password.value)) {
            mostrarErrorCampo(password, 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y un número.');
            esValido = false;
        }

        // Validar Términos
        if (terminos && !terminos.checked) {
            alert('Debes aceptar las políticas de privacidad y protección de datos para registrarte.');
            esValido = false;
        }

        if (esValido) {
            // Guardar usuario en localStorage
            const nuevoUsuario = {
                nombre: nombre.value.trim(),
                correo: correo.value.trim(),
                carrera: carrera.value,
                rol: 'Voluntario'
            };
            localStorage.setItem(STORAGE_KEYS.USUARIO_ACTUAL, JSON.stringify(nuevoUsuario));

            alert(`¡Registro exitoso como Estudiante Voluntario!\nBienvenido a VoluntMatch Cusco, ${nombre.value.trim()}.`);
            window.location.href = 'dashboard-voluntario.html';
        }
    });
}

/**
 * Configuración de validación para el formulario de Registro de Organizaciones (ONG)
 */
function configurarValidacionOrganizacion() {
    const form = document.getElementById('form-registro-ong');
    if (!form) return;

    inicializarFeedbackEnTiempoReal(form);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let esValido = true;

        const razonSocial = document.getElementById('ong-razon');
        const ruc = document.getElementById('ong-ruc');
        const distrito = document.getElementById('ong-distrito');
        const correo = document.getElementById('ong-correo');
        const telefono = document.getElementById('ong-telefono');
        const mision = document.getElementById('ong-mision');
        const password = document.getElementById('ong-password');

        if (!razonSocial.value.trim()) {
            mostrarErrorCampo(razonSocial, 'Ingresa el nombre o razón social de la organización.');
            esValido = false;
        }

        if (!PATRONES_VALIDACION.RUC_PERU.test(ruc.value.trim())) {
            mostrarErrorCampo(ruc, 'El RUC debe tener 11 dígitos y empezar con 10 o 20.');
            esValido = false;
        }

        if (!distrito.value) {
            mostrarErrorCampo(distrito, 'Selecciona el distrito de operación en Cusco.');
            esValido = false;
        }

        if (!PATRONES_VALIDACION.CORREO_GENERAL.test(correo.value.trim())) {
            mostrarErrorCampo(correo, 'Ingresa un correo institucional de contacto válido.');
            esValido = false;
        }

        if (!PATRONES_VALIDACION.TELEFONO_PERU.test(telefono.value.trim())) {
            mostrarErrorCampo(telefono, 'Ingresa un teléfono de contacto de 9 dígitos.');
            esValido = false;
        }

        if (!mision.value.trim() || mision.value.trim().length < 20) {
            mostrarErrorCampo(mision, 'Describe brevemente la misión social de la ONG (mínimo 20 caracteres).');
            esValido = false;
        }

        if (!password.value || password.value.length < 6) {
            mostrarErrorCampo(password, 'La contraseña debe tener al menos 6 caracteres.');
            esValido = false;
        }

        if (esValido) {
            const nuevaOng = {
                nombre: razonSocial.value.trim(),
                ruc: ruc.value.trim(),
                distrito: distrito.value,
                rol: 'ONG'
            };
            localStorage.setItem(STORAGE_KEYS.USUARIO_ACTUAL, JSON.stringify(nuevaOng));

            alert(`¡Registro de Organización completado!\nTu cuenta para "${razonSocial.value.trim()}" ha sido creada.`);
            window.location.href = 'dashboard-organizacion.html';
        }
    });
}

/**
 * Configuración de validación para el formulario de Inicio de Sesión (Login)
 */
function configurarValidacionLogin() {
    const form = document.getElementById('form-login');
    if (!form) return;

    inicializarFeedbackEnTiempoReal(form);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let esValido = true;

        const correo = document.getElementById('login-correo');
        const password = document.getElementById('login-password');
        const rolSeleccionado = document.querySelector('input[name="rol"]:checked');

        if (!PATRONES_VALIDACION.CORREO_GENERAL.test(correo.value.trim())) {
            mostrarErrorCampo(correo, 'Ingresa un correo electrónico válido.');
            esValido = false;
        }

        if (!password.value || password.value.length < 6) {
            mostrarErrorCampo(password, 'Ingresa tu contraseña (mínimo 6 caracteres).');
            esValido = false;
        }

        if (esValido) {
            const rol = rolSeleccionado ? rolSeleccionado.value : 'voluntario';
            localStorage.setItem(STORAGE_KEYS.USUARIO_ACTUAL, JSON.stringify({
                correo: correo.value.trim(),
                rol: rol === 'voluntario' ? 'Voluntario' : 'ONG',
                nombre: rol === 'voluntario' ? 'Piero Edu Alcca' : 'Hogar Infantil Azul Wasi'
            }));

            if (rol === 'voluntario') {
                window.location.href = 'dashboard-voluntario.html';
            } else {
                window.location.href = 'dashboard-organizacion.html';
            }
        }
    });
}

/**
 * Configuración de validación para el formulario de Contacto
 */
function configurarValidacionContacto() {
    const form = document.getElementById('form-contacto');
    if (!form) return;

    inicializarFeedbackEnTiempoReal(form);

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let esValido = true;

        const nombre = document.getElementById('contacto-nombre');
        const correo = document.getElementById('contacto-correo');
        const asunto = document.getElementById('contacto-asunto');
        const mensaje = document.getElementById('contacto-mensaje');

        if (!nombre.value.trim()) {
            mostrarErrorCampo(nombre, 'Por favor ingresa tu nombre.');
            esValido = false;
        }

        if (!PATRONES_VALIDACION.CORREO_GENERAL.test(correo.value.trim())) {
            mostrarErrorCampo(correo, 'Por favor ingresa un correo válido.');
            esValido = false;
        }

        if (!asunto.value.trim()) {
            mostrarErrorCampo(asunto, 'Indica el motivo de tu consulta.');
            esValido = false;
        }

        if (!mensaje.value.trim() || mensaje.value.trim().length < 15) {
            mostrarErrorCampo(mensaje, 'El mensaje debe tener al menos 15 caracteres.');
            esValido = false;
        }

        if (esValido) {
            alert(`¡Mensaje enviado con éxito!\nGracias ${nombre.value.trim()}, el equipo de VoluntMatch Cusco responderá a ${correo.value.trim()} a la brevedad.`);
            form.reset();
        }
    });
}

// Inicialización de escuchadores según el formulario presente en la vista actual
document.addEventListener('DOMContentLoaded', () => {
    configurarValidacionVoluntario();
    configurarValidacionOrganizacion();
    configurarValidacionLogin();
    configurarValidacionContacto();
});
