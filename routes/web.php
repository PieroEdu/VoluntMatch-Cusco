<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OportunidadController;
use App\Http\Controllers\ChatbotController;

// 1. RUTAS PÚBLICAS
Route::get('/', fn() => view('index'))->name('index');

// APIs DE INTELIGENCIA ARTIFICIAL
Route::post('/chatbot/preguntar', [ChatbotController::class, 'preguntar'])->name('chatbot.preguntar');
Route::post('/ia/mejorar-descripcion', [ChatbotController::class, 'mejorarDescripcion'])->name('ia.mejorar-descripcion');
Route::post('/ia/recomendar-campanas', [ChatbotController::class, 'recomendarCampanas'])->name('ia.recomendar-campanas');
Route::post('/ia/admin-copiloto', [ChatbotController::class, 'adminCopiloto'])->name('ia.admin-copiloto');
Route::post('/ia/admin-sugerir-respuesta', [ChatbotController::class, 'adminSugerirRespuesta'])->name('ia.admin-sugerir-respuesta');

Route::get('/oportunidades', [OportunidadController::class, 'index'])->name('oportunidades');
Route::get('/detalle-oportunidad/{id?}', [OportunidadController::class, 'detalle'])->name('detalle-oportunidad');
Route::get('/verificar/{codigo}', [OportunidadController::class, 'verificarPublico'])->name('certificado.verificar');
Route::get('/donacion/voucher/{codigo}', [OportunidadController::class, 'verVoucherDonacion'])->name('donacion.voucher');

Route::get('/contacto', fn() => view('contacto'))->name('contacto');
Route::post('/contacto/enviar', [DashboardController::class, 'enviarConsultaContacto'])->name('contacto.enviar');
Route::get('/nosotros', fn() => view('nosotros'))->name('nosotros');
Route::get('/proceso', fn() => view('proceso'))->name('proceso');
Route::get('/politicas-y-terminos', fn() => view('legal'))->name('legal');

// 2. RUTAS DE AUTENTICACIÓN
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/registro', [AuthController::class, 'seleccionarRegistro'])->name('registro');
    Route::get('/registro-voluntario', [AuthController::class, 'registroVoluntario'])->name('registro-voluntario');
    Route::get('/registro-organizacion', [AuthController::class, 'registroOrganizacion'])->name('registro-organizacion');
    
    Route::post('/login', [AuthController::class, 'logear']);
    Route::post('/logear', [AuthController::class, 'logear'])->name('logear');
    Route::post('/registro-voluntario', [AuthController::class, 'registrar']);
    Route::post('/registro-organizacion', [AuthController::class, 'registrar']);
    Route::post('/registrar', [AuthController::class, 'registrar'])->name('registrar');
});

// 3. RUTAS PROTEGIDAS
Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::post('/perfil/actualizar', [DashboardController::class, 'actualizarPerfil'])->name('perfil.actualizar');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Voluntarios
    Route::get('/oportunidad/{id}/postular', [OportunidadController::class, 'vistaPostular'])->name('oportunidad.vista-postular');
    Route::post('/postular/{id}', [OportunidadController::class, 'postular'])->name('postular');
    Route::get('/certificado/{id}', [OportunidadController::class, 'verCertificado'])->name('certificado.ver');
    Route::get('/constancia-consolidada', [OportunidadController::class, 'constanciaConsolidada'])->name('constancia.consolidada');
    Route::post('/notificaciones/marcar-leidas', [DashboardController::class, 'marcarNotificacionesLeidas'])->name('notificaciones.leidas');

    // Donaciones Solidarias
    Route::get('/campana/{id}/donar', [OportunidadController::class, 'vistaDonar'])->name('campana.vista-donar');
    Route::post('/campana/{id}/donar', [OportunidadController::class, 'registrarDonacion'])->name('campana.donar');

    // ONGs
    Route::get('/ong/campana/crear', [OportunidadController::class, 'vistaCrearCampana'])->name('ong.campana.crear');
    Route::get('/ong/campana/{id}/editar', [OportunidadController::class, 'vistaEditarCampana'])->name('ong.campana.editar');
    Route::post('/crear-convocatoria', [OportunidadController::class, 'crear'])->name('crear-convocatoria');
    Route::post('/actualizar-convocatoria/{id}', [OportunidadController::class, 'actualizar'])->name('actualizar-convocatoria');
    Route::post('/convocatoria/{id}/toggle-estado', [OportunidadController::class, 'toggleEstadoConvocatoria'])->name('convocatoria.toggle');
    Route::delete('/eliminar-convocatoria/{id}', [OportunidadController::class, 'eliminar'])->name('eliminar-convocatoria');
    Route::get('/postulacion/{id}/expediente', [OportunidadController::class, 'verExpediente'])->name('postulacion.expediente');
    Route::post('/postulacion/{id}/estado', [OportunidadController::class, 'cambiarEstado'])->name('postulacion.estado');
    Route::post('/postulacion/{id}/registrar-avance', [OportunidadController::class, 'registrarAvance'])->name('postulacion.avance');
    Route::post('/campana/{id}/rendicion', [OportunidadController::class, 'registrarRendicionGasto'])->name('campana.rendicion');

    // Administrador Institucional
    Route::post('/admin/campana/{id}/toggle', [DashboardController::class, 'adminToggleCampana'])->name('admin.campana.toggle');
    Route::delete('/admin/campana/{id}', [DashboardController::class, 'adminEliminarCampana'])->name('admin.campana.eliminar');
    Route::post('/admin/crear-ong', [DashboardController::class, 'adminCrearOng'])->name('admin.crear.ong');
    Route::post('/admin/convalidar-horas', [DashboardController::class, 'adminConvalidarHoras'])->name('admin.convalidar.horas');
    Route::post('/admin/comunicado-global', [DashboardController::class, 'adminEnviarComunicado'])->name('admin.comunicado');
    Route::post('/admin/chat/responder', [DashboardController::class, 'adminResponderHiloChat'])->name('admin.chat.responder');
    Route::post('/admin/usuario/{id}/reset-password', [DashboardController::class, 'adminResetPassword'])->name('admin.usuario.reset');
    Route::delete('/admin/usuario/{id}', [DashboardController::class, 'adminEliminarUsuario'])->name('admin.usuario.eliminar');
    Route::get('/admin/exportar-csv', [DashboardController::class, 'adminExportarReporteCsv'])->name('admin.exportar.csv');
    Route::post('/admin/ong/{id}/estado', [DashboardController::class, 'adminToggleEstadoOng'])->name('admin.ong.estado');
});