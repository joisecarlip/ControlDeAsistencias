@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-5 rounded-4" style="width: 100%; max-width: 480px;">
        
        <div class="text-center mb-4">
            <img src="{{ asset('images/MiAsistencia-negro.png') }}" alt="Logo" class="mb-3" style="width: 60%;">
            <p class="text-muted">Inicia sesión para continuar</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="correo" class="form-label">Correo electrónico</label>
                <div class="position-relative">
                    <i class='bx bx-envelope text-primary position-absolute top-50 translate-middle-y ms-2' style="font-size: 1.8rem;"></i>
                    <input type="email" id="correo" name="correo" class="form-control ps-5" required>
                </div>
                @error('correo')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="contrasena" class="form-label">Contraseña</label>
                <div class="position-relative">
                    <i class='bx bx-lock-alt text-primary position-absolute top-50 translate-middle-y ms-2' style="font-size: 1.8rem;"></i>
                    <input type="password" id="contrasena" name="contrasena" class="form-control ps-5 pe-5" required>
                    <i class='bx bx-show text-primary position-absolute top-50 translate-middle-y end-0 me-2' id="toggle-icon" style="font-size: 1.8rem; cursor: pointer;" onclick="togglePassword()"></i>
                </div>
                @error('contrasena')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                    <i class='bx bx-log-in-circle me-2'></i> Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Boxicons -->
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script>
function togglePassword() {
    const input = document.getElementById('contrasena');
    const icon = document.getElementById('toggle-icon');
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
    } else {
        input.type = "password";
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
    }
}
</script>
@endsection
