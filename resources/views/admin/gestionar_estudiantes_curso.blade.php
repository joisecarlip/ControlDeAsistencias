@extends('layouts.menu')

@section('content')

<div class="container">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-4">
        <a href="{{ route('cursos.index') }}" class="btn btn-danger">
            <i class="bx bx-arrow-back"></i> Volver a Cursos
        </a>
    </div>

    <!-- Estadística del curso -->
    <div class="row mb-4 text-center">
        <div class="col-md-4">
            <div class="p-3 bg-light border rounded">
                <strong>Curso</strong>
                <div class="fs-6">{{ $curso->nombre_curso }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-light border rounded text-info">
                <strong>Código</strong>
                <div class="fs-6">{{ $curso->codigo_curso }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-light border rounded text-success">
                <strong>Estudiantes Inscritos</strong>
                <div class="fs-4">{{ $curso->estudiantes->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Formulario para agregar estudiante -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bx bx-user-plus"></i> Agregar Estudiante al Curso
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cursos.agregarEstudiante', $curso->id_curso) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-10">
                        <label for="estudiante_id" class="form-label">Seleccionar Estudiante:</label>
                        <select name="estudiante_id" id="estudiante_id" class="form-select" required>
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bx bx-plus"></i> Agregar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de estudiantes inscritos -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bx bx-group"></i> Estudiantes Inscritos ({{ $curso->estudiantes->count() }})
            </h5>
        </div>
        <div class="card-body">
            @if($curso->estudiantes->count() > 0)
                <table class="table table-hover text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($curso->estudiantes as $estudiante)
                            <tr>
                                <td>{{ $estudiante->nombre }}</td>
                                <td>{{ $estudiante->apellido }}</td>
                                <td>{{ $estudiante->correo }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cursos.quitarEstudiante', [$curso->id_curso, $estudiante->id_usuario]) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar a este estudiante?')" class="btn btn-link p-0">
                                            <i class="bx bx-trash-alt text-danger fs-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-4">
                    <i class="bx bx-group text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No hay estudiantes inscritos en este curso.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection