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
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded">
                <strong>Total Usuarios</strong>
                <div class="fs-4">{{ $total }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-primary">
                <strong>Administradores</strong>
                <div class="fs-4">{{ $totalAdmin }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-info">
                <strong>Docentes</strong>
                <div class="fs-4">{{ $totalDocente }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-3 bg-light border rounded text-success">
                <strong>Estudiantes</strong>
                <div class="fs-4">{{ $totalEstudiante }}</div>
            </div>
        </div>
    </div>


    <!-- Botón nuevo usuario -->
    <button class="btn btn-primary mb-3" onclick="openModal(null)">Nuevo usuario</button>

    <!-- Filtros -->
    <form method="GET" action="{{ route('usuarios.index') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <select name="rol" class="form-select" onchange="this.form.submit()">
                <option value="">Todos</option>
                <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="docente" {{ request('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
                <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
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
                <th>Rol</th>
                <th>Correo</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->rol }}</td>
                    <td>{{ $usuario->correo }}</td>
                    <td>
                        <button class="btn btn-link p-0" onclick="openModal({{ $usuario->id_usuario }})">
                            <i class="fa fa-pencil-alt text-primary fs-5"></i>
                        </button>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('usuarios.destroy', $usuario->id_usuario) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Desea eliminar este usuario?')" class="btn btn-link p-0">
                                <i class="bx bx-trash-alt text-danger fs-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay usuarios</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginación -->
    {{ $usuarios->links() }}
</div>

<!-- Modal -->
<div id="modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="userForm" method="POST">
                @csrf
                <input type="hidden" id="method" name="_method" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Usuario</h5>
                    <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Apellido" required>
                    </div>
                    <div class="mb-3">
                        <select id="rol" name="rol" class="form-select" required>
                            <option value="">Seleccione un rol</option>
                            <option value="administrador">Administrador</option>
                            <option value="docente">Docente</option>
                            <option value="estudiante">Estudiante</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="email" id="correo" name="correo" class="form-control" placeholder="Correo" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña">
                    </div>
                    <div class="mb-3">
                        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" class="form-control" placeholder="Confirmar contraseña">
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

@endsection

@push('scripts')
<script>
    function openModal(usuarioId) {
        const modal = new bootstrap.Modal(document.getElementById('modal'));
        const form = document.getElementById('userForm');
        const methodInput = document.getElementById('method');

        if (usuarioId) {
            fetch(`/usuarios/${usuarioId}/edit`)
                .then(response => response.json())
                .then(usuario => {
                    form.action = `/usuarios/${usuarioId}`;
                    methodInput.value = 'PUT';
                    document.getElementById('nombre').value = usuario.nombre;
                    document.getElementById('apellido').value = usuario.apellido;
                    document.getElementById('rol').value = usuario.rol;
                    document.getElementById('correo').value = usuario.correo;
                    document.getElementById('contrasena').value = '';
                    document.getElementById('contrasena_confirmation').value = '';
                    modal.show();
                })
                .catch(() => alert('Error al cargar los datos'));
        } else {
            form.action = `/usuarios`;
            methodInput.value = 'POST';
            form.reset();
            modal.show();
        }
    }

    function closeModal() {
        const modalElement = document.getElementById('modal');
        const modal = bootstrap.Modal.getInstance(modalElement);
        modal.hide();
    }
</script>
@endpush
