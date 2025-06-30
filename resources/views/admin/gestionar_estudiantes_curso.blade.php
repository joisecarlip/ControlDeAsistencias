@extends('layouts.menu')

@section('content')
<div>
    <h2>Gestionar Estudiantes - {{ $curso->nombre_curso }}</h2>
    
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <div>
        <a href="{{ route('cursos.index') }}">Volver a Cursos</a>
    </div>

    <!-- Formulario para agregar estudiante -->
    <div>
        <h3>Agregar Estudiante al Curso</h3>
        <form method="POST" action="{{ route('cursos.agregarEstudiante', $curso->id_curso) }}">
            @csrf
            <div>
                <div>
                    <label for="estudiante_id">Seleccionar Estudiante:</label>
                    <select name="estudiante_id" id="estudiante_id" required>
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
                    <button type="submit">Agregar</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Lista de estudiantes inscritos -->
    <div>
        <h3>Estudiantes Inscritos ({{ $curso->estudiantes->count() }})</h3>
        
        @if($curso->estudiantes->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($curso->estudiantes as $estudiante)
                        <tr>
                            <td>{{ $estudiante->nombre }}</td>
                            <td>{{ $estudiante->apellido }}</td>
                            <td>{{ $estudiante->correo }}</td>
                            <td>
                                <form method="POST" action="{{ route('cursos.quitarEstudiante', [$curso->id_curso, $estudiante->id_usuario]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar a este estudiante?')">Eliminar</button>
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
