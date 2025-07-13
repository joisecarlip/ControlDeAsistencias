@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Reporte de Asistencias</h1>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cursos Asignados</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cursosAsignados }}</div>
                    </div>
                    <i class="bx bx-book-content text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Estudiantes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEstudiantes }}</div>
                    </div>
                    <i class="bx bx-group text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Asistencias Pendientes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $asistenciasPendientes }}</div>
                    </div>
                    <i class="bx bx-list-check text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Asistencia Promedio</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $promedioAsistenciaDocente }}%</div>
                    </div>
                    <i class="bx bx-line-chart text-gray-300 fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principales -->
    <div class="row mb-4">
        <!-- Gráfico de barras: Asistencia por Curso -->
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

        <!-- Gráfico de línea: Tendencia de Asistencias -->
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

</div>

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

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
    .text-xs {
        font-size: .75rem;
    }
    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    .fa-2x {
        font-size: 2em;
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
                    title: { display: true, text: 'Cantidad' }
                }
            }
        }
    });
}

crearGraficoTendencia(7);

document.getElementById('filtroTiempo').addEventListener('change', function () {
    crearGraficoTendencia(parseInt(this.value));
});
</script>
@endsection
