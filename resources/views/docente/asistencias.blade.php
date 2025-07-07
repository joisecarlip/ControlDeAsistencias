@extends('layouts.menu')

@section('content')
<div class="container">
    <h3 class="mb-4">Configuración de Clase</h3>

    <!-- Filtros -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="curso" class="form-label">Curso</label>
            <select id="curso" class="form-select">
                <option value="">Seleccione un curso</option>
                @foreach($cursos as $curso)
                    <option value="{{ $curso->id_curso }}">{{ $curso->nombre_curso }} ({{ $curso->codigo_curso }}) - {{ $curso->hora_inicio }} a {{ $curso->hora_fin }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" id="fecha" class="form-control" value="{{ date('Y-m-d') }}">
        </div>
    </div>

    <!-- Panel contadores -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded">
                <strong>Total Estudiantes</strong>
                <div id="total-estudiantes" class="fs-4">0</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-success">
                <strong>Presente</strong>
                <div id="contador-presente" class="fs-4">0</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-danger">
                <strong>Ausente</strong>
                <div id="contador-ausente" class="fs-4">0</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-warning">
                <strong>Tardanza</strong>
                <div id="contador-tardanza" class="fs-4">0</div>
            </div>
        </div>
    </div>

    <!-- Buscador -->
    <div class="mb-3">
        <input type="text" id="busqueda" class="form-control" placeholder="Buscar estudiante por nombre o código...">
    </div>

    <!-- Lista estudiantes -->
    <form id="form-asistencia">
        @csrf
        <div id="lista-estudiantes">
            <!-- Se llena por JavaScript -->
        </div>

        <!-- Botón guardar -->
        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="fas fa-save me-2"></i> Guardar Asistencia
            </button>
        </div>
    </form>
</div>

<script>
const cursoSelect = document.getElementById('curso');
const listaContainer = document.getElementById('lista-estudiantes');
const totalSpan = document.getElementById('total-estudiantes');
const contPresente = document.getElementById('contador-presente');
const contAusente = document.getElementById('contador-ausente');
const contTardanza = document.getElementById('contador-tardanza');

cursoSelect.addEventListener('change', () => {
    const idCurso = cursoSelect.value;
    if (!idCurso) return;

    fetch(`/docente/asistencias/estudiantes/${idCurso}`)
        .then(res => res.json())
        .then(data => {
            const estudiantes = data.estudiantes;
            totalSpan.textContent = estudiantes.length;
            contPresente.textContent = 0;
            contAusente.textContent = 0;
            contTardanza.textContent = 0;

            listaContainer.innerHTML = estudiantes.map(e => `
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-2 mb-md-0">
                            <strong>${e.nombre} ${e.apellido}</strong><br>
                            ${e.codigo} • ${e.correo}
                        </div>
                        <div>
                            <input type="hidden" name="estudiantes[]" value="${e.id_usuario}">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check estado" name="asistencias[${e.id_usuario}]" id="presente-${e.id_usuario}" value="presente" checked onchange="actualizarContador()">
                                <label class="btn btn-outline-success" for="presente-${e.id_usuario}">
                                    <i class="fas fa-check"></i> Presente
                                </label>

                                <input type="radio" class="btn-check estado" name="asistencias[${e.id_usuario}]" id="tardanza-${e.id_usuario}" value="tardanza" onchange="actualizarContador()">
                                <label class="btn btn-outline-warning" for="tardanza-${e.id_usuario}">
                                    <i class="fas fa-clock"></i> Tardanza
                                </label>

                                <input type="radio" class="btn-check estado" name="asistencias[${e.id_usuario}]" id="ausente-${e.id_usuario}" value="ausente" onchange="actualizarContador()">
                                <label class="btn btn-outline-danger" for="ausente-${e.id_usuario}">
                                    <i class="fas fa-times"></i> Ausente
                                </label>
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
