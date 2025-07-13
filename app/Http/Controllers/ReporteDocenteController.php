<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Asistencia;
use App\Models\Usuario;
use Carbon\Carbon;

class ReporteDocenteController extends Controller
{
    public function index()
    {
        $docenteId = Auth::user()->id_usuario;

        // Cursos asignados al docente (id_docente)
        $cursos = Curso::with(['asistencias', 'estudiantesCurso'])
            ->where('id_docente', $docenteId)
            ->get();

        $cursosAsignados = $cursos->count();

        // Total estudiantes únicos en todos los cursos
        $totalEstudiantes = $cursos->pluck('estudiantesCurso')
            ->flatten()
            ->unique('id_usuario')
            ->count();

        // Asistencias pendientes (sin estado)
        $asistenciasPendientes = Asistencia::whereIn('id_curso', $cursos->pluck('id_curso'))
            ->whereNull('estado')
            ->count();

        // Asistencia total y promedio
        $asistencias = Asistencia::whereIn('id_curso', $cursos->pluck('id_curso'))->get();
        $totalAsistencias = $asistencias->count();
        $totalPresentes = $asistencias->where('estado', 'presente')->count();

        $promedioAsistenciaDocente = $totalAsistencias > 0 
            ? round(($totalPresentes / $totalAsistencias) * 100, 1)
            : 0;

        // Gráfico por curso
        $labelsCurso = [];
        $dataPresente = [];
        $dataTardanza = [];
        $dataAusente = [];

        foreach ($cursos as $curso) {
            $labelsCurso[] = $curso->nombre_curso;
            $asistenciasCurso = $curso->asistencias;

            $dataPresente[] = $asistenciasCurso->where('estado', 'presente')->count();
            $dataTardanza[] = $asistenciasCurso->where('estado', 'tardanza')->count();
            $dataAusente[]  = $asistenciasCurso->where('estado', 'ausente')->count();
        }

        // Gráfico por fecha (últimos 30 días)
        $fechas = collect();
        $asistenciasPorFecha = [];
        $tardanzasPorFecha = [];
        $ausentesPorFecha = [];

        for ($i = 29; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i)->toDateString();
            $fechas->push($fecha);

            $asistenciasPorFecha[] = Asistencia::whereIn('id_curso', $cursos->pluck('id_curso'))
                ->whereDate('fecha', $fecha)
                ->where('estado', 'presente')->count();

            $tardanzasPorFecha[] = Asistencia::whereIn('id_curso', $cursos->pluck('id_curso'))
                ->whereDate('fecha', $fecha)
                ->where('estado', 'tardanza')->count();

            $ausentesPorFecha[] = Asistencia::whereIn('id_curso', $cursos->pluck('id_curso'))
                ->whereDate('fecha', $fecha)
                ->where('estado', 'ausente')->count();
        }

        return view('docente.reportesdocente', [
            'cursosAsignados' => $cursosAsignados,
            'totalEstudiantes' => $totalEstudiantes,
            'asistenciasPendientes' => $asistenciasPendientes,
            'promedioAsistenciaDocente' => $promedioAsistenciaDocente,
            'totalAsistenciasDocente' => $totalAsistencias,
            'labelsCurso' => $labelsCurso,
            'dataPresente' => $dataPresente,
            'dataTardanza' => $dataTardanza,
            'dataAusente' => $dataAusente,
            'fechas' => $fechas,
            'asistenciasPorFecha' => $asistenciasPorFecha,
            'tardanzasPorFecha' => $tardanzasPorFecha,
            'ausentesPorFecha' => $ausentesPorFecha,
        ]);
    }
}
