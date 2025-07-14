<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        // Conteo de usuarios
        $totalUsuarios = Usuario::count();
        $totalAdmin = Usuario::where('rol', 'administrador')->count();
        $totalDocente = Usuario::where('rol', 'docente')->count();
        $totalEstudiante = Usuario::where('rol', 'estudiante')->count();

        // Estadísticas generales de asistencias
        $totalAsistencias = Asistencia::count();
        $totalPresentes = Asistencia::where('estado', 'presente')->count();
        $totalTardanzas = Asistencia::where('estado', 'tardanza')->count();
        $totalAusentes = Asistencia::where('estado', 'ausente')->count();
        
        // Porcentajes
        $porcentajeAsistencia = $totalAsistencias > 0 ? round(($totalPresentes / $totalAsistencias) * 100, 1) : 0;
        $porcentajeTardanza = $totalAsistencias > 0 ? round(($totalTardanzas / $totalAsistencias) * 100, 1) : 0;
        $porcentajeAusencia = $totalAsistencias > 0 ? round(($totalAusentes / $totalAsistencias) * 100, 1) : 0;

        // Gráfico por curso
        $cursos = Curso::with('asistencias')->get();
        $labelsCurso = [];
        $dataPresente = [];
        $dataTardanza = [];
        $dataAusente = [];

        foreach ($cursos as $curso) {
            $labelsCurso[] = $curso->nombre_curso;
            $dataPresente[] = $curso->asistencias()->where('estado', 'presente')->count();
            $dataTardanza[] = $curso->asistencias()->where('estado', 'tardanza')->count();
            $dataAusente[] = $curso->asistencias()->where('estado', 'ausente')->count();
        }

        // Gráfico últimos 7 días
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

        // Gráfico por horas (análisis de puntualidad)
        $horasLabels = [];
        $asistenciasPorHora = [];
        
        for ($hora = 7; $hora <= 18; $hora++) {
            $horasLabels[] = $hora . ':00';
            $asistenciasPorHora[] = Asistencia::whereTime('hora_registro', '>=', $hora . ':00:00')
                ->whereTime('hora_registro', '<', ($hora + 1) . ':00:00')
                ->where('estado', 'presente')
                ->count();
        }

        // TOP 5 estudiantes con mejor asistencia
        $topEstudiantes = Usuario::where('rol', 'estudiante')
            ->withCount(['asistenciasComoEstudiante as total_asistencias'])
            ->withCount(['asistenciasComoEstudiante as presentes' => function($query) {
                $query->where('estado', 'presente');
            }])
            ->having('total_asistencias', '>', 0)
            ->orderByDesc('presentes')
            ->limit(5)
            ->get();

        // TOP 5 cursos con mayor asistencia
        $topCursos = Curso::withCount(['asistencias as total_asistencias'])
            ->withCount(['asistencias as presentes' => function($query) {
                $query->where('estado', 'presente');
            }])
            ->having('total_asistencias', '>', 0)
            ->orderByDesc('presentes')
            ->limit(5)
            ->get();

        // Estudiantes con mayor ausentismo (problema)
        $estudiantesProblematicos = Usuario::where('rol', 'estudiante')
            ->withCount(['asistenciasComoEstudiante as total_asistencias'])
            ->withCount(['asistenciasComoEstudiante as ausentes' => function($query) {
                $query->where('estado', 'ausente');
            }])
            ->having('total_asistencias', '>', 0)
            ->having('ausentes', '>', 3) // Más de 3 ausencias
            ->orderByDesc('ausentes')
            ->limit(10)
            ->get();

        // Estadísticas por mes (últimos 6 meses)
        $mesesLabels = [];
        $asistenciasPorMes = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $mesesLabels[] = $fecha->format('M Y');
            
            $asistenciasPorMes[] = Asistencia::whereYear('fecha', $fecha->year)
                ->whereMonth('fecha', $fecha->month)
                ->where('estado', 'presente')
                ->count();
        }

        // Promedio diario de asistencias
        $promedioDiario = round(Asistencia::where('estado', 'presente')
            ->whereDate('fecha', '>=', Carbon::now()->subDays(30))
            ->count() / 30, 1);

        // Asistencias de hoy
        $asistenciasHoy = Asistencia::whereDate('fecha', Carbon::today())
            ->where('estado', 'presente')
            ->count();

        // Docentes más activos (que más registran asistencias)
        $docentesActivos = Usuario::where('rol', 'docente')
            ->withCount(['asistenciasComoDocente as registros_asistencia'])
            ->orderByDesc('registros_asistencia')
            ->limit(5)
            ->get();

        return view('admin.reportes', compact(
            'totalUsuarios', 'totalAdmin', 'totalDocente', 'totalEstudiante',
            'totalAsistencias', 'totalPresentes', 'totalTardanzas', 'totalAusentes',
            'porcentajeAsistencia', 'porcentajeTardanza', 'porcentajeAusencia',
            'labelsCurso', 'dataPresente', 'dataTardanza', 'dataAusente',
            'fechas', 'asistenciasPorFecha', 'tardanzasPorFecha', 'ausentesPorFecha',
            'horasLabels', 'asistenciasPorHora',
            'topEstudiantes', 'topCursos', 'estudiantesProblematicos',
            'mesesLabels', 'asistenciasPorMes',
            'promedioDiario', 'asistenciasHoy', 'docentesActivos'
        ));
    }

    public function exportarReporte()
    {
        // Lógica para exportar a PDF o Excel
        return response()->download(storage_path('app/reportes/reporte_asistencias.pdf'));
    }

    public function reportePersonalizado(Request $request)
    {
        // Permitir filtros personalizados por fecha, curso, etc.
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->subMonth());
        $fechaFin = $request->input('fecha_fin', Carbon::now());
        $cursoId = $request->input('curso_id');
        
        // Lógica para generar reporte personalizado
        
        return view('admin.reporte_personalizado', compact('data'));
    }
}