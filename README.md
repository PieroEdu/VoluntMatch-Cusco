# VoluntMatch Cusco 🤝🇵🇪

> **Plataforma Web de Match de Voluntariado Local**  
> *Conexión Inteligente entre Voluntarios y Organizaciones Sociales en Cusco*

[![Universidad](https://img.shields.io/badge/Universidad-Continental-red.svg)](https://ucontinental.edu.pe/)
[![Curso](https://img.shields.io/badge/Curso-Programaci%C3%B3n_Web-blue.svg)]()
[![Semana](https://img.shields.io/badge/Evaluaci%C3%B3n-Semana_08_(50%25)-green.svg)]()
[![Licencia](https://img.shields.io/badge/Licencia-MIT-brightgreen.svg)](LICENSE)

---

## 📌 Descripción del Proyecto

**VoluntMatch Cusco** es una solución tecnológica orientada al desarrollo e impacto social en la región de Cusco. La plataforma aborda la fragmentación existente entre la oferta de voluntariado universitario y las necesidades operativas de las Organizaciones No Gubernamentales (ONG), comedores y albergues locales.

A través de un mecanismo de emparejamiento inteligente (*match*) basado en disponibilidad horaria, ubicación distrital (Cusco Centro, San Jerónimo, Wanchaq, Santiago) y habilidades específicas (Educación, Sistemas, Medio Ambiente, Logística), el sistema optimiza la vinculación comunitaria y reduce la tasa de deserción en programas sociales.

---

## 👥 Equipo de Desarrollo - Grupo 6

| Integrante | Rol en el Proyecto | Aporte |
| :--- | :--- | :---: |
| **Alcca Moron, Piero Edu** | Líder Técnico & Programación Frontend / JavaScript | 100% |
| **Dueñas Quintana, Luis Angel** | Arquitecto de Software & Modelado de Datos (MVC / ER) | 100% |
| **Leguia Choquemamani, Patrick Antonio** | Diseñador UI/UX & Maquetador CSS (Responsive / Grid) | 100% |
| **Rojas Lopez, Alexis Sebastian** | Documentador Técnico, QA & Control de Versiones | 100% |

- **Docente:** Alfredo Collantes Mendoza  
- **Institución:** Universidad Continental - Sede Cusco  
- **Año:** 2026  

---

## 🚀 Tecnologías Utilizadas

- **HTML5:** Marcado semántico y accesible (etiquetas `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`).
- **CSS3:** Sistema de diseño responsivo mediante **CSS Grid**, **Flexbox**, variables personalizadas (`:root`) y *Media Queries*.
- **Bootstrap 5.3 & Bootstrap Icons:** Componentes auxiliares de interfaz y conjunto de iconografía ligera vía CDN.
- **JavaScript (ES6+ Vanilla):** Lógica reactiva en el cliente, manipulación del DOM, validaciones con expresiones regulares (RegEx) y persistencia temporal con `localStorage`.

---

## 📁 Estructura del Repositorio

```text
VoluntMatch-Cusco/
├── assets/
│   ├── icons/                  # Iconos SVG y recursos vectoriales
│   └── img/                    # Imágenes optimizadas de iniciativas y organizaciones
├── css/
│   └── styles.css              # Hoja de estilos centralizada (Grid, Flexbox, Responsive)
├── js/
│   ├── main.js                 # Control de navegación global, menús móviles y modales
│   └── validaciones.js         # Lógica de validación de formularios y filtros reactivos
├── contacto.html               # Vista y formulario de contacto institucional
├── dashboard-organizacion.html # Panel de control de ONG (gestión de postulantes)
├── dashboard-voluntario.html   # Panel de control del estudiante (seguimiento de postulaciones)
├── detalle-oportunidad.html    # Ficha técnica ampliada del voluntariado seleccionado
├── index.html                  # Portada y Landing Page principal
├── login.html                  # Formulario de inicio de sesión por rol
├── oportunidades.html          # Catálogo interactivo de iniciativas con filtros dinámicos
├── registro-organizacion.html  # Formulario de registro de ONG (validación de RUC)
├── registro-voluntario.html    # Formulario de registro de voluntarios universitarios
├── .gitignore                  # Exclusión de archivos innecesarios
└── README.md                   # Documentación técnica del proyecto
```

---

## 💻 Instalación y Ejecución Local

No requiere de instalación de paquetes de Node ni configuraciones complejas en esta etapa:

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/PieroEdu/VoluntMatch-Cusco.git
   ```
2. **Acceder al directorio:**
   ```bash
   cd VoluntMatch-Cusco
   ```
3. **Ejecutar en el navegador:**
   - **Opción recomendada:** Abrir el proyecto en **Visual Studio Code**, hacer clic derecho sobre `index.html` y seleccionar **"Open with Live Server"**.
   - **Opción directa:** Hacer doble clic directamente sobre `index.html` en el explorador de archivos.

---

## 🧪 Pruebas de Funcionalidad

1. **Catálogo de Convocatorias (`oportunidades.html`):**
   - Filtrar en tiempo real por áreas (*Educación*, *Sistemas*, *Medio Ambiente*) o por distrito de Cusco (*San Jerónimo*, *Wanchaq*).
2. **Validaciones en Formularios (`registro-voluntario.html`, `registro-organizacion.html`):**
   - Validación sintáctica de correo institucional universitario (`@continental.edu.pe`).
   - Verificación de longitud mínima en contraseñas (mínimo 8 caracteres).
   - Verificación de 11 dígitos numéricos en el campo de RUC para organizaciones.
3. **Simulación de Postulación y CRUD Local:**
   - Al postularse a una oportunidad, se almacena en `localStorage` y se refleja inmediatamente en `dashboard-voluntario.html`.

---

## 📄 Licencia

Este proyecto se encuentra bajo la [Licencia MIT](LICENSE) - código libre para propósitos académicos y comunitarios.
