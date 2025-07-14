@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <!-- Mensajes -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="header-section">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Mis Asistencias</h1>
                <p class="text-muted">Registro de asistencias</p>
            </div>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight text-primary text-uppercase mb-1">Total Clases</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAsistencias }}</div>
                        <div class="text-xs text-muted">Clases programadas</div>
                    </div>
                    <i class="bx bx-calendar text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight text-success text-uppercase mb-1">Presente</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPresentes }}</div>
                        <div class="text-xs text-muted">Asistencias confirmadas</div>
                    </div>
                    <i class="bx bx-check text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight text-danger text-uppercase mb-1">Ausente</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAusentes }}</div>
                        <div class="text-xs text-muted">Faltas registradas</div>
                    </div>
                    <i class="bx bx-x text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight text-warning text-uppercase mb-1">Tardanzas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTardanzas }}</div>
                        <div class="text-xs text-muted">Llegadas tardías</div>
                    </div>
                    <i class="bx bx-time text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight text-info text-uppercase mb-1">% Asistencia</div>
                        <div class="h5 mb-0 font-weight-bold 
                            {{ $promedioAsistencia >= 75 ? 'text-success' : ($promedioAsistencia >= 60 ? 'text-warning' : 'text-danger') }}">
                            {{ $promedioAsistencia }}%
                        </div>
                        <div class="text-xs text-muted">
                                    @if($promedioAsistencia >= 75)
                                        Excelente asistencia
                                    @elseif($promedioAsistencia >= 60)
                                        Asistencia regular
                                    @else
                                        Requiere mejora
                                    @endif
                        </div>
                    </div>
                    <i class="bx bx-stats text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <form method="GET" action="{{ route('estudiante.asistencias') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" 
                class="form-control" 
                name="buscar" 
                placeholder="Buscar por fecha o curso..." 
                value="{{ request('buscar') }}">
        </div>
        <div class="col-md-3">
            <select class="form-select" name="curso_id" onchange="this.form.submit()">
                <option value="">Todos los cursos</option>
                @foreach($misCursos as $curso)
                    <option value="{{ $curso->id_curso }}" 
                            {{ request('curso_id') == $curso->id_curso ? 'selected' : '' }}>
                        {{ $curso->nombre_curso }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" name="mes" onchange="this.form.submit()">
                <option value="">Todos los meses</option>
                <option value="1" {{ request('mes') == '1' ? 'selected' : '' }}>Enero</option>
                <option value="2" {{ request('mes') == '2' ? 'selected' : '' }}>Febrero</option>
                <option value="3" {{ request('mes') == '3' ? 'selected' : '' }}>Marzo</option>
                <option value="4" {{ request('mes') == '4' ? 'selected' : '' }}>Abril</option>
                <option value="5" {{ request('mes') == '5' ? 'selected' : '' }}>Mayo</option>
                <option value="6" {{ request('mes') == '6' ? 'selected' : '' }}>Junio</option>
                <option value="7" {{ request('mes') == '7' ? 'selected' : '' }}>Julio</option>
                <option value="8" {{ request('mes') == '8' ? 'selected' : '' }}>Agosto</option>
                <option value="9" {{ request('mes') == '9' ? 'selected' : '' }}>Septiembre</option>
                <option value="10" {{ request('mes') == '10' ? 'selected' : '' }}>Octubre</option>
                <option value="11" {{ request('mes') == '11' ? 'selected' : '' }}>Noviembre</option>
                <option value="12" {{ request('mes') == '12' ? 'selected' : '' }}>Diciembre</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="bx bx-search"></i> Buscar
            </button>
        </div>
    </form>

    <!-- Tabla de Asistencias -->
    <div class="table-responsive">
        <table class="table table-hover text-center">
            <thead class="table-primary">
                <tr>
                    <th>Fecha</th>
                    <th>Curso</th>
                    <th>Horario</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($asistencias as $asistencia)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="bx bx-calendar me-2 text-muted"></i>
                                <div>
                                    <div class="font-weight">
                                        {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d M, Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($asistencia->fecha)->locale('es')->isoFormat('dddd') }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="font-weight">{{ $asistencia->curso->nombre_curso }}</div>
                            <small class="text-muted">{{ $asistencia->curso->codigo_curso }}</small>
                        </td>
                        <td>
                            @if($asistencia->curso->horarios->isNotEmpty())
                                @php
                                    $horario = $asistencia->curso->horarios->first();
                                @endphp
                                <div class="font-weight">
                                    {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($horario->hora_fin)->format('g:i A') }}
                                </div>
                            @else
                                <small class="text-muted">No definido</small>
                            @endif
                        </td>
                        <td>
                            @if($asistencia->estado == 'presente')
                                <span class="badge d-inline-flex align-items-center px-3 py-1 rounded-pill" style="background-color: #198754; color: #f8f9fa; font-weight: normal;">
                                    <i class="bx bx-check-circle me-1"></i> Presente
                                </span>
                            @elseif($asistencia->estado == 'tardanza')
                                <span class="badge d-inline-flex align-items-center px-3 py-1 rounded-pill" style="background-color: #ffc107; color: #212529; font-weight: normal;">
                                    <i class="bx bx-time-five me-1"></i> Tardanza
                                </span>
                            @elseif($asistencia->estado == 'ausente')
                                <span class="badge d-inline-flex align-items-center px-3 py-1 rounded-pill" style="background-color: #dc3545; color: #f8f9fa; font-weight: normal;">
                                    <i class="bx bx-x-circle me-1"></i> Ausente
                                </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="text-center py-5">
                                <i class="bx bx-info-circle display-1 text-muted mb-3"></i>
                                <h5 class="text-muted">No hay registros de asistencia</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['buscar', 'curso_id', 'mes']))
                                        No se encontraron registros con los filtros aplicados.
                                    @else
                                        Aún no tienes registros de asistencia.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
</div>

<!-- Estilos -->
<style>
    .text-xs {
        font-size: .75rem;
    }
    .fa-2x {
        font-size: 2em;
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
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    .font-weight-bold {
        font-weight: 700 !important;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
</style>

@endsection

@push('scripts')
<script>
    // Función para limpiar filtros
    function limpiarFiltros() {
        window.location.href = '{{ route("estudiante.asistencias") }}';
    }

    // Autosubmit del formulario cuando cambia el select
    document.querySelectorAll('select[name="curso_id"], select[name="mes"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
@endpush