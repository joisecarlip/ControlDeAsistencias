@extends('layouts.menu')

@section('content')
<div>
    <h2>Gestionar Estudiantes - {{ $curso->nombre_curso }}</h2>
    
    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    <div style="margin-bottom: 20px;">
        <a href="{{ route('cursos.index') }}" style="background-color: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;">Volver a Cursos</a>
    </div>

    <!-- Formulario para agregar estudiante -->
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h3>Agregar Estudiante al Curso</h3>
        <form method="POST" action="{{ route('cursos.agregarEstudiante', $curso->id_curso) }}">
            @csrf
            <div style="display: flex; gap: 10px; align-items: end;">
                <div style="flex: 1;">
                    <label for="estudiante_id">Seleccionar Estudiante:</label>
                    <select name="estudiante_id" id="estudiante_id" required style="width: 100%; padding: 8px; margin-top: 5px;">
                        <option value="">Seleccione un estudiante</option>
                        @foreach($todosEstudiantes as $estudiante)
                            @if(!$curso->estudiantes->contains('id_usuario', $estudiante->id_usuario))
                                <option value="{{ $estudiante->id_usuario }}">
                                    {{ $estudiante->nombre }} {{ $estudiante->apellido }} ({{ $estudiante->correo }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Agregar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de estudiantes inscritos -->
    <div>
        <h3>Estudiantes Inscritos ({{ $curso->estudiantes->count() }})</h3>
        
        @if($curso->estudiantes->count() > 0)
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;">Nombre</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;">Apellido</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: left;">Correo</th>
                        <th style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($curso->estudiantes as $estudiante)
                        <tr>
                            <td style="border: 1px solid #dee2e6; padding: 12px;">{{ $estudiante->nombre }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 12px;">{{ $estudiante->apellido }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 12px;">{{ $estudiante->correo }}</td>
                            <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center;">
                                <form method="POST" action="{{ route('cursos.quitarEstudiante', [$curso->id_curso, $estudiante->id_usuario]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar a este estudiante?')" style="background-color: #dc3545; color: white; padding: 5px 10px; border: none; border-radius: 3px;">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No hay estudiantes inscritos en este curso.</p>
        @endif
    </div>
</div>
@endsection
