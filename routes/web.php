<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OportunidadController;
use App\Http\Controllers\PostulacionController;
use Illuminate\Support\Facades\Route;

// Páginas Públicas
Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::get('/oportunidades', [OportunidadController::class, 'index'])->name('oportunidades');
Route::get('/detalle-oportunidad', [OportunidadController::class, 'show'])->name('detalle-oportunidad');

// Acciones de Postulación (Público / Estudiantes)
Route::post('/postular', [PostulacionController::class, 'store'])->name('postulaciones.store');

// APIs JSON para sincronización
Route::get('/api/oportunidades', [OportunidadController::class, 'apiListar'])->name('api.oportunidades');
Route::get('/api/oportunidades/mapa', [OportunidadController::class, 'apiMapa'])->name('api.oportunidades.mapa');

Route::get('/api/postulaciones', [PostulacionController::class, 'apiListar'])->name('api.postulaciones');

// Rutas de Autenticación (Solo Invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::get('/registro-voluntario', [AuthController::class, 'registroVoluntario'])->name('registro-voluntario');
    Route::get('/registro-organizacion', [AuthController::class, 'registroOrganizacion'])->name('registro-organizacion');
    Route::post('/registrar', [AuthController::class, 'registrar'])->name('registrar');
    Route::post('/logear', [AuthController::class, 'logear'])->name('logear');
});

// Rutas Protegidas (Solo Usuarios Autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'voluntario'])->name('home');
    Route::get('/dashboard-organizacion', [DashboardController::class, 'organizacion'])->name('dashboard-organizacion');

    // Gestión de Postulaciones (Cancelar y Cambiar Estado)
    Route::delete('/postulaciones/{id}/cancelar', [PostulacionController::class, 'cancelar'])->name('postulaciones.cancelar');
    Route::post('/postulaciones/{id}/estado', [PostulacionController::class, 'cambiarEstado'])->name('postulaciones.estado');

    // Publicación de nuevas convocatorias
    Route::post('/oportunidades/crear', [OportunidadController::class, 'store'])->name('oportunidades.store');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
