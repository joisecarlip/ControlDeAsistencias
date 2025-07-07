<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::query();

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        $usuarios = $query->paginate(10);
        
        $total = Usuario::count();
        $totalAdmin = Usuario::where('rol', 'administrador')->count();
        $totalDocente = Usuario::where('rol', 'docente')->count();
        $totalEstudiante = Usuario::where('rol', 'estudiante')->count();

        return view('admin.gestion_usuarios', compact('usuarios', 'total', 'totalAdmin', 'totalDocente', 'totalEstudiante'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'rol' => 'required|in:administrador,docente,estudiante',
            'correo' => 'required|email|unique:usuarios,correo',
            'contrasena' => 'required|string|min:8|confirmed',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'rol' => $request->rol,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->contrasena),
        ]);

        return redirect()->back()->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'rol' => 'required|in:administrador,docente,estudiante',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->rol = $request->rol;
        $usuario->correo = $request->correo;

        if ($request->filled('contrasena')) {
            $request->validate([
                'contrasena' => 'required|string|min:8|confirmed',
            ]);
            $usuario->contrasena = Hash::make($request->contrasena);
        }

        $usuario->save();

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        Usuario::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function inicio()
    {
        $user = Auth::guard('usuarios')->user();

        // Totales para la gráfica de asistencias (últimos 7 días)
        $fechas = [];
        $asistencias = [];
        $tardanzas = [];
        $faltas = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i)->toDateString();
            $fechas[] = $fecha;

            $asistencias[] = DB::table('asistencias')->where('fecha', $fecha)->where('estado', 'presente')->count();
            $tardanzas[]   = DB::table('asistencias')->where('fecha', $fecha)->where('estado', 'tardanza')->count();
            $faltas[]      = DB::table('asistencias')->where('fecha', $fecha)->where('estado', 'ausente')->count();
        }

        $totalUsuarios = \App\Models\Usuario::count();
        $totalAdmin = \App\Models\Usuario::where('rol', 'administrador')->count();
        $totalDocente = \App\Models\Usuario::where('rol', 'docente')->count();
        $totalEstudiante = \App\Models\Usuario::where('rol', 'estudiante')->count();

        switch ($user->rol) {
            case 'administrador':
                return view('admin.dashboard', compact('user', 'fechas', 'asistencias', 'tardanzas', 'faltas'));
            case 'docente':
                return view('docente.dashboard', compact('user'));
            case 'estudiante':
            default:
                return view('estudiante.dashboard', compact('user'));
        }
    }
}
