<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Páginas Públicas
Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::get('/oportunidades', function () {
    return view('oportunidades');
})->name('oportunidades');

Route::get('/detalle-oportunidad', function () {
    return view('detalle-oportunidad');
})->name('detalle-oportunidad');

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
    Route::get('/home', [AuthController::class, 'home'])->name('home');
    Route::get('/dashboard-organizacion', function () {
        return view('dashboard/organizacion');
    })->name('dashboard-organizacion');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});