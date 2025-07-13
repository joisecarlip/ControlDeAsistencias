@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Asistencia</h1>
            <p class="text-muted">Control de asistencias</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <label for="curso" class="form-label text-dark font-weight-bold">
                        <i class="bx bx-book-content me-2"></i>Curso
                    </label>
                    <select id="curso" class="form-select">
                        <option value="">Seleccione un curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id_curso }}">{{ $curso->nombre_curso }} ({{ $curso->codigo_curso }}) - {{ $curso->hora_inicio }} a {{ $curso->hora_fin }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <label for="fecha" class="form-label text-dark font-weight-bold">
                        <i class="bx bx-calendar me-2"></i>Fecha
                    </label>
                    <input type="date" id="fecha" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>
    </div>

    <!-- Panel contadores -->
    <div class="row mb-4">
        <!-- Total Estudiantes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Estudiantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="total-estudiantes">0</span>
                            </div>
                            <div class="text-xs text-muted">Estudiantes inscritos</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-group fa-2x text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Presente -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Presente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="contador-presente">0</span>
                            </div>
                            <div class="text-xs text-muted">Estudiantes que asistieron puntualmente a la clase</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-check fa-2x text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ausente -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Ausente
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="contador-ausente">0</span>
                            </div>
                            <div class="text-xs text-muted">Estudiantes que no asistieron a la clase</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-x fa-2x text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tardanza -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tardanza
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="contador-tardanza">0</span>
                            </div>
                            <div class="text-xs text-muted">Estudiantes que llegaron tarde a la clase</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-time-five fa-2x text-gray-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buscador -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bx bx-search"></i>
                        </span>
                        <input type="text" id="busqueda" class="form-control" placeholder="Buscar estudiante por nombre o código...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista estudiantes -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bx bx-list-check me-2"></i>Lista de Estudiantes
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form-asistencia">
                        @csrf
                        <div id="lista-estudiantes">
                            <div class="text-center py-4">
                                <i class="bx bx-user-plus fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">Seleccione un curso para ver la lista de estudiantes</p>
                            </div>
                        </div>

                        <!-- Botón guardar -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-outline-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i> Guardar Asistencia
                            </button>
                        </div>
                    </form>
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
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
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
    .fa-3x {
        font-size: 3em;
    }
    .text-gray-800 {
        color:rgb(12, 13, 14) !important;
    }
    .text-gray-300 {
        color: #dddfeb !important;
    }
    .text-danger {
        color: #e74a3b !important;
    }
    .btn-outline-success:checked + label,
    .btn-outline-success.active {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
    .btn-outline-warning:checked + label,
    .btn-outline-warning.active {
        background-color: #f6c23e;
        border-color: #f6c23e;
    }
    .btn-outline-danger:checked + label,
    .btn-outline-danger.active {
        background-color: #e74a3b;
        border-color: #e74a3b;
    }
    .estudiante-card {
        transition: all 0.3s ease;
    }
    .estudiante-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2) !important;
    }
</style>

<script>
const cursoSelect = document.getElementById('curso');
const listaContainer = document.getElementById('lista-estudiantes');
const totalSpan = document.getElementById('total-estudiantes');
const contPresente = document.getElementById('contador-presente');
const contAusente = document.getElementById('contador-ausente');
const contTardanza = document.getElementById('contador-tardanza');

cursoSelect.addEventListener('change', () => {
    const idCurso = cursoSelect.value;
    if (!idCurso) {
        listaContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="bx bx-user-plus fa-3x text-gray-300 mb-3"></i>
                <p class="text-muted">Seleccione un curso para ver la lista de estudiantes</p>
            </div>
        `;
        return;
    }

    fetch(`/docente/asistencias/estudiantes/${idCurso}`)
        .then(res => res.json())
        .then(data => {
            const estudiantes = data.estudiantes;
            totalSpan.textContent = estudiantes.length;
            contPresente.textContent = 0;
            contAusente.textContent = 0;
            contTardanza.textContent = 0;

            listaContainer.innerHTML = estudiantes.map(e => `
                <div class="card mb-3 estudiante-card border-left-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-1">${e.nombre} ${e.apellido}</h6>
                                        <small class="text-muted">${e.codigo} • ${e.correo}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
    <input type="hidden" name="estudiantes[]" value="${e.id_usuario}">
    <div class="btn-group" role="group">
        <input type="radio" class="btn-check estado" name="asistencias[${e.id_usuario}]" id="presente-${e.id_usuario}" value="presente" checked onchange="actualizarContador()">
        <label class="btn btn-outline-success px-3 py-1 small" for="presente-${e.id_usuario}">
            <i class="fas fa-check me-1"></i> Presente
        </label>

        <input type="radio" class="btn-check estado" name="asistencias[${e.id_usuario}]" id="tardanza-${e.id_usuario}" value="tardanza" onchange="actualizarContador()">
        <label class="btn btn-outline-warning px-3 py-1 small" for="tardanza-${e.id_usuario}">
            <i class="fas fa-clock me-1"></i> Tardanza
        </label>

        <input type="radio" class="btn-check estado" name="asistencias[${e.id_usuario}]" id="ausente-${e.id_usuario}" value="ausente" onchange="actualizarContador()">
        <label class="btn btn-outline-danger px-3 py-1 small" for="ausente-${e.id_usuario}">
            <i class="fas fa-times me-1"></i> Ausente
        </label>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
            `).join('');

            actualizarContador();
        });
});

function actualizarContador() {
    const presentes = document.querySelectorAll('.estado[value="presente"]:checked').length;
    const ausentes = document.querySelectorAll('.estado[value="ausente"]:checked').length;
    const tardanzas = document.querySelectorAll('.estado[value="tardanza"]:checked').length;
    contPresente.textContent = presentes;
    contAusente.textContent = ausentes;
    contTardanza.textContent = tardanzas;
}

document.getElementById('form-asistencia').addEventListener('submit', function (e) {
    e.preventDefault();
    const idCurso = cursoSelect.value;
    const fecha = document.getElementById('fecha').value;
    const formData = new FormData(this);
    formData.append('fecha', fecha);

    fetch(`/docente/asistencias/curso/${idCurso}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(res => res.json())
    .then(res => {
        alert('✅ Asistencia guardada correctamente.');
    })
    .catch(err => {
        alert('❌ Error al guardar asistencia.');
        console.error(err);
    });
});
</script>
@endsection