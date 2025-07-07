@extends('layouts.menu')

@section('content')

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-4 text-center">
        <div class="col-md-12">
            <div class="p-3 bg-light border rounded">
                <strong>Total Cursos</strong>
                <div class="fs-4">{{ $cursos->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Botón nuevo curso -->
    <button class="btn btn-primary mb-3" onclick="openModal(null)">Nuevo curso</button>

    <!-- Filtros -->
    <form method="GET" action="{{ route('cursos.index') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
        </div>
        <div class="col-md-4">
            <input type="text" name="codigo" class="form-control" placeholder="Buscar por código" value="{{ request('codigo') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-outline-primary">Buscar</button>
        </div>
    </form>

    <!-- Tabla -->
    <table class="table table-hover text-center">
        <thead class="table-primary">
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Docente</th>
                <th>Estudiantes</th>
                <th>Créditos</th>
                <th>Horario</th>
                <th>Gestionar</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cursos as $curso)
                <tr>
                    <td>{{ $curso->nombre_curso }}</td>
                    <td>{{ $curso->codigo_curso }}</td>
                    <td>{{ $curso->docente ? $curso->docente->nombre : 'Sin docente' }}</td>
                    <td>{{ $curso->estudiantes->count() }}</td>
                    <td>{{ $curso->creditos }}</td>
                    <td>
                        @foreach($curso->horarios as $horario)
                            <small>{{ $horario->dia_semana }} - {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</small><br>
                        @endforeach
                    </td>
                    <td>
                        <button class="btn btn-link p-0" onclick="window.location.href='{{ route('cursos.gestionarEstudiantes', $curso->id_curso) }}'">
                            <i class="fa fa-users text-info fs-5"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-link p-0" onclick="openModal({{ $curso->id_curso }})">
                            <i class="fa fa-pencil-alt text-primary fs-5"></i>
                        </button>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cursos.destroy', $curso->id_curso) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este curso?')" class="btn btn-link p-0">
                                <i class="bx bx-trash-alt text-danger fs-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9">No hay cursos</td></tr>
            @endforelse
        </tbody>
    </table>


</div>

<!-- Modal para agregar o editar un curso -->
<div id="modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="cursoForm" method="POST" action="{{ route('cursos.store') }}">
                @csrf
                <input type="hidden" id="method" name="_method" value="POST">
                <input type="hidden" id="curso_id" name="curso_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Nuevo Curso</h5>
                    <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="nombre_curso" name="nombre_curso" class="form-control" placeholder="Nombre del curso" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" id="codigo_curso" name="codigo_curso" class="form-control" placeholder="Código del curso" required>
                    </div>
                    <div class="mb-3">
                        <select id="docente_id" name="docente_id" class="form-select" required>
                            <option value="">Seleccione un docente</option>
                            @foreach($docentes as $docente)
                                <option value="{{ $docente->id_usuario }}" id="docenteOption_{{ $docente->id_usuario }}">{{ $docente->nombre }} {{ $docente->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="number" id="creditos" name="creditos" class="form-control" placeholder="Créditos" required min="1">
                    </div>
                    <div class="mb-3">
                        <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Descripción del curso (opcional)"></textarea>
                    </div>

                    <!-- Sección de Estudiantes -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Estudiantes del curso:</label>
                        <div class="border p-3 rounded" style="max-height: 200px; overflow-y: auto;">
                            @foreach($estudiantes as $estudiante)
                                <div class="form-check">
                                    <input class="form-check-input estudiante-checkbox" type="checkbox" name="estudiantes[]" value="{{ $estudiante->id_usuario }}" id="estudiante_{{ $estudiante->id_usuario }}">
                                    <label class="form-check-label" for="estudiante_{{ $estudiante->id_usuario }}">
                                        {{ $estudiante->nombre }} {{ $estudiante->apellido }} ({{ $estudiante->correo }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="seleccionarTodosEstudiantes()">Seleccionar Todos</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deseleccionarTodosEstudiantes()">Deseleccionar Todos</button>
                        </div>
                    </div>

                    <!-- Sección de Horarios -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Horarios del curso:</label>
                        <div id="horariosContainer" class="border p-3 rounded">
                            <!-- Los horarios se agregarán dinámicamente aquí -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="agregarHorario()">Agregar Horario</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para gestionar estudiantes -->
<div id="modalEstudiantes" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEstudiantesTitle">Gestionar Estudiantes del Curso</h5>
                <button type="button" class="btn-close" onclick="cerrarModalEstudiantes()" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="contenidoEstudiantes">
                    <!-- El contenido se cargará dinámicamente -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModalEstudiantes()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let horarioIndex = 0;

    function openModal(cursoId) {
        const modal = new bootstrap.Modal(document.getElementById('modal'));
        const form = document.getElementById('cursoForm');
        const methodInput = document.getElementById('method');
        const modalTitle = document.getElementById('modalTitle');
        
        // Limpiar horarios existentes
        document.getElementById('horariosContainer').innerHTML = '';
        horarioIndex = 0;
        
        // Limpiar checkboxes de estudiantes
        document.querySelectorAll('.estudiante-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        if (cursoId) {
            // Modo edición
            modalTitle.textContent = 'Editar Curso';
            fetch(`/cursos/${cursoId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(curso => {
                    form.action = `/cursos/${cursoId}`;
                    methodInput.value = 'PUT';
                    document.getElementById('nombre_curso').value = curso.nombre_curso;
                    document.getElementById('codigo_curso').value = curso.codigo_curso;
                    document.getElementById('docente_id').value = curso.id_docente;
                    document.getElementById('creditos').value = curso.creditos;
                    document.getElementById('descripcion').value = curso.descripcion || '';
                    
                    // Marcar estudiantes seleccionados
                    if (curso.estudiantes && curso.estudiantes.length > 0) {
                        curso.estudiantes.forEach(estudiante => {
                            const checkbox = document.querySelector(`input[name="estudiantes[]"][value="${estudiante.id_usuario}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }
                    
                    // Cargar horarios existentes
                    if (curso.horarios && curso.horarios.length > 0) {
                        curso.horarios.forEach(horario => {
                            agregarHorario(horario.dia_semana, horario.hora_inicio, horario.hora_fin);
                        });
                    }
                    
                    modal.show();
                })
                .catch(() => alert('Error al cargar los datos del curso'));
        } else {
            // Modo creación
            modalTitle.textContent = 'Nuevo Curso';
            form.action = '/cursos';
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('curso_id').value = '';
            modal.show();
        }
    }

    function closeModal() {
        const modalElement = document.getElementById('modal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide();
    }

    function seleccionarTodosEstudiantes() {
        document.querySelectorAll('.estudiante-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function deseleccionarTodosEstudiantes() {
        document.querySelectorAll('.estudiante-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }

    function gestionarEstudiantes(cursoId) {
        const modal = new bootstrap.Modal(document.getElementById('modalEstudiantes'));
        const contenido = document.getElementById('contenidoEstudiantes');
        
        fetch(`/cursos/${cursoId}/estudiantes`)
            .then(response => response.text())
            .then(html => {
                contenido.innerHTML = html;
                modal.show();
            })
            .catch(() => alert('Error al cargar los estudiantes del curso'));
    }

    function cerrarModalEstudiantes() {
        const modalElement = document.getElementById('modalEstudiantes');
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide();
    }

    function agregarHorario(diaSeleccionado = '', horaInicio = '', horaFin = '') {
        const container = document.getElementById('horariosContainer');
        const horarioDiv = document.createElement('div');

        horarioDiv.innerHTML = `
            <div class="mb-3 p-3 border rounded">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Día de la semana:</label>
                        <select name="horarios[${horarioIndex}][dia_semana]" class="form-select" required>
                            <option value="">Seleccione un día</option>
                            <option value="Lunes" ${diaSeleccionado === 'Lunes' ? 'selected' : ''}>Lunes</option>
                            <option value="Martes" ${diaSeleccionado === 'Martes' ? 'selected' : ''}>Martes</option>
                            <option value="Miércoles" ${diaSeleccionado === 'Miércoles' ? 'selected' : ''}>Miércoles</option>
                            <option value="Jueves" ${diaSeleccionado === 'Jueves' ? 'selected' : ''}>Jueves</option>
                            <option value="Viernes" ${diaSeleccionado === 'Viernes' ? 'selected' : ''}>Viernes</option>
                            <option value="Sábado" ${diaSeleccionado === 'Sábado' ? 'selected' : ''}>Sábado</option>
                            <option value="Domingo" ${diaSeleccionado === 'Domingo' ? 'selected' : ''}>Domingo</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hora inicio:</label>
                        <input type="time" name="horarios[${horarioIndex}][hora_inicio]" class="form-control" value="${horaInicio}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Hora fin:</label>
                        <input type="time" name="horarios[${horarioIndex}][hora_fin]" class="form-control" value="${horaFin}" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-link p-0" onclick="eliminarHorario(this)">
                            <i class="bx bx-trash-alt text-danger fs-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.appendChild(horarioDiv);
        horarioIndex++;
    }

    function eliminarHorario(button) {
        button.closest('.mb-3').remove();
    }
</script>
@endpush