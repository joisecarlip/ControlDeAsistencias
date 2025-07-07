@extends('layouts.menu')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Panel de Administración</h2>

    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded">
                <strong>Total Usuarios</strong>
                <div class="fs-4">{{ \App\Models\Usuario::count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-primary">
                <strong>Administradores</strong>
                <div class="fs-4">{{ \App\Models\Usuario::where('rol', 'administrador')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-info">
                <strong>Docentes</strong>
                <div class="fs-4">{{ \App\Models\Usuario::where('rol', 'docente')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-success">
                <strong>Estudiantes</strong>
                <div class="fs-4">{{ \App\Models\Usuario::where('rol', 'estudiante')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">Distribución de Roles</div>
                <div class="card-body">
                    <canvas id="rolesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">Asistencias por Día</div>
                <div class="card-body">
                    <canvas id="asistenciaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Roles Pie Chart
    const ctxRoles = document.getElementById('rolesChart');
    new Chart(ctxRoles, {
        type: 'doughnut',
        data: {
            labels: ['Administradores', 'Docentes', 'Estudiantes'],
            datasets: [{
                label: 'Total',
                data: [
                    {{ \App\Models\Usuario::where('rol', 'administrador')->count() }},
                    {{ \App\Models\Usuario::where('rol', 'docente')->count() }},
                    {{ \App\Models\Usuario::where('rol', 'estudiante')->count() }}
                ],
                backgroundColor: ['#0d6efd', '#0dcaf0', '#198754']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // Línea de asistencia (últimos 7 días)
    const ctxAsistencia = document.getElementById('asistenciaChart');
    new Chart(ctxAsistencia, {
        type: 'line',
        data: {
            
            datasets: [
                {
                    label: 'Asistencias',
                    
                    borderColor: '#0d6efd',
                    fill: false
                },
                {
                    label: 'Tardanzas',
                    
                    borderColor: '#6c757d',
                    fill: false
                },
                {
                    label: 'Faltas',
                    
                    borderColor: '#dc3545',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
