<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema De Asistencia</title>
    
    <!-- CSS de Bootstrap, Boxicons y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        /* Estilo personalizado para el menú lateral */
        aside {
            width: 300px;
            background-color: #007BFF;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        aside::-webkit-scrollbar {
            display: none;
        }

        aside a {
            color: #fff;
            text-decoration: none;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: background-color 0.3s;
        }

        aside a i {
            font-size: 1.8em;
        }

        aside a:hover {
            background-color: #0056b3;
        }

        main {
            margin-left: 320px;
            padding: 20px;
        }

        ul {
            list-style: none;
            padding-left: 0;
        }
    </style>
</head>
<body>

    <!-- Menú lateral -->
    <aside>
        <nav>
            <ul>
                <li><a href="{{ route('perfil') }}"><i class="bx bx-user-circle"></i> Mi perfil </a></li>

                @php $rol = auth()->user()->rol; @endphp

                @if ($rol === 'estudiante')
                    <li><a href="{{ url('/estudiante/inicio') }}"><i class="bx bx-home-alt-2"></i> Inicio </a></li>
                    <li><a href="{{ route('estudiante.asistencias') }}"><i class="bx bx-check-circle"></i> Mis asistencias </a></li>
                    <li><a href="{{ route('estudiante.cursos') }}"><i class="bx bx-book-content"></i> Mis cursos </a></li>

                @elseif ($rol === 'docente')
                    <li><a href="{{ url('/docente/inicio') }}"><i class="bx bx-home-alt-2"></i> Inicio </a></li>
                    <li><a href="{{ route('docente.asistencias.index') }}"><i class="bx bx-pen"></i> Tomar Asistencia </a></li>
                    <li><a href="{{ route('docente.cursos') }}"><i class="bx bx-book-content"></i> Mis cursos </a></li>
                    <li><a href="{{ route('docente.reporte') }}"><i class="bx bx-bar-chart"></i> Reportes </a></li>

                @elseif ($rol === 'administrador')
                    <li><a href="{{ url('/admin/inicio') }}"><i class="bx bx-home-alt-2"></i> Inicio </a></li>
                    <li><a href="{{ route('usuarios.index') }}"><i class="bx bx-user-plus"></i> Gestión de Usuarios </a></li>
                    <li><a href="{{ route('cursos.index') }}"><i class="bx bx-cube"></i> Gestión de Cursos </a></li>
                    <li><a href="{{ route('admin.reportes') }}"><i class="bx bx-bar-chart-alt-2"></i> Reportes </a></li>
                @endif

                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bx bx-log-out"></i> Cerrar sesión
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Contenido principal -->
    <main>
        <div id="app">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
