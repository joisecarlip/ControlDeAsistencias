@extends('layouts.app')

@section('content')
<div>
    <h2>SISTEMA DE CONTROL DE ASISTENCIAS</h2>
    <div>
        Iniciar sesión
    </div>
    <div>
        @if (session('error'))
            <div>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required>
                @error('correo')
                    <span>{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>
                @error('contrasena')
                    <span>{{ $message }}</span>
                @enderror
            </div>

            <button type="submit">Iniciar Sesión</button>
    </div>
</div>