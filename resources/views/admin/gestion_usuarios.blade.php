@extends('layouts.menu')

@section('content')
<div>
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <div>
        <h5>Usuarios:</h5>
        <ul>
            <li><strong>Total:</strong> {{ $total }}</li>
            <li><strong>Administradores:</strong> {{ $totalAdmin }}</li>
            <li><strong>Docentes:</strong> {{ $totalDocente }}</li>
            <li><strong>Estudiantes:</strong> {{ $totalEstudiante }}</li>
        </ul>
    </div>



    <button onclick="openModal(null)">Nuevo usuario</button>

    <form method="GET" action="{{ route('usuarios.index') }}" id="filterForm">
        <select name="rol" onchange="document.getElementById('filterForm').submit()">
            <option value="">Todos</option>
            <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
            <option value="docente" {{ request('rol') == 'docente' ? 'selected' : '' }}>Docente</option>
            <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
        </select>

        <input type="text" name="nombre" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
        <button type="submit">Buscar</button>
    </form>

    <table>
        <thead>
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
                        <button onclick="openModal({{ $usuario->id_usuario }})">Editar</button>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('usuarios.destroy', $usuario->id_usuario) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Desea eliminar este usuario?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">No hay usuarios</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $usuarios->links() }}
</div>

{{-- Modal manual básico --}}
<div id="modal" style="display: none;">
    <form id="userForm" method="POST">
        @csrf
        <input type="hidden" id="method" name="_method" value="POST">
        <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
        <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>
        <select id="rol" name="rol" required>
            <option value="">Rol</option>
            <option value="administrador">Administrador</option>
            <option value="docente">Docente</option>
            <option value="estudiante">Estudiante</option>
        </select>
        <input type="email" id="correo" name="correo" placeholder="Correo" required>
        <input type="password" id="contrasena" name="contrasena" placeholder="Contraseña">
        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" placeholder="Confirmar contraseña">

        <button type="button" onclick="closeModal()">Cancelar</button>
        <button type="submit">Guardar</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function openModal(usuarioId) {
        const modal = document.getElementById('modal');
        const form = document.getElementById('userForm');
        const methodInput = document.getElementById('method');
        const passwordInput = document.getElementById('contrasena');
        const confirmPasswordInput = document.getElementById('contrasena_confirmation');

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
                    passwordInput.value = '';
                    confirmPasswordInput.value = '';
                    modal.style.display = 'block';
                })
                .catch(() => alert('Error al cargar los datos'));
        } else {
            form.action = `/usuarios`;
            methodInput.value = 'POST';
            form.reset();
            modal.style.display = 'block';
        }
    }

    function closeModal() {
        document.getElementById('modal').style.display = 'none';
    }
</script>
@endpush
