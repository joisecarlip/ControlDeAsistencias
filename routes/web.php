<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RolMiddleware;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CursoController;

// LOGIN
Route::get('/', [LoginController::class, 'MostrarFormularioLogin'])->name('login');
Route::get('/login', [LoginController::class, 'MostrarFormularioLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth:usuarios', RolMiddleware::class . ':administrador'])->group(function () {
    Route::get('/admin/inicio', [LoginController::class, 'inicio'])->name('admin.inicio');
});

Route::middleware(['auth:usuarios', RolMiddleware::class . ':docente'])->group(function () {
    Route::get('/docente/inicio', [LoginController::class, 'inicio'])->name('docente.inicio');
});

Route::middleware(['auth:usuarios', RolMiddleware::class . ':estudiante'])->group(function () {
    Route::get('/estudiante/inicio', [LoginController::class, 'inicio'])->name('estudiante.inicio');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

# PERFIL

Route::middleware(['auth:usuarios'])->group(function () {
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
});

# Usuarios - CRUD

Route::middleware(['auth:usuarios', RolMiddleware::class . ':administrador'])->group(function () {
    Route::get('admin/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});

# Cursos - CRUD

Route::middleware(['auth:usuarios', RolMiddleware::class . ':administrador'])->group(function () {
    Route::get('/admin/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/{id}/edit', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/cursos/{id}', [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/cursos/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy');

    Route::get('/cursos/{id}/gestionar-estudiantes', [CursoController::class, 'gestionarEstudiantes'])->name('cursos.gestionarEstudiantes');
    Route::post('/cursos/{id}/agregar-estudiante', [CursoController::class, 'agregarEstudiante'])->name('cursos.agregarEstudiante');
    Route::delete('/cursos/{cursoId}/quitar-estudiante/{estudianteId}', [CursoController::class, 'quitarEstudiante'])->name('cursos.quitarEstudiante');
});

