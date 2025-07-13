@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Dashboard Estudiante</h1>
        </div>
    </div>

    <!-- Tarjetas de estadísticas principales -->
    <div class="row mb-4">
        <!-- Asistencia Total -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Asistencia Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $promedioAsistencia }}%</div>
                            <div class="text-xs text-muted">
                                @if($tendencia > 0)
                                    <span class="text-success">+{{ $tendencia }}%</span> desde el mes pasado
                                @elseif($tendencia < 0)
                                    <span class="text-danger">{{ $tendencia }}%</span> desde el mes pasado
                                @else
                                    <span class="text-muted">Sin cambios</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-trending-up fa-2x text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cursos Activos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Cursos Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cursosActivos }}</div>
                            <div class="text-xs text-muted">Semestre actual</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-refresh fa-2x text-gray-800"></i>
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
                                    Hoy, {{ date('H:i', strtotime($proximaClase->hora_inicio)) }} - {{ date('H:i', strtotime($proximaClase->hora_fin)) }}
                                @else
                                    No hay más clases hoy
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-time fa-2x text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faltas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Faltas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $faltasSemestre }}</div>
                            <div class="text-xs text-muted">Este semestre</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-calendar-x fa-2x text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila principal con dos columnas -->
    <div class="row">
        <!-- Columna izquierda - Asistencia Reciente -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Asistencia Reciente</h6>
                    <small class="text-muted">Últimas 5 clases registradas</small>
                </div>
                <div class="card-body">
                    @if($asistenciasRecientes->count() > 0)
                        @foreach($asistenciasRecientes as $asistencia)
                            <div class="row align-items-center mb-3 p-3 border-left-primary rounded shadow-sm" style="border-left: 4px solid #4e73df; height: 80px;">
                                <div class="col-md-6">
                                    <h6 class="font-weight-bold text-dark mb-1">{{ $asistencia->nombre_curso }}</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($asistencia->fecha)->format('d M, Y') }}</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    @if($asistencia->estado == 'presente')
                                        <span class="badge badge-success">Presente</span>
                                    @elseif($asistencia->estado == 'tardanza')
                                        <span class="badge badge-warning">Tardanza</span>
                                    @else
                                        <span class="badge badge-danger">Ausente</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-calendar-minus fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No hay asistencias registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna derecha - Horario de Hoy -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Horario de Hoy</h6>
                    <small class="text-muted">{{ \Carbon\Carbon::now()->format('d M, Y') }}</small>
                </div>
                <div class="card-body">
                    @if($horarioHoy->count() > 0)
                        @foreach($horarioHoy as $clase)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark font-weight-bold">{{ $clase->nombre_curso }}</span>
                                    <small class="text-muted">{{ date('H:i', strtotime($clase->hora_inicio)) }} - {{ date('H:i', strtotime($clase->hora_fin)) }}</></small>
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
    </div>

    <!-- Mis Cursos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Cursos</h6>
                </div>
                <div class="card-body">
                    @if($misCursos->count() > 0)
                        <div class="row">
                            @foreach($misCursos as $curso)
                                @php
                                    $hoy = \Carbon\Carbon::now()->locale('es')->isoFormat('dddd'); // ejemplo: 'lunes'
                                    $horariosCurso = $horarios->where('nombre_curso', $curso->nombre_curso);
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border-left-success h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="font-weight-bold text-dark mb-0">{{ $curso->nombre_curso }}</h6>
                                            </div>
                                            <p class="text-muted small mb-2">Código: {{ $curso->codigo_curso }}</p>
                                            <div class="row text-center mb-3">
                                                <div class="col-6">
                                                    <div class="text-xs text-muted">Docente</div>
                                                    <div class="font-weight-bold text-sm">{{ $curso->nombre_docente }} {{ $curso->apellido_docente }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-xs text-muted">Créditos</div>
                                                    <div class="font-weight-bold">{{ $curso->creditos }}</div>
                                                </div>
                                            </div>

                                            @if($horariosCurso->count())
                                                <div class="mt-2">
                                                    @foreach($horariosCurso as $horario)
                                                        <div class="text-sm">
                                                            <i class="bx bx-calendar"></i>
                                                            <span class="{{ strtolower($horario->dia_semana) == strtolower($hoy) ? 'text-success font-weight-bold' : 'text-muted' }}">
                                                                {{ strtolower($horario->dia_semana) == strtolower($hoy) ? 'Hoy' : $horario->dia_semana }}
                                                                — {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="mt-2 text-muted text-sm">No tiene horarios asignados</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-book fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No estás inscrito en ningún curso</p>
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
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .text-sm {
        font-size: 0.875rem;
    }
</style>

@endsection