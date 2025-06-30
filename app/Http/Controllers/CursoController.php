<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Usuario;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    // Mostrar la lista de cursos con docentes, estudiantes y horarios
    public function index()
    {
        $cursos = Curso::with(['docente', 'estudiantes', 'horarios'])->get();
        $docentes = Usuario::where('rol', 'docente')->get();
        $estudiantes = Usuario::where('rol', 'estudiante')->get();
        
        return view('admin.gestion_cursos', compact('cursos', 'docentes', 'estudiantes'));
    }

    // Crear un nuevo curso
    public function store(Request $request)
    {
        $request->validate([
            'nombre_curso' => 'required|string|max:255',
            'codigo_curso' => 'required|string|max:50|unique:cursos,codigo_curso',
            'docente_id' => 'required|exists:usuarios,id_usuario',
            'creditos' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:1000',
            'estudiantes' => 'nullable|array',
            'estudiantes.*' => 'exists:usuarios,id_usuario',
            'horarios' => 'nullable|array',
            'horarios.*.dia_semana' => 'required_with:horarios|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'horarios.*.hora_inicio' => 'required_with:horarios|date_format:H:i',
            'horarios.*.hora_fin' => 'required_with:horarios|date_format:H:i|after:horarios.*.hora_inicio',
        ]);

        DB::beginTransaction();
        
        try {
            $curso = Curso::create([
                'nombre_curso' => $request->nombre_curso,
                'codigo_curso' => $request->codigo_curso,
                'id_docente' => $request->docente_id,
                'creditos' => $request->creditos,
                'descripcion' => $request->descripcion,
            ]);

            // Asignar estudiantes al curso
            if ($request->has('estudiantes') && is_array($request->estudiantes)) {
                $curso->estudiantes()->attach($request->estudiantes);
            }

            // Guardar horarios si existen
            if ($request->has('horarios') && is_array($request->horarios)) {
                foreach ($request->horarios as $horario) {
                    if (!empty($horario['dia_semana']) && !empty($horario['hora_inicio']) && !empty($horario['hora_fin'])) {
                        Horario::create([
                            'id_curso' => $curso->id_curso,
                            'dia_semana' => $horario['dia_semana'],
                            'hora_inicio' => $horario['hora_inicio'],
                            'hora_fin' => $horario['hora_fin'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Curso creado exitosamente.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al crear el curso: ' . $e->getMessage());
        }
    }

    // Mostrar detalles de un curso para editar
    public function edit($id)
    {
        $curso = Curso::with(['docente', 'horarios', 'estudiantes'])->findOrFail($id);
        return response()->json($curso);
    }

    // Actualizar un curso existente
    public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);

        // Validación de los datos del formulario
        $request->validate([
            'nombre_curso' => 'required|string|max:255',
            'codigo_curso' => 'required|string|max:50|unique:cursos,codigo_curso,' . $id . ',id_curso',
            'docente_id' => 'required|exists:usuarios,id_usuario',
            'creditos' => 'required|integer|min:1',
            'descripcion' => 'nullable|string|max:1000',
            'estudiantes' => 'nullable|array',
            'estudiantes.*' => 'exists:usuarios,id_usuario',
            'horarios' => 'nullable|array',
            'horarios.*.dia_semana' => 'required_with:horarios|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'horarios.*.hora_inicio' => 'required_with:horarios|date_format:H:i',
            'horarios.*.hora_fin' => 'required_with:horarios|date_format:H:i|after:horarios.*.hora_inicio',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar los datos del curso
            $curso->update([
                'nombre_curso' => $request->nombre_curso,
                'codigo_curso' => $request->codigo_curso,
                'id_docente' => $request->docente_id,
                'creditos' => $request->creditos,
                'descripcion' => $request->descripcion,
            ]);

            // Actualizar estudiantes del curso
            if ($request->has('estudiantes')) {
                $curso->estudiantes()->sync($request->estudiantes);
            } else {
                $curso->estudiantes()->detach();
            }

            // Eliminar horarios anteriores y agregar nuevos
            Horario::where('id_curso', $id)->delete();

            // Guardar nuevos horarios si existen
            if ($request->has('horarios') && is_array($request->horarios)) {
                foreach ($request->horarios as $horario) {
                    if (!empty($horario['dia_semana']) && !empty($horario['hora_inicio']) && !empty($horario['hora_fin'])) {
                        Horario::create([
                            'id_curso' => $curso->id_curso,
                            'dia_semana' => $horario['dia_semana'],
                            'hora_inicio' => $horario['hora_inicio'],
                            'hora_fin' => $horario['hora_fin'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('cursos.index')->with('success', 'Curso actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('cursos.index')->with('error', 'Error al actualizar el curso: ' . $e->getMessage());
        }
    }

    // Eliminar un curso
    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $curso = Curso::findOrFail($id);
            
            // Eliminar relaciones estudiante-curso
            $curso->estudiantes()->detach();
            
            // Eliminar horarios asociados
            Horario::where('id_curso', $curso->id_curso)->delete();
            
            // Eliminar el curso
            $curso->delete();
            
            DB::commit();
            return redirect()->back()->with('success', 'Curso eliminado correctamente.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al eliminar el curso: ' . $e->getMessage());
        }
    }

    // Método para gestionar estudiantes de un curso específico
    public function gestionarEstudiantes($id)
    {
        $curso = Curso::with(['estudiantes'])->findOrFail($id);
        $todosEstudiantes = Usuario::where('rol', 'estudiante')->get();
        
        return view('admin.gestionar_estudiantes_curso', compact('curso', 'todosEstudiantes'));
    }

    // Método para agregar estudiante a un curso
    public function agregarEstudiante(Request $request, $id)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:usuarios,id_usuario'
        ]);

        $curso = Curso::findOrFail($id);
        
        if (!$curso->estudiantes()->where('id_estudiante', $request->estudiante_id)->exists()) {
            $curso->estudiantes()->attach($request->estudiante_id);
            return redirect()->back()->with('success', 'Estudiante agregado correctamente al curso.');
        }
        
        return redirect()->back()->with('error', 'El estudiante ya está inscrito en este curso.');
    }

    // Método para quitar estudiante de un curso
    public function quitarEstudiante($cursoId, $estudianteId)
    {
        $curso = Curso::findOrFail($cursoId);
        $curso->estudiantes()->detach($estudianteId);
        
        return redirect()->back()->with('success', 'Estudiante removido del curso correctamente.');
    }
}
