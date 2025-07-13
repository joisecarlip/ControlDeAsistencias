@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Dashboard Administrador</h1>
        </div>
    </div>

    <!-- Tarjetas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Usuarios</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsuarios }}</div>
                    </div>
                    <i class="bx bx-user text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Administradores</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmin }}</div>
                    </div>
                    <i class="bx bx-shield-quarter text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Docentes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDocente }}</div>
                    </div>
                    <i class="bx bx-chalkboard text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Estudiantes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEstudiante }}</div>
                    </div>
                    <i class="bx bxs-graduation text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        @if(isset($labelsCurso))
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header table-primary">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bx bx-bar-chart-alt-2"></i> Asistencias por Curso</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficoCursos" height="200"></canvas>
                </div>
            </div>
        </div>
        @endif

        @if(isset($fechas))
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header table-info">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bx bx-line-chart"></i> Asistencias Últimos 7 Días</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficoFechas" height="200"></canvas>
                </div>
            </div>
        </div>
        @endif
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
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if(isset($labelsCurso))
const ctxCursos = document.getElementById('graficoCursos').getContext('2d');
new Chart(ctxCursos, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labelsCurso) !!},
        datasets: [
            {
                label: 'Presentes',
                data: {!! json_encode($dataPresente) !!},
                backgroundColor: 'rgba(167, 233, 175, 0.3)',
                borderColor: '#28a745',
                borderWidth: 2
            },
            {
                label: 'Tardanzas',
                data: {!! json_encode($dataTardanza) !!},
                backgroundColor: 'rgba(255, 233, 167, 0.3)',
                borderColor: '#ffc107',
                borderWidth: 2
            },
            {
                label: 'Ausentes',
                data: {!! json_encode($dataAusente) !!},
                backgroundColor: 'rgba(255, 179, 179, 0.3)',
                borderColor: '#dc3545',
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Cantidad' }
            }
        }
    }
});
@endif

@if(isset($fechas))
const ctxFechas = document.getElementById('graficoFechas').getContext('2d');
new Chart(ctxFechas, {
    type: 'line',
    data: {
        labels: {!! json_encode($fechas) !!},
        datasets: [
            {
                label: 'Asistencias',
                data: {!! json_encode($asistencias) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(167, 233, 175, 0.3)',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Tardanzas',
                data: {!! json_encode($tardanzas) !!},
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 233, 167, 0.3)',
                fill: true,
                tension: 0.4
            },
            {
                label: 'Faltas',
                data: {!! json_encode($faltas) !!},
                borderColor: '#dc3545',
                backgroundColor: 'rgba(255, 179, 179, 0.3)',
                fill: true,
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Cantidad' }
            }
        }
    }
});
@endif
</script>
@endsection
