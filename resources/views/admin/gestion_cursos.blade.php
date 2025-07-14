@extends('layouts.menu')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-dark mb-1" style="font-family: 'Poppins', sans-serif;">Gestión de Cursos</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cursos</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cursos->count() }}</div>
                    </div>
                    <i class="bx bx-book fa-2x text-gray-300"></i>
                </div>
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
            <button type="submit" class="btn btn-outline-primary w-100"> <i class="bx bx-search"></i> Buscar</button>
        </div>
    </form>

    <!-- Tarjetas de cursos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado de Cursos</h6>
                </div>
                <div class="card-body">
                    @if($cursos->count() > 0)
                        <div class="row">
                            @foreach($cursos as $curso)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card border-left-success h-100 shadow">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="font-weight-bold text-dark mb-0">{{ $curso->nombre_curso }}</h6>
                                                <span class="badge badge-outline-primary">{{ $curso->creditos }} créditos</span>
                                            </div>
                                            <p class="text-muted small mb-1">Código: {{ $curso->codigo_curso }}</p>

                                            @if($curso->horarios && $curso->horarios->count())
                                                <div class="text-xs text-muted mb-2">
                                                    @foreach($curso->horarios as $horario)
                                                        <div>
                                                            {{ $horario->dia_semana }}: 
                                                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('g:i A') }} - 
                                                            {{ \Carbon\Carbon::parse($horario->hora_fin)->format('g:i A') }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <p class="text-sm text-gray-600 mb-3">
                                                {{ Str::limit($curso->descripcion, 80) }}
                                            </p>

                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <div class="text-xs text-muted">Estudiantes</div>
                                                    <div class="font-weight-bold">{{ $curso->estudiantes->count() }}</div>
                                                </div>
                                            </div>

                                            <div class="mt-3 d-flex justify-content-end gap-2">
                                                <button class="btn btn-outline-info btn-sm"
                                                        onclick="window.location.href='{{ route('cursos.gestionarEstudiantes', $curso->id_curso) }}'">
                                                    <i class="fa fa-users"></i>
                                                </button>

                                                <button class="btn btn-outline-primary btn-sm" onclick="openModal({{ $curso->id_curso }})">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </button>

                                                <form method="POST"
                                                    action="{{ route('cursos.destroy', $curso->id_curso) }}"
                                                    onsubmit="return confirm('¿Eliminar este curso?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm">
                                                        <i class="bx bx-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-book fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No tienes cursos registrados</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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

<style>
    .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
    .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
    .text-xs { font-size: .75rem; }
    .badge-outline-primary {
        color: #4e73df;
        border: 1px solid #4e73df;
        background-color: transparent;
    }
</style>

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

    function filtrarCursos() {
        const nombreBusqueda = document.querySelector('input[name="nombre"]').value.toLowerCase().trim();
        const codigoBusqueda = document.querySelector('input[name="codigo"]').value.toLowerCase().trim();
        const cursoCards = document.querySelectorAll('.col-md-6.col-lg-4.mb-4'); // Seleccionar las tarjetas de cursos
        
        let cursosVisibles = 0;
        
        cursoCards.forEach(card => {
            const nombreCurso = card.querySelector('h6.font-weight-bold').textContent.toLowerCase();
            const codigoCurso = card.querySelector('p.text-muted.small').textContent.toLowerCase();
            
            const coincideNombre = nombreBusqueda === '' || nombreCurso.includes(nombreBusqueda);
            const coincideCodigo = codigoBusqueda === '' || codigoCurso.includes(codigoBusqueda);
            
            if (coincideNombre && coincideCodigo) {
                card.style.display = 'block';
                cursosVisibles++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Mostrar mensaje si no hay resultados
        mostrarMensajeSinResultados(cursosVisibles);
    }

    function mostrarMensajeSinResultados(cursosVisibles) {
        const cardBody = document.querySelector('.card-body');
        let mensajeSinResultados = document.getElementById('mensaje-sin-resultados');
        
        if (cursosVisibles === 0) {
            if (!mensajeSinResultados) {
                mensajeSinResultados = document.createElement('div');
                mensajeSinResultados.id = 'mensaje-sin-resultados';
                mensajeSinResultados.className = 'text-center py-4';
                mensajeSinResultados.innerHTML = `
                    <i class="bx bx-search fa-3x text-gray-300 mb-3"></i>
                    <p class="text-muted">No se encontraron cursos que coincidan con tu búsqueda</p>
                `;
                cardBody.appendChild(mensajeSinResultados);
            }
            mensajeSinResultados.style.display = 'block';
        } else {
            if (mensajeSinResultados) {
                mensajeSinResultados.style.display = 'none';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const nombreInput = document.querySelector('input[name="nombre"]');
        const codigoInput = document.querySelector('input[name="codigo"]');
        
        // Búsqueda en tiempo real mientras se escribe
        nombreInput.addEventListener('input', filtrarCursos);
        codigoInput.addEventListener('input', filtrarCursos);
        
        // También mantener la funcionalidad del botón de búsqueda
        const formBusqueda = document.querySelector('form[method="GET"]');
        formBusqueda.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevenir envío del formulario
            filtrarCursos(); // Usar la búsqueda en tiempo real
        });
    });
</script>
@endpush