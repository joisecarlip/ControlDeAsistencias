@extends('layouts.menu')

@section('content')

<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Estudiantes Inscritos</h1>
        </div>
    </div>
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
            <div class="d-flex align-items-center p-3 shadow border-left-primary rounded bg-white h-100">
                <div class="me-3">
                    <i class="bx bx-book text-primary fa-2x"></i>
                </div>
                <div class="text-start">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Curso</div>
                    <div class="h5 font-weight-bold text-gray-800">{{ $curso->nombre_curso }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-center p-3 shadow border-left-info rounded bg-white h-100">
                <div class="me-3">
                    <i class="bx bx-barcode text-info fa-2x"></i>
                </div>
                <div class="text-start">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Código</div>
                    <div class="h5 font-weight-bold text-gray-800">{{ $curso->codigo_curso }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex align-items-center p-3 shadow border-left-success rounded bg-white h-100">
                <div class="me-3">
                    <i class="bx bx-group text-success fa-2x"></i>
                </div>
                <div class="text-start">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Estudiantes Inscritos</div>
                    <div class="h4 font-weight-bold text-gray-800">{{ $curso->estudiantes->count() }}</div>
                </div>
            </div>
        </div>
    </div>


    <!-- Formulario para agregar estudiante -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light border-bottom">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bx bx-user-plus"></i> Agregar Estudiante al Curso
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cursos.agregarEstudiante', $curso->id_curso) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-10">
                        <label for="estudiante_id" class="form-label text-muted">Seleccionar Estudiante:</label>
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
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light border-bottom">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bx bx-group"></i> Estudiantes Inscritos ({{ $curso->estudiantes->count() }})
            </h6>
        </div>
        <div class="card-body">
            @if($curso->estudiantes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover text-center align-middle">
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
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bx bx-group text-gray-300 fa-3x mb-3"></i>
                    <p class="text-muted">No hay estudiantes inscritos en este curso.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Estilos -->
<style>
    .text-xs {
        font-size: .75rem;
    }
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .fa-3x {
        font-size: 3em;
    }
</style>

@endsection
