<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Asistencia;

class CursoDocenteController extends Controller
{
    public function index()
    {
        $user = Auth::guard('usuarios')->user();

        $cursos = Curso::where('id_docente', $user->id_usuario)
            ->withCount('estudiantes')
            ->get();

        $cursosAsignados = $cursos->count();
        $totalEstudiantes = $cursos->sum('estudiantes_count');

        // Calcular porcentaje de asistencia detallado por curso
        $cursos->each(function ($curso) {
            $total = Asistencia::where('id_curso', $curso->id_curso)->count();

            $presentes = Asistencia::where('id_curso', $curso->id_curso)
                ->where('estado', 'presente')->count();

            $tardanzas = Asistencia::where('id_curso', $curso->id_curso)
                ->where('estado', 'tardanza')->count();

            $ausentes = Asistencia::where('id_curso', $curso->id_curso)
                ->where('estado', 'ausente')->count();

            $curso->porcentaje_presente = $total > 0 ? round(($presentes / $total) * 100, 1) : 0;
            $curso->porcentaje_tardanza = $total > 0 ? round(($tardanzas / $total) * 100, 1) : 0;
            $curso->porcentaje_ausente = $total > 0 ? round(($ausentes / $total) * 100, 1) : 0;

            // Por compatibilidad con vista antigua
            $curso->porcentaje_asistencia = $curso->porcentaje_presente;
        });

        // Promedio de asistencias presentes
        $asistenciaPromedio = $cursos->count() > 0
            ? round($cursos->avg('porcentaje_presente'), 1)
            : 0;

        return view('docente.cursos', compact('cursos', 'cursosAsignados', 'totalEstudiantes', 'asistenciaPromedio'));
    }
}
