@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Mis Cursos</h1>
        </div>
    </div>

    <!-- Estadísticas generales -->
    <div class="row mb-4">
        <!-- Total de cursos -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Cursos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cursosAsignados }}</div>
                            <div class="text-xs text-muted">Semestre actual</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total de estudiantes -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total de Estudiantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEstudiantes }}</div>
                            <div class="text-xs text-muted">En cursos asignados</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asistencia promedio -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Asistencia Promedio
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asistenciaPromedio}}%</div>
                            <div class="text-xs text-muted">% Estudiantes que asistieron puntualmente</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-bar-chart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Cursos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Cursos</h6>
                </div>
                <div class="card-body">
                    @if($cursos->count() > 0)
                        <div class="row">
                            @foreach($cursos as $curso)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border-left-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="font-weight-bold text-dark mb-0">{{ $curso->nombre_curso }}</h6>
                                                <span class="badge badge-outline-primary">{{ $curso->creditos }} créditos</span>
                                            </div>
                                            <p class="text-muted small mb-1">Código: {{ $curso->codigo_curso }}</p>
                                            @if($curso->horarios && $curso->horarios->count())
                                                <div class="text-xs text-muted mb-2">
                                                    @foreach($curso->horarios as $horario)
                                                        <div>{{ $horario->dia_semana }}: {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('g:i A') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('g:i A') }}</div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-xs text-muted mb-2">Horario no definido</div>
                                            @endif
                                            <p class="text-sm text-gray-600 mb-3">
                                                {{ Str::limit($curso->descripcion, 80) }}
                                            </p>

                                            <div class="row text-center mb-3">
                                                <div class="col-4">
                                                    <div class="text-xs text-muted">Presente</div>
                                                    <div class="font-weight-bold text-success">{{ $curso->porcentaje_presente ?? 0 }}%</div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-xs text-muted">Tardanza</div>
                                                    <div class="font-weight-bold text-warning">{{ $curso->porcentaje_tardanza ?? 0 }}%</div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="text-xs text-muted">Ausente</div>
                                                    <div class="font-weight-bold text-danger">{{ $curso->porcentaje_ausente ?? 0 }}%</div>
                                                </div>
                                            </div>

                                            <div class="progress mb-2" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $curso->porcentaje_presente ?? 0 }}%"
                                                    title="Presente: {{ $curso->porcentaje_presente ?? 0 }}%">
                                                </div>
                                                <div class="progress-bar bg-warning" role="progressbar"
                                                    style="width: {{ $curso->porcentaje_tardanza ?? 0 }}%"
                                                    title="Tardanza: {{ $curso->porcentaje_tardanza ?? 0 }}%">
                                                </div>
                                                <div class="progress-bar bg-danger" role="progressbar"
                                                    style="width: {{ $curso->porcentaje_ausente ?? 0 }}%"
                                                    title="Ausente: {{ $curso->porcentaje_ausente ?? 0 }}%">
                                                </div>
                                            </div>

                                            <small class="text-muted mb-3 d-block">
                                                {{ $curso->porcentaje_presente ?? 0 }}% de asistencia presente
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-book fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No tienes cursos asignados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
    .text-xs { font-size: .75rem; }
    .badge-outline-primary {
        color: #4e73df;
        border: 1px solid #4e73df;
        background-color: transparent;
    }
    .fa-2x { font-size: 2em; }
</style>
@endsection
