@extends('layouts.menu')

@section('content')
<div>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <button onclick="openModal(null)">Nuevo curso</button>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Código</th>
                <th>Docente</th>
                <th>Estudiantes</th>
                <th>Créditos</th>
                <th>Horario</th>
                <th>Acciones</th>
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
                            <p>{{ $horario->dia_semana }} - {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</p>
                        @endforeach
                    </td>
                    <td>
                        <button onclick="openModal({{ $curso->id_curso }})">Editar</button>
                        <button onclick="window.location.href='{{ route('cursos.gestionarEstudiantes', $curso->id_curso) }}'">Gestionar Estudiantes</button>
                        <form method="POST" action="{{ route('cursos.destroy', $curso->id_curso) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este curso?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No hay cursos</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal para agregar o editar un curso -->
<div id="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: white; margin: 2% auto; padding: 20px; width: 90%; max-width: 800px; border-radius: 5px; max-height: 90%; overflow-y: auto;">
        <h3 id="modalTitle">Agregar Curso</h3>
        <form id="cursoForm" method="POST" action="{{ route('cursos.store') }}">
            @csrf
            <input type="hidden" id="method" name="_method" value="POST">
            <input type="hidden" id="curso_id" name="curso_id">

            <div>
                <label for="nombre_curso">Nombre del curso:</label>
                <input type="text" id="nombre_curso" name="nombre_curso" placeholder="Nombre del curso" required>
            </div>

            <div>
                <label for="codigo_curso">Código del curso:</label>
                <input type="text" id="codigo_curso" name="codigo_curso" placeholder="Código del curso" required>
            </div>

            <div>
                <label for="docente_id">Docente:</label>
                <select id="docente_id" name="docente_id" required>
                    <option value="">Seleccione un docente</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id_usuario }}" id="docenteOption_{{ $docente->id_usuario }}">{{ $docente->nombre }} {{ $docente->apellido }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="creditos">Créditos:</label>
                <input type="number" id="creditos" name="creditos" placeholder="Créditos" required min="1">
            </div>

            <div>
                <label for="descripcion">Descripción (opcional):</label>
                <textarea id="descripcion" name="descripcion" placeholder="Descripción del curso"></textarea>
            </div>

            <!-- Sección de Estudiantes -->
            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 10px;">Estudiantes del curso:</label>
                <div>
                    @foreach($estudiantes as $estudiante)
                        <div>
                            <label>
                                <input type="checkbox" name="estudiantes[]" value="{{ $estudiante->id_usuario }}" class="estudiante-checkbox">
                                {{ $estudiante->nombre }} {{ $estudiante->apellido }} ({{ $estudiante->correo }})
                            </label>
                        </div>
                    @endforeach
                </div>
                <div>
                    <button type="button" onclick="seleccionarTodosEstudiantes()">Seleccionar Todos</button>
                    <button type="button" onclick="deseleccionarTodosEstudiantes()">Deseleccionar Todos</button>
                </div>
            </div>

            <!-- Sección de Horarios -->
            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 10px;">Horarios del curso:</label>
                <div id="horariosContainer">
                    <!-- Los horarios se agregarán dinámicamente aquí -->
                </div>
                <button type="button" onclick="agregarHorario()">Agregar Horario</button>
            </div>

            <div>
                <button type="button" onclick="closeModal()">Cancelar</button>
                <button type="submit">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para gestionar estudiantes -->
<div id="modalEstudiantes" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: white; margin: 5% auto; padding: 20px; width: 80%; max-width: 600px; border-radius: 5px; max-height: 80%; overflow-y: auto;">
        <h3 id="modalEstudiantesTitle">Gestionar Estudiantes del Curso</h3>
        <div id="contenidoEstudiantes">
            <!-- El contenido se cargará dinámicamente -->
        </div>
        <div>
            <button type="button" onclick="cerrarModalEstudiantes()">Cerrar</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let horarioIndex = 0;

    function openModal(cursoId) {
        const modal = document.getElementById('modal');
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
                    
                    modal.style.display = 'block';
                })
                .catch(() => alert('Error al cargar los datos del curso'));
        } else {
            // Modo creación
            modalTitle.textContent = 'Agregar Curso';
            form.action = '/cursos';
            methodInput.value = 'POST';
            form.reset();
            document.getElementById('curso_id').value = '';
            modal.style.display = 'block';
        }
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
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
        const modal = document.getElementById('modalEstudiantes');
        const contenido = document.getElementById('contenidoEstudiantes');
        
        fetch(`/cursos/${cursoId}/estudiantes`)
            .then(response => response.text())
            .then(html => {
                contenido.innerHTML = html;
                modal.style.display = 'block';
            })
            .catch(() => alert('Error al cargar los estudiantes del curso'));
    }

    function cerrarModalEstudiantes() {
        document.getElementById('modalEstudiantes').style.display = 'none';
    }

    function agregarHorario(diaSeleccionado = '', horaInicio = '', horaFin = '') {
        const container = document.getElementById('horariosContainer');
        const horarioDiv = document.createElement('div');
        
        horarioDiv.innerHTML = `
            <div>
                <div>
                    <label>Día de la semana:</label>
                    <select name="horarios[${horarioIndex}][dia_semana]">
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
                <div>
                    <label>Hora inicio:</label>
                    <input type="time" name="horarios[${horarioIndex}][hora_inicio]" value="${horaInicio}">
                </div>
                <div>
                    <label>Hora fin:</label>
                    <input type="time" name="horarios[${horarioIndex}][hora_fin]" value="${horaFin}">
                </div>
                <div>
                    <button type="button" onclick="eliminarHorario(this)">Eliminar</button>
                </div>
            </div>
        `;
        
        container.appendChild(horarioDiv);
        horarioIndex++;
    }

    function eliminarHorario(button) {
        button.closest('div').parentNode.remove();
    }
</script>
@endpush
