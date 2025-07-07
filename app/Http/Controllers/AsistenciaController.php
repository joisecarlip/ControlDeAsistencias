<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Curso;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index()
    {
        $docente = Auth::user();
        
        // Obtener cursos del docente
        $cursos = $docente->cursosComoDocente()->with('estudiantes')->get();
        
        return view('docente.asistencias', compact('cursos'));
    }

    public function mostrarEstudiantes($id_curso)
    {
        $docente = Auth::user();

        $curso = Curso::with('estudiantes')->findOrFail($id_curso);

        // Validar que el docente tenga acceso
        if (!$docente->cursosComoDocente()->where('id_curso', $id_curso)->exists()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $estudiantes = $curso->estudiantes->map(function ($est) {
            return [
                'id_usuario' => $est->id_usuario,
                'nombre' => $est->nombre,
                'apellido' => $est->apellido,
                'correo' => $est->correo,
                'codigo' => $est->codigo ?? '', // si usas código de estudiante
            ];
        });

        return response()->json(['estudiantes' => $estudiantes]);
    }


    public function guardarAsistencia(Request $request, $id_curso)
    {
        $request->validate([
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
            'asistencias.*' => 'in:presente,tardanza,ausente'
        ]);

        $docente = Auth::user();
        $curso = Curso::findOrFail($id_curso);
        
        // Verificar que el docente tiene acceso a este curso
        if (!$docente->cursosComoDocente()->where('id_curso', $id_curso)->exists()) {
            return redirect()->route('docente.asistencias')->with('error', 'No tienes acceso a este curso.');
        }

        $fecha = $request->fecha;
        $horaActual = Carbon::now()->toTimeString();

        foreach ($request->asistencias as $id_estudiante => $estado) {
            Asistencia::updateOrCreate([
                'id_estudiante' => $id_estudiante,
                'id_curso' => $id_curso,
                'fecha' => $fecha
            ], [
                'id_docente' => $docente->id_usuario,
                'estado' => $estado,
                'hora_registro' => $horaActual,
                'observaciones' => $request->observaciones[$id_estudiante] ?? null
            ]);
        }

        return redirect()->route('docente.asistencias.estudiantes', $id_curso)
            ->with('success', 'Asistencia registrada correctamente.');
    }

    public function historial($id_curso)
    {
        $docente = Auth::user();
        $curso = Curso::findOrFail($id_curso);
        
        // Verificar que el docente tiene acceso a este curso
        if (!$docente->cursosComoDocente()->where('id_curso', $id_curso)->exists()) {
            return redirect()->route('docente.asistencias.index')->with('error', 'No tienes acceso a este curso.');
        }

        $asistencias = Asistencia::where('id_curso', $id_curso)
            ->with('estudiante')
            ->orderBy('fecha', 'desc')
            ->orderBy('id_estudiante')
            ->paginate(50);

        return view('docente.asistencias.historial', compact('curso', 'asistencias'));
    }
}