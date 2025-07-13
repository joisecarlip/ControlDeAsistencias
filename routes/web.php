<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Middleware\RolMiddleware;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CursoDocenteController;
use App\Http\Controllers\ReporteDocenteController;
use App\Http\Controllers\AsistenciaEstudianteController;

// LOGIN
Route::get('/', [LoginController::class, 'MostrarFormularioLogin'])->name('login');
Route::get('/login', [LoginController::class, 'MostrarFormularioLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// DASHBOARD
Route::middleware(['auth:usuarios', RolMiddleware::class . ':administrador'])->group(function () {
    Route::get('/admin/inicio', [LoginController::class, 'inicio'])->name('admin.inicio');
    Route::get('/admin/reportes', [ReporteController::class, 'index'])->name('admin.reportes');
});

Route::middleware(['auth:usuarios', RolMiddleware::class . ':docente'])->group(function () {
    Route::get('/docente/inicio', [LoginController::class, 'inicio'])->name('docente.inicio');
    Route::get('/docente/cursos', [CursoDocenteController::class, 'index'])->name('docente.cursos');
    Route::get('/reporte-docente', [ReporteDocenteController::class, 'index'])->name('docente.reporte');
});

Route::middleware(['auth:usuarios', RolMiddleware::class . ':estudiante'])->group(function () {
    Route::get('/estudiante/inicio', [LoginController::class, 'inicio'])->name('estudiante.inicio');
    Route::get('/estudiante/asistencias', [AsistenciaEstudianteController::class, 'index'])->name('estudiante.asistencias');
    Route::get('/estudiante/cursos', [AsistenciaEstudianteController::class, 'cursos'])->name('estudiante.cursos');
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

# Asistencias

Route::middleware(['auth:usuarios', RolMiddleware::class . ':docente'])->group(function () {
    Route::prefix('docente')->name('docente.')->group(function () {
        Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
        Route::get('/asistencias/estudiantes/{id_curso}', [AsistenciaController::class, 'mostrarEstudiantes'])->name('asistencias.estudiantes');
        Route::post('/asistencias/curso/{id_curso}', [AsistenciaController::class, 'guardarAsistencia'])->name('asistencias.guardar');
        Route::get('/asistencias/historial/{id_curso}', [AsistenciaController::class, 'historial'])->name('asistencias.historial');
        Route::get('/asistencias/cursos', function () {
            $docente = Auth::user();
            $cursos = $docente->cursosComoDocente()->withCount('estudiantes')->get();
            return response()->json($cursos);});
    });
});


# Reportes

