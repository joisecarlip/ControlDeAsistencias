<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Usuario;
use App\Models\Curso;
use App\Models\Horario;
use App\Models\Asistencia;

class LoginController extends Controller
{
    public function inicio()
    {
        $user = Auth::guard('usuarios')->user();

        switch ($user->rol) {
            case 'administrador':
                return $this->dashboardAdmin($user);
            case 'docente':
                return $this->dashboardDocente($user);
            case 'estudiante':
            default:
                return $this->dashboardEstudiante($user);
        }
    }

    private function dashboardAdmin($user)
    {
        $fechas = [];
        $asistencias = [];
        $tardanzas = [];
        $faltas = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i)->toDateString();
            $fechas[] = $fecha;

            $asistencias[] = DB::table('asistencias')
                ->where('fecha', $fecha)
                ->where('estado', 'presente')
                ->count();

            $tardanzas[] = DB::table('asistencias')
                ->where('fecha', $fecha)
                ->where('estado', 'tardanza')
                ->count();

            $faltas[] = DB::table('asistencias')
                ->where('fecha', $fecha)
                ->where('estado', 'ausente')
                ->count();
        }

        $ultimos7dias = Carbon::today()->subDays(6)->toDateString();

        $asistenciasPorCurso = DB::table('asistencias')
            ->join('cursos', 'asistencias.id_curso', '=', 'cursos.id_curso')
            ->select(
                'cursos.nombre_curso',
                DB::raw("SUM(CASE WHEN estado = 'presente' THEN 1 ELSE 0 END) as presentes"),
                DB::raw("SUM(CASE WHEN estado = 'tardanza' THEN 1 ELSE 0 END) as tardanzas"),
                DB::raw("SUM(CASE WHEN estado = 'ausente' THEN 1 ELSE 0 END) as ausentes")
            )
            ->where('fecha', '>=', $ultimos7dias)
            ->groupBy('cursos.nombre_curso')
            ->get();

        $labelsCurso = $asistenciasPorCurso->pluck('nombre_curso');
        $dataPresente = $asistenciasPorCurso->pluck('presentes');
        $dataTardanza = $asistenciasPorCurso->pluck('tardanzas');
        $dataAusente = $asistenciasPorCurso->pluck('ausentes');

        $totalUsuarios = Usuario::count();
        $totalAdmin = Usuario::where('rol', 'administrador')->count();
        $totalDocente = Usuario::where('rol', 'docente')->count();
        $totalEstudiante = Usuario::where('rol', 'estudiante')->count();

        return view('admin.dashboard', compact(
            'user', 'fechas', 'asistencias', 'tardanzas', 'faltas',
            'totalUsuarios', 'totalAdmin', 'totalDocente', 'totalEstudiante',
            'labelsCurso', 'dataPresente', 'dataTardanza', 'dataAusente'
        ));
    }

    private function dashboardDocente($user)
    {
        // Obtener cursos asignados al docente
        $cursos = Curso::where('id_docente', $user->id_usuario)
            ->withCount('estudiantes')
            ->get();

        // Calcular porcentaje de asistencia por curso
        $cursos->each(function ($curso) {
            $totalAsistencias = Asistencia::where('id_curso', $curso->id_curso)->count();
            $presentesAsistencias = Asistencia::where('id_curso', $curso->id_curso)
                ->where('estado', 'presente')
                ->count();
            
            $curso->porcentaje_asistencia = $totalAsistencias > 0 
                ? round(($presentesAsistencias / $totalAsistencias) * 100, 1) 
                : 0;
        });

        // Estadísticas principales
        $cursosAsignados = $cursos->count();
        $totalEstudiantes = $cursos->sum('estudiantes_count');

        // Clases de hoy
        $hoy = Carbon::today()->format('l'); // Nombre del día en inglés
        $diasEspanol = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        
        $diaHoy = $diasEspanol[$hoy];

        $clasesHoy = DB::table('cursos')
            ->join('horarios', 'cursos.id_curso', '=', 'horarios.id_curso')
            ->where('cursos.id_docente', $user->id_usuario)
            ->where('horarios.dia_semana', $diaHoy)
            ->select(
                'cursos.id_curso',
                'cursos.nombre_curso',
                'horarios.hora_inicio',
                'horarios.hora_fin'
            )
            ->orderBy('horarios.hora_inicio')
            ->get();

        // Verificar si ya se registró asistencia hoy para cada clase
        $clasesHoy->each(function ($clase) {
            $asistenciaHoy = Asistencia::where('id_curso', $clase->id_curso)
                ->whereDate('fecha', Carbon::today())
                ->exists();
            
            $clase->asistencia_registrada = $asistenciaHoy;
        });

        // Próxima clase
        $proximaClase = $clasesHoy->where('asistencia_registrada', false)->first();

        // Asistencias pendientes de registrar
        $asistenciasPendientes = $clasesHoy->where('asistencia_registrada', false)->count();

        // Estadísticas de asistencia por curso
        $estadisticasAsistencia = collect();
        foreach ($cursos as $curso) {
            $totalRegistros = Asistencia::where('id_curso', $curso->id_curso)->count();
            $totalPresentes = Asistencia::where('id_curso', $curso->id_curso)
                ->where('estado', 'presente')
                ->count();
            
            if ($totalRegistros > 0) {
                $porcentaje = round(($totalPresentes / $totalRegistros) * 100, 1);
                $estadisticasAsistencia->push((object) [
                    'nombre_curso' => $curso->nombre_curso,
                    'porcentaje_asistencia' => $porcentaje,
                    'total_presentes' => $totalPresentes,
                    'total_registros' => $totalRegistros
                ]);
            }
        }

        return view('docente.dashboard', compact(
            'user',
            'cursos',
            'cursosAsignados',
            'totalEstudiantes',
            'clasesHoy',
            'proximaClase',
            'asistenciasPendientes',
            'estadisticasAsistencia'
        ));
    }


    private function dashboardEstudiante($user)
    {
        // Obtener cursos en los que está inscrito el estudiante
        $misCursos = DB::table('estudiante_curso')
            ->join('cursos', 'estudiante_curso.id_curso', '=', 'cursos.id_curso')
            ->join('usuarios', 'cursos.id_docente', '=', 'usuarios.id_usuario')
            ->where('estudiante_curso.id_estudiante', $user->id_usuario)
            ->select(
                'cursos.id_curso',
                'cursos.nombre_curso',
                'cursos.codigo_curso',
                'cursos.creditos',
                'usuarios.nombre as nombre_docente',
                'usuarios.apellido as apellido_docente'
            )
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

        // Cursos activos (inscritos)
        $cursosActivos = $misCursos->count();

        // Asistencias recientes (últimas 5 clases del estudiante)
        $asistenciasRecientes = DB::table('asistencias')
            ->join('cursos', 'asistencias.id_curso', '=', 'cursos.id_curso')
            ->where('asistencias.id_estudiante', $user->id_usuario)
            ->select(
                'cursos.nombre_curso',
                'asistencias.fecha',
                'asistencias.estado'
            )
            ->orderBy('asistencias.fecha', 'desc')
            ->limit(5)
            ->get();

        // Clases de hoy
        $hoy = Carbon::today()->format('l');
        $diasEspanol = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];
        
        $diaHoy = $diasEspanol[$hoy];

        // Horario de hoy del estudiante
        $horarioHoy = DB::table('estudiante_curso')
            ->join('cursos', 'estudiante_curso.id_curso', '=', 'cursos.id_curso')
            ->join('horarios', 'cursos.id_curso', '=', 'horarios.id_curso')
            ->where('estudiante_curso.id_estudiante', $user->id_usuario)
            ->where('horarios.dia_semana', $diaHoy)
            ->select(
                'cursos.nombre_curso',
                'horarios.hora_inicio',
                'horarios.hora_fin',
            )
            ->orderBy('horarios.hora_inicio')
            ->get();
        
        $horarios = DB::table('estudiante_curso')
            ->join('cursos', 'estudiante_curso.id_curso', '=', 'cursos.id_curso')
            ->join('horarios', 'cursos.id_curso', '=', 'horarios.id_curso')
            ->where('estudiante_curso.id_estudiante', $user->id_usuario)
            ->select(
                'cursos.nombre_curso',
                'horarios.dia_semana',
                'horarios.hora_inicio',
                'horarios.hora_fin'
            )
            ->orderByRaw("FIELD(horarios.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo')")
            ->orderBy('horarios.hora_inicio')
            ->get();

        // Próxima clase (primera clase del día que aún no ha terminado)
        $horaActual = Carbon::now()->format('H:i:s');
        $proximaClase = $horarioHoy->filter(function ($clase) use ($horaActual) {
            return $clase->hora_fin > $horaActual;
        })->first();

        // Calcular faltas del semestre actual
        $inicioSemestre = Carbon::now()->startOfMonth()->subMonths(2); // Ajustar según tu semestre
        $faltasSemestre = Asistencia::where('id_estudiante', $user->id_usuario)
            ->where('estado', 'ausente')
            ->where('fecha', '>=', $inicioSemestre)
            ->count();

        // Asistencia del mes pasado para comparar tendencia
        $inicioMesPasado = Carbon::now()->subMonth()->startOfMonth();
        $finMesPasado = Carbon::now()->subMonth()->endOfMonth();
        
        $asistenciasMesPasado = Asistencia::where('id_estudiante', $user->id_usuario)
            ->whereBetween('fecha', [$inicioMesPasado, $finMesPasado])
            ->count();
        
        $presentesMesPasado = Asistencia::where('id_estudiante', $user->id_usuario)
            ->where('estado', 'presente')
            ->whereBetween('fecha', [$inicioMesPasado, $finMesPasado])
            ->count();

        $promedioMesPasado = $asistenciasMesPasado > 0 
            ? round(($presentesMesPasado / $asistenciasMesPasado) * 100) 
            : 0;

        // Calcular tendencia
        $tendencia = $promedioAsistencia - $promedioMesPasado;

        return view('estudiante.dashboard', compact(
            'user',
            'misCursos',
            'cursosActivos',
            'totalAsistencias',
            'totalPresentes',
            'totalTardanzas',
            'totalAusentes',
            'promedioAsistencia',
            'asistenciasRecientes',
            'horarioHoy',
            'proximaClase',
            'faltasSemestre',
            'tendencia',
            'horarios',
        ));
    }

    public function MostrarFormularioLogin()
    {
        return view('auth.Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $credentials = $request->only('correo', 'contrasena');

        $user = Usuario::where('correo', $credentials['correo'])->first();

        if ($user && Hash::check($credentials['contrasena'], $user->contrasena)) {
            Auth::guard('usuarios')->login($user, $request->filled('remember'));
            $request->session()->regenerate();

            switch ($user->rol) {
                case 'administrador':
                    return redirect()->intended('/admin/inicio');
                case 'docente':
                    return redirect()->intended('/docente/inicio');
                case 'estudiante':
                default:
                    return redirect()->intended('/estudiante/inicio');
            }
        }

        return back()->withErrors([
            'correo' => 'Credenciales incorrectas.'
        ])->withInput($request->only('correo', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('usuarios')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}