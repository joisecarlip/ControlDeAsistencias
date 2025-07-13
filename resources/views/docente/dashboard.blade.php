@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Dashboard Docente</h1>
        </div>
    </div>


    <!-- Tarjetas de estadísticas principales -->
    <div class="row mb-4">
        <!-- Cursos Asignados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Cursos Asignados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $cursosAsignados }}
                            </div>
                            <div class="text-xs text-muted">Semestre actual</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-book-content fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Estudiantes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Estudiantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalEstudiantes }}
                            </div>
                            <div class="text-xs text-muted">En todos los cursos</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próxima Clase -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Próxima Clase
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $proximaClase ? $proximaClase->nombre_curso : 'Sin clases' }}
                            </div>
                            <div class="text-xs text-muted">
                                @if($proximaClase)
                                    Hoy, {{ $proximaClase->hora_inicio }} - {{ $proximaClase->hora_fin }}
                                @else
                                    No hay clases programadas
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-time-five fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asistencias Pendientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Asistencias Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $asistenciasPendientes }}
                            </div>
                            <div class="text-xs text-muted">Por registrar</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-list-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secciones principales -->
    <div class="row">
        <!-- Clases de Hoy -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Clases de Hoy</h6>
                    <small class="text-muted">{{ now()->format('d \d\e M\, Y') }}</small>
                </div>
                <div class="card-body">
                    @if($clasesHoy->count() > 0)
                        @foreach($clasesHoy as $clase)
                            <div class="row align-items-center mb-3 p-3 border-left-primary" style="border-left: 4px solid #4e73df;">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold text-dark mb-1">{{ $clase->nombre_curso }}</h6>
                                    <small class="text-muted">{{ $clase->hora_inicio }} - {{ $clase->hora_fin }} </small>
                                </div>
                                <div class="col-md-3 text-center">
                                    @if($clase->asistencia_registrada)
                                        <span class="badge badge-success">Registrada</span>
                                    @else
                                        <span class="badge badge-warning">Pendiente</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-calendar-x fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No tienes clases programadas para hoy</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Estadísticas de Asistencia -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Estadísticas de Asistencia</h6>
                    <small class="text-muted">Porcentaje de asistencia por curso</small>
                </div>
                <div class="card-body">
                    @if($estadisticasAsistencia->count() > 0)
                        @foreach($estadisticasAsistencia as $estadistica)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark font-weight-bold">{{ $estadistica->nombre_curso }}</span>
                                    <span class="badge badge-primary">{{ $estadistica->porcentaje_asistencia}}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" 
                                         style="width: {{ $estadistica->porcentaje_asistencia}}%"
                                         role="progressbar">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $estadistica->porcentaje_asistencia}}%
                                </small>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-bar-chart fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No hay estadísticas disponibles</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Cursos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Cursos</h6>
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
                                            <p class="text-muted small mb-2">Código: {{ $curso->codigo_curso }}</p>
                                            <p class="text-sm text-gray-600 mb-3">
                                                {{ Str::limit($curso->descripcion, 80) }}
                                            </p>
                                            <div class="row text-center mb-3">
                                                <div class="col-6">
                                                    <div class="text-xs text-muted">Estudiantes</div>
                                                    <div class="font-weight-bold">{{ $curso->estudiantes_count }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-xs text-muted">Asistencia</div>
                                                    <div class="font-weight-bold">{{ $curso->porcentaje_asistencia ?? 0 }}%</div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                
                                                
                                            </div>
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
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .text-xs {
        font-size: .75rem;
    }
    .badge-outline-primary {
        color: #4e73df;
        border: 1px solid #4e73df;
        background-color: transparent;
    }
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    .fa-2x {
        font-size: 2em;
    }
    .fa-3x {
        font-size: 3em;
    }
</style>
@endsection