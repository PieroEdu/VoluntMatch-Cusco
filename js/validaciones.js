/**
 * ==============================================================================
 * VoluntMatch Cusco - Módulo de Validaciones (validaciones.js)
 * Asignatura: Programación Web | Universidad Continental (Sede Cusco)
 * Autores: Piero Alcca, Luis Dueñas, Patrick Leguia, Alexis Rojas
 * ==============================================================================
 * Validaciones en el cliente para formularios de registro, login y contacto.
 */

// Expresiones regulares para comprobaciones
const PATRONES = {
    CORREO: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    TELEFONO: /^9\d{8}$/,                                       // 9 dígitos comenzando en 9
    DNI: /^\d{8}$/,                                             // 8 dígitos numéricos
    RUC: /^(10|20)\d{9}$/,                                      // 11 dígitos que inician con 10 o 20
    CONTRASENA: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/        // Mínimo 8 caracteres (mayúscula, minúscula y número)
};

// Alias de compatibilidad
const PATRONES_VALIDACION = {
    CORREO_GENERAL: PATRONES.CORREO,
    CORREO_UNIVERSITARIO: /^[a-zA-Z0-9._%+-]+@continental\.edu\.pe$/,
    TELEFONO_PERU: PATRONES.TELEFONO,
    DNI_PERU: PATRONES.DNI,
    RUC_PERU: PATRONES.RUC,
    PASSWORD_SEGURO: PATRONES.CONTRASENA
};

/**
 * Muestra un mensaje de error debajo de un campo.
 * @param {HTMLElement} campo Elemento del formulario
 * @param {string} mensaje Texto del error
 */
function mostrarError(campo, mensaje) {
    const contenedor = campo.closest('.form-campo');
    if (!contenedor) return;

    contenedor.classList.add('error');
    let elementoError = contenedor.querySelector('.form-mensaje-error');
    if (!elementoError) {
        elementoError = document.createElement('span');
        elementoError.className = 'form-mensaje-error';
        contenedor.appendChild(elementoError);
    }
    elementoError.textContent = mensaje;
}

/**
 * Limpia el estado de error de un campo.
 * @param {HTMLElement} campo Elemento del formulario
 */
function limpiarError(campo) {
    const contenedor = campo.closest('.form-campo');
    if (!contenedor) return;

    contenedor.classList.remove('error');
    const elementoError = contenedor.querySelector('.form-mensaje-error');
    if (elementoError) {
        elementoError.textContent = '';
    }
}

/**
 * Limpia los errores automáticamente mientras el usuario escribe o cambia el valor.
 * @param {HTMLFormElement} formulario Formulario a observar
 */
function activarLimpiezaErrores(formulario) {
    if (!formulario) return;
    const campos = formulario.querySelectorAll('input, select, textarea');
    campos.forEach(campo => {
        campo.addEventListener('input', () => limpiarError(campo));
        campo.addEventListener('change', () => limpiarError(campo));
    });
}

/**
 * Validación del Formulario de Registro de Voluntarios
 */
function validarRegistroVoluntario() {
    const formulario = document.getElementById('form-registro-voluntario');
    if (!formulario) return;

    activarLimpiezaErrores(formulario);

    formulario.addEventListener('submit', (evento) => {
        evento.preventDefault();
        let esValido = true;

        const nombre = document.getElementById('reg-nombre');
        const dni = document.getElementById('reg-dni');
        const correo = document.getElementById('reg-correo');
        const telefono = document.getElementById('reg-telefono');
        const carrera = document.getElementById('reg-carrera');
        const contrasena = document.getElementById('reg-password');
        const terminos = document.getElementById('reg-terminos');

        if (!nombre.value.trim() || nombre.value.trim().length < 3) {
            mostrarError(nombre, 'Ingresa tu nombre completo (mínimo 3 caracteres).');
            esValido = false;
        }

        if (!PATRONES.DNI.test(dni.value.trim())) {
            mostrarError(dni, 'El DNI debe tener 8 dígitos numéricos.');
            esValido = false;
        }

        if (!PATRONES.CORREO.test(correo.value.trim())) {
            mostrarError(correo, 'Ingresa un correo electrónico válido.');
            esValido = false;
        }

        if (!PATRONES.TELEFONO.test(telefono.value.trim())) {
            mostrarError(telefono, 'Ingresa un celular válido de 9 dígitos que inicie con 9.');
            esValido = false;
        }

        if (!carrera.value) {
            mostrarError(carrera, 'Por favor selecciona tu carrera.');
            esValido = false;
        }

        if (!PATRONES.CONTRASENA.test(contrasena.value)) {
            mostrarError(contrasena, 'Debe tener al menos 8 caracteres, incluyendo mayúscula, minúscula y número.');
            esValido = false;
        }

        if (terminos && !terminos.checked) {
            alert('Debes aceptar las políticas de privacidad para registrarte.');
            esValido = false;
        }

        if (esValido) {
            const nuevoUsuario = {
                nombre: nombre.value.trim(),
                correo: correo.value.trim(),
                carrera: carrera.value,
                rol: 'Voluntario'
            };
            const almacenamientoClave = (typeof CLAVES_ALMACENAMIENTO !== 'undefined') ? CLAVES_ALMACENAMIENTO.USUARIO_ACTUAL : 'voluntmatch_usuario_actual';
            localStorage.setItem(almacenamientoClave, JSON.stringify(nuevoUsuario));

            alert(`¡Registro exitoso!\nBienvenido a VoluntMatch Cusco, ${nombre.value.trim()}.`);
            window.location.href = 'dashboard-voluntario.html';
        }
    });
}

