@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Reportes</h1>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
    </div>

    <!-- Métricas Principales -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-left-primary shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-primary text-uppercase mb-1">Total Usuarios</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsuarios }}</div>
                    </div>
                    <i class="bx bx-group text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-info shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-info text-uppercase mb-1">Administradores</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmin }}</div>
                    </div>
                    <i class="bx bx-shield-alt-2 text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-warning shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-warning text-uppercase mb-1">Docentes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDocente }}</div>
                    </div>
                    <i class="bx bx-chalkboard text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-success shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-success text-uppercase mb-1">Estudiantes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEstudiante }}</div>
                    </div>
                    <i class="bx bx-user-circle text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-dark shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-dark text-uppercase mb-1">Presentes Hoy</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asistenciasHoy }}</div>
                    </div>
                    <i class="bx bx-calendar-check text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-secondary shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-secondary text-uppercase mb-1">Promedio Diario</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $promedioDiario }}</div>
                    </div>
                    <i class="bx bx-trending-up text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de Asistencia -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-success text-uppercase mb-1">Asistencia General</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $porcentajeAsistencia }}%</div>
                        <small class="text-muted">{{ $totalPresentes }} presentes</small>
                    </div>
                    <i class="bx bx-check-circle text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-warning text-uppercase mb-1">Tardanzas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $porcentajeTardanza }}%</div>
                        <small class="text-muted">{{ $totalTardanzas }} tardanzas</small>
                    </div>
                    <i class="bx bx-time text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-danger text-uppercase mb-1">Ausencias</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $porcentajeAusencia }}%</div>
                        <small class="text-muted">{{ $totalAusentes }} ausentes</small>
                    </div>
                    <i class="bx bx-x-circle text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-1">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs text-info text-uppercase mb-1">Total Registros</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAsistencias }}</div>
                        <small class="text-muted">Todos los estados</small>
                    </div>
                    <i class="bx bx-list-ul text-gray-800 fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principales -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header table-primary">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bx bx-bar-chart-alt-2"></i> Asistencias por Curso</h6>
                </div>
                <div class="card-body">
                    <canvas id="graficoCursos" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header table-info d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bx bx-line-chart"></i> Tendencia de Asistencias</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <select id="filtroTiempo" class="form-select form-select-sm" style="width: auto;">
                            <option value="7">Últimos 7 días</option>
                            <option value="15">Últimos 15 días</option>
                            <option value="30">Últimos 30 días</option>
                        </select>
                        <button id="aplicarFiltro" class="btn btn-sm btn-outline-primary">
                            <i class="bx bx-filter"></i> Filtrar
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="graficoTendencia" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla Resumen -->
    <div class="card shadow-sm">
        <div class="card-header table-primary">
            <h6 class="m-0 font-weight-bold text-primary"><i class="bx bx-table"></i> Resumen Detallado por Curso</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover text-center" style="font-family: system-ui, -apple-system, sans-serif;">
                    <thead class="table-primary">
                        <tr>
                            <th>Curso</th>
                            <th>Presentes</th>
                            <th>Tardanzas</th>
                            <th>Ausentes</th>
                            <th>Total</th>
                            <th>% Asistencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($labelsCurso as $index => $curso)
                        @php
                            $total = $dataPresente[$index] + $dataTardanza[$index] + $dataAusente[$index];
                            $porcentaje = $total > 0 ? round(($dataPresente[$index] / $total) * 100, 1) : 0;
                            $estadoColor = $porcentaje >= 80 ? 'text-success' : ($porcentaje >= 60 ? 'text-warning' : 'text-danger');
                            $estadoTexto = $porcentaje >= 80 ? 'Excelente' : ($porcentaje >= 60 ? 'Regular' : 'Crítico');
                        @endphp
                        <tr>
                            <td>{{ $curso }}</td>
                            <td>{{ $dataPresente[$index] }}</td>
                            <td>{{ $dataTardanza[$index] }}</td>
                            <td>{{ $dataAusente[$index] }}</td>
                            <td>{{ $total }}</td>
                            <td>{{ $porcentaje }}%</td>
                            <td>
                                <span class="badge {{ $estadoColor }}" style="background-color: transparent; border: 1px solid currentColor;">
                                    {{ $estadoTexto }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Agregar Boxicons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<!-- Estilos del dashboard -->
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
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    .border-left-dark {
        border-left: 0.25rem solid #5a5c69 !important;
    }
    .border-left-secondary {
        border-left: 0.25rem solid #858796 !important;
    }
    .text-xs {
        font-size: .75rem;
    }
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    .fa-2x {
        font-size: 2em;
    }
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .font-weight-bold {
        font-weight: 700 !important;
    }
    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let graficoTendencia;

const datosIniciales = {
    fechas: {!! json_encode($fechas) !!},
    asistenciasPorFecha: {!! json_encode($asistenciasPorFecha) !!},
    tardanzasPorFecha: {!! json_encode($tardanzasPorFecha) !!},
    ausentesPorFecha: {!! json_encode($ausentesPorFecha) !!}
};

// Gráfico por curso
const ctxCursos = document.getElementById('graficoCursos').getContext('2d');
new Chart(ctxCursos, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labelsCurso) !!},
        datasets: [
            {
                label: 'Presentes',
                data: {!! json_encode($dataPresente) !!},
                backgroundColor: 'rgba(167, 233, 175, 0.3)', // relleno suave (verde claro)
                borderColor: '#28a745',                      // borde fuerte
                borderWidth: 2
            },
            {
                label: 'Tardanzas',
                data: {!! json_encode($dataTardanza) !!},
                backgroundColor: 'rgba(255, 233, 167, 0.3)', // relleno suave (amarillo)
                borderColor: '#ffc107',                      // borde fuerte
                borderWidth: 2
            },
            {
                label: 'Ausentes',
                data: {!! json_encode($dataAusente) !!},
                backgroundColor: 'rgba(255, 179, 179, 0.3)', // relleno suave (rojo claro)
                borderColor: '#dc3545',                      // borde fuerte
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
                    pointStyle: 'circle',
                    padding: 20
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Cantidad'
                }
            }
        }
    }
});

function crearGraficoTendencia(dias = 7) {
    const ctx = document.getElementById('graficoTendencia').getContext('2d');
    const fechas = datosIniciales.fechas.slice(-dias);
    const presentes = datosIniciales.asistenciasPorFecha.slice(-dias);
    const tardanzas = datosIniciales.tardanzasPorFecha.slice(-dias);
    const ausentes = datosIniciales.ausentesPorFecha.slice(-dias);

    if (graficoTendencia) graficoTendencia.destroy();

    graficoTendencia = new Chart(ctx, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [
                {
                    label: 'Presentes',
                    data: presentes,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(167, 233, 175, 0.3)', 
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Tardanzas',
                    data: tardanzas,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 233, 167, 0.3)', 
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Ausentes',
                    data: ausentes,
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
                        pointStyle: 'circle',
                        padding: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return 'Fecha: ' + context[0].label;
                        },
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: { display: true, text: 'Fecha' }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Cantidad'
                    }
                }
            }
        }
    });
}

// Inicializar el gráfico de tendencia
crearGraficoTendencia(7);

// Event listener para el filtro
document.getElementById('filtroTiempo').addEventListener('change', function () {
    crearGraficoTendencia(parseInt(this.value));
});
</script>
@endsection