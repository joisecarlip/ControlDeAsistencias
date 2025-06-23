<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de acceso</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <aside>
        <nav>
            <ul>
                <li><a href="{{ route('perfil') }}"><i></i> Mi perfil </a></li>

                @php $rol = auth()->user()->rol; @endphp

                @if ($rol === 'estudiante')
                    <li><a href="{{ url('/estudiante/inicio') }}"><i></i> Inicio </a></li>
                    <li><a href="#"><i></i> Mis asistencias </a></li>
                    <li><a href="#"><i></i> Mis cursos </a></li>

                @elseif ($rol === 'docente')
                    <li><a href="{{ url('/docente/inicio') }}"><i></i> Inicio </a></li>
                    <li><a href="#"><i></i> Tomar Asistencia </a></li>
                    <li><a href="#"><i></i> Mis cursos </a></li>
                    <li><a href="#"><i></i> Reportes </a></li>

                @elseif ($rol === 'administrador')
                    <li><a href="{{ url('/admin/inicio') }}"><i></i> Inicio </a></li>
                    <li><a href="{{ route('usuarios.index') }}"><i></i> Gestión de Usuarios </a></li>
                    <li><a href="#"><i></i> Gestión de Cursos </a></li>
                    <li><a href="#"><i></i> Reportes </a></li>

                @endif
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i></i> Cerrar sesión
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
            </ul>
        </nav>
    </aside>
    
    <main>
        <div id="app">
            @yield('content')
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @stack('scripts') 
</body>
</html>