/**
 * Validación del Formulario de Registro de Organizaciones (ONG)
 */
function validarRegistroOrganizacion() {
    const formulario = document.getElementById('form-registro-ong');
    if (!formulario) return;

    activarLimpiezaErrores(formulario);

    formulario.addEventListener('submit', (evento) => {
        evento.preventDefault();
        let esValido = true;

        const razonSocial = document.getElementById('ong-razon');
        const ruc = document.getElementById('ong-ruc');
        const distrito = document.getElementById('ong-distrito');
        const correo = document.getElementById('ong-correo');
        const telefono = document.getElementById('ong-telefono');
        const mision = document.getElementById('ong-mision');
        const contrasena = document.getElementById('ong-password');

        if (!razonSocial.value.trim()) {
            mostrarError(razonSocial, 'Ingresa la razón social o nombre de la organización.');
            esValido = false;
        }

        if (!PATRONES.RUC.test(ruc.value.trim())) {
            mostrarError(ruc, 'El RUC debe tener 11 dígitos y empezar con 10 o 20.');
            esValido = false;
        }

        if (!distrito.value) {
            mostrarError(distrito, 'Selecciona el distrito en Cusco.');
            esValido = false;
        }

        if (!PATRONES.CORREO.test(correo.value.trim())) {
            mostrarError(correo, 'Ingresa un correo institucional válido.');
            esValido = false;
        }

        if (!PATRONES.TELEFONO.test(telefono.value.trim())) {
            mostrarError(telefono, 'Ingresa un teléfono válido de 9 dígitos.');
            esValido = false;
        }

        if (!mision.value.trim() || mision.value.trim().length < 20) {
            mostrarError(mision, 'Describe la misión social de la organización (mínimo 20 caracteres).');
            esValido = false;
        }

        if (!contrasena.value || contrasena.value.length < 6) {
            mostrarError(contrasena, 'La contraseña debe tener al menos 6 caracteres.');
            esValido = false;
        }

        if (esValido) {
            const nuevaOng = {
                nombre: razonSocial.value.trim(),
                ruc: ruc.value.trim(),
                distrito: distrito.value,
                rol: 'ONG'
            };
            const almacenamientoClave = (typeof CLAVES_ALMACENAMIENTO !== 'undefined') ? CLAVES_ALMACENAMIENTO.USUARIO_ACTUAL : 'voluntmatch_usuario_actual';
            localStorage.setItem(almacenamientoClave, JSON.stringify(nuevaOng));

            alert(`¡Registro de Organización completado!\nTu cuenta para "${razonSocial.value.trim()}" ha sido creada.`);
            window.location.href = 'dashboard-organizacion.html';
        }
    });
}

/**
 * Validación del Formulario de Inicio de Sesión (Login)
 */
function validarInicioSesion() {
    const formulario = document.getElementById('form-login');
    if (!formulario) return;

    activarLimpiezaErrores(formulario);

    formulario.addEventListener('submit', (evento) => {
        evento.preventDefault();
        let esValido = true;

        const correo = document.getElementById('login-correo');
        const contrasena = document.getElementById('login-password');
        const rolSeleccionado = document.querySelector('input[name="rol"]:checked');

        if (!PATRONES.CORREO.test(correo.value.trim())) {
            mostrarError(correo, 'Ingresa un correo electrónico válido.');
            esValido = false;
        }

        if (!contrasena.value || contrasena.value.length < 6) {
            mostrarError(contrasena, 'Ingresa tu contraseña (mínimo 6 caracteres).');
            esValido = false;
        }

        if (esValido) {
            const rol = rolSeleccionado ? rolSeleccionado.value : 'voluntario';
            const almacenamientoClave = (typeof CLAVES_ALMACENAMIENTO !== 'undefined') ? CLAVES_ALMACENAMIENTO.USUARIO_ACTUAL : 'voluntmatch_usuario_actual';
            localStorage.setItem(almacenamientoClave, JSON.stringify({
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
 * Validación del Formulario de Contacto
 */
function validarContacto() {
    const formulario = document.getElementById('form-contacto');
    if (!formulario) return;

    activarLimpiezaErrores(formulario);

    formulario.addEventListener('submit', (evento) => {
        evento.preventDefault();
        let esValido = true;

        const nombre = document.getElementById('contacto-nombre');
        const correo = document.getElementById('contacto-correo');
        const asunto = document.getElementById('contacto-asunto');
        const mensaje = document.getElementById('contacto-mensaje');

        if (!nombre.value.trim()) {
            mostrarError(nombre, 'Por favor ingresa tu nombre.');
            esValido = false;
        }

        if (!PATRONES.CORREO.test(correo.value.trim())) {
            mostrarError(correo, 'Por favor ingresa un correo válido.');
            esValido = false;
        }

        if (!asunto.value.trim()) {
            mostrarError(asunto, 'Indica el motivo de tu consulta.');
            esValido = false;
        }

        if (!mensaje.value.trim() || mensaje.value.trim().length < 15) {
            mostrarError(mensaje, 'El mensaje debe tener al menos 15 caracteres.');
            esValido = false;
        }

        if (esValido) {
            alert(`¡Mensaje enviado con éxito!\nGracias ${nombre.value.trim()}, responderemos a ${correo.value.trim()} a la brevedad.`);
            formulario.reset();
        }
    });
}

// Inicialización de validaciones según el formulario presente en la página
document.addEventListener('DOMContentLoaded', () => {
    validarRegistroVoluntario();
    validarRegistroOrganizacion();
    validarInicioSesion();
    validarContacto();
});
