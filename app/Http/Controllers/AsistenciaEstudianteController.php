<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsistenciaEstudianteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener cursos en los que está inscrito el estudiante
        $misCursos = DB::table('estudiante_curso')
            ->join('cursos', 'estudiante_curso.id_curso', '=', 'cursos.id_curso')
            ->where('estudiante_curso.id_estudiante', $user->id_usuario)
            ->select('cursos.id_curso', 'cursos.nombre_curso', 'cursos.codigo_curso')
            ->get();

        // Estadísticas principales del estudiante
        $totalAsistencias = Asistencia::where('id_estudiante', $user->id_usuario)->count();
        $totalPresentes = Asistencia::where('id_estudiante', $user->id_usuario)
            ->where('estado', 'presente')
            ->count();
        $totalTardanzas = Asistencia::where('id_estudiante', $user->id_usuario)
            ->where('estado', 'tardanza')
            ->count();
        $totalAusentes = Asistencia::where('id_estudiante', $user->id_usuario)
            ->where('estado', 'ausente')
            ->count();

        // Promedio de asistencia general
        $promedioAsistencia = $totalAsistencias > 0 
            ? round(($totalPresentes / $totalAsistencias) * 100) 
            : 0;

        // Query base para asistencias
        $query = DB::table('asistencias')
            ->join('cursos', 'asistencias.id_curso', '=', 'cursos.id_curso')
            ->leftJoin('horarios', 'cursos.id_curso', '=', 'horarios.id_curso')
            ->where('asistencias.id_estudiante', $user->id_usuario)
            ->select(
                'asistencias.*',
                'cursos.nombre_curso',
                'cursos.codigo_curso',
                'horarios.hora_inicio',
                'horarios.hora_fin'
            );

        // Aplicar filtros
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('cursos.nombre_curso', 'like', "%{$buscar}%")
                ->orWhere('asistencias.fecha', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('curso_id')) {
            $query->where('cursos.id_curso', $request->curso_id);
        }

        if ($request->filled('mes')) {
            $query->whereMonth('asistencias.fecha', $request->mes);
        }

        // Obtener asistencias paginadas
        $asistencias = $query->orderBy('asistencias.fecha', 'desc')
            ->paginate(50);

        // Convertir a colección para usar en la vista
        $asistenciasCollection = collect($asistencias->items())->map(function($item) {
            return (object) [
                'id_asistencia' => $item->id_asistencia,
                'fecha' => $item->fecha,
                'estado' => $item->estado,
                'observaciones' => $item->observaciones,
                'hora_registro' => $item->hora_registro,
                'curso' => (object) [
                    'nombre_curso' => $item->nombre_curso,
                    'codigo_curso' => $item->codigo_curso,
                    'horarios' => collect([
                        (object) [
                            'hora_inicio' => $item->hora_inicio,
                            'hora_fin' => $item->hora_fin
                        ]
                    ])
                ]
            ];
        });

        // Reemplazar los items en la paginación
        $asistencias->setCollection($asistenciasCollection);

        return view('estudiante.asistencias', compact(
            'user',
            'misCursos',
            'totalAsistencias',
            'totalPresentes',
            'totalTardanzas',
            'totalAusentes',
            'promedioAsistencia',
            'asistencias'
        ));
    }

    public function detalle($id)
    {
        $user = Auth::user();
        
        $asistencia = DB::table('asistencias')
            ->join('cursos', 'asistencias.id_curso', '=', 'cursos.id_curso')
            ->join('usuarios', 'cursos.id_docente', '=', 'usuarios.id_usuario')
            ->leftJoin('horarios', 'cursos.id_curso', '=', 'horarios.id_curso')
            ->where('asistencias.id_asistencia', $id)
            ->where('asistencias.id_estudiante', $user->id_usuario)
            ->select(
                'asistencias.*',
                'cursos.nombre_curso',
                'cursos.codigo_curso',
                'cursos.creditos',
                'usuarios.nombre as nombre_docente',
                'usuarios.apellido as apellido_docente',
                'horarios.hora_inicio',
                'horarios.hora_fin'
            )
            ->first();

        if (!$asistencia) {
            return redirect()->route('estudiante.asistencias')
                ->with('error', 'Registro de asistencia no encontrado.');
        }

        return view('estudiante.asistencia_detalle', compact('asistencia'));
    }

    public function reporte()
    {
        $user = Auth::user();
        
        // Asistencias por curso
        $asistenciasPorCurso = DB::table('asistencias')
            ->join('cursos', 'asistencias.id_curso', '=', 'cursos.id_curso')
            ->where('asistencias.id_estudiante', $user->id_usuario)
            ->select(
                'cursos.nombre_curso',
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) as presentes"),
                DB::raw("SUM(CASE WHEN estado = 'tardanza' THEN 1 ELSE 0 END) as tardanzas"),
                DB::raw("SUM(CASE WHEN estado = 'ausente' THEN 1 ELSE 0 END) as ausentes"),
                DB::raw("ROUND((SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as porcentaje")
            )
            ->groupBy('cursos.id_curso', 'cursos.nombre_curso')
            ->get();

        // Asistencias por mes
        $asistenciasPorMes = DB::table('asistencias')
            ->where('id_estudiante', $user->id_usuario)
            ->select(
                DB::raw("MONTH(fecha) as mes"),
                DB::raw("YEAR(fecha) as año"),
                DB::raw("COUNT(*) as total"),
                DB::raw("SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) as presentes"),
                DB::raw("ROUND((SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as porcentaje")
            )
            ->groupBy('mes', 'año')
            ->orderBy('año', 'desc')
            ->orderBy('mes', 'desc')
            ->get();

        return view('estudiante.reporte_asistencias', compact(
            'asistenciasPorCurso',
            'asistenciasPorMes'
        ));
    }

    public function cursos()
    {
        $user = Auth::user();
        
        // Obtener cursos en los que está inscrito el estudiante con información adicional
        $misCursos = DB::table('estudiante_curso')
            ->join('cursos', 'estudiante_curso.id_curso', '=', 'cursos.id_curso')
            ->join('usuarios', 'cursos.id_docente', '=', 'usuarios.id_usuario')
            ->leftJoin('horarios', 'cursos.id_curso', '=', 'horarios.id_curso')
            ->where('estudiante_curso.id_estudiante', $user->id_usuario)
            ->select(
                'cursos.id_curso',
                'cursos.nombre_curso',
                'cursos.codigo_curso',
                'cursos.creditos',
                'cursos.descripcion',
                'usuarios.nombre as docente_nombre',
                'usuarios.apellido as docente_apellido',
                'horarios.dia_semana',
                'horarios.hora_inicio',
                'horarios.hora_fin'
            )
            ->get();

        // Agrupar horarios por curso
        $cursosAgrupados = $misCursos->groupBy('id_curso')->map(function ($grupo) {
            $primerItem = $grupo->first();
            return (object) [
                'id_curso' => $primerItem->id_curso,
                'nombre_curso' => $primerItem->nombre_curso,
                'codigo_curso' => $primerItem->codigo_curso,
                'creditos' => $primerItem->creditos,
                'descripcion' => $primerItem->descripcion,
                'docente_nombre' => $primerItem->docente_nombre,
                'docente_apellido' => $primerItem->docente_apellido,
                'horarios' => $grupo->filter(function ($item) {
                    return !is_null($item->dia_semana);
                })->map(function ($item) {
                    return (object) [
                        'dia_semana' => $item->dia_semana,
                        'hora_inicio' => $item->hora_inicio,
                        'hora_fin' => $item->hora_fin
                    ];
                })
            ];
        });

        // Estadísticas generales
        $totalCursos = $cursosAgrupados->count();
        $totalCreditos = $cursosAgrupados->sum('creditos');
        
        // Calcular asistencia promedio general
        $asistenciaPromedio = DB::table('asistencias')
            ->join('estudiante_curso', 'asistencias.id_curso', '=', 'estudiante_curso.id_curso')
            ->where('estudiante_curso.id_estudiante', $user->id_usuario)
            ->select(
                DB::raw('COUNT(*) as total_asistencias'),
                DB::raw('SUM(CASE WHEN estado = "presente" THEN 1 ELSE 0 END) as total_presentes')
            )
            ->first();

        $porcentajeAsistencia = $asistenciaPromedio->total_asistencias > 0 
            ? round(($asistenciaPromedio->total_presentes / $asistenciaPromedio->total_asistencias) * 100, 1)
            : 0;

        // Calcular asistencia por curso
        // Agregar porcentajes detallados de asistencia a cada curso
        $asistenciasPorCurso = DB::table('asistencias')
            ->join('cursos', 'asistencias.id_curso', '=', 'cursos.id_curso')
            ->where('asistencias.id_estudiante', $user->id_usuario)
            ->select(
                'cursos.id_curso',
                DB::raw('COUNT(*) as total_asistencias'),
                DB::raw('SUM(CASE WHEN estado = "presente" THEN 1 ELSE 0 END) as total_presentes'),
                DB::raw('SUM(CASE WHEN estado = "tardanza" THEN 1 ELSE 0 END) as total_tardanzas'),
                DB::raw('SUM(CASE WHEN estado = "ausente" THEN 1 ELSE 0 END) as total_ausentes')
            )
            ->groupBy('cursos.id_curso')
            ->get()
            ->keyBy('id_curso');

        $cursosAgrupados = $cursosAgrupados->map(function ($curso) use ($asistenciasPorCurso) {
            $asistencia = $asistenciasPorCurso->get($curso->id_curso);

            if ($asistencia && $asistencia->total_asistencias > 0) {
                $curso->porcentaje_presente = round(($asistencia->total_presentes / $asistencia->total_asistencias) * 100, 2);
                $curso->porcentaje_tardanza = round(($asistencia->total_tardanzas / $asistencia->total_asistencias) * 100, 2);
                $curso->porcentaje_ausente  = round(($asistencia->total_ausentes  / $asistencia->total_asistencias) * 100, 2);
            } else {
                $curso->porcentaje_presente = 0;
                $curso->porcentaje_tardanza = 0;
                $curso->porcentaje_ausente  = 0;
            }

            return $curso;
        });


        return view('estudiante.cursos', compact(
            'user',
            'cursosAgrupados',
            'totalCursos',
            'totalCreditos',
            'porcentajeAsistencia'
        ));
    }
}