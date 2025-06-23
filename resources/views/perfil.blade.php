@extends('layouts.menu')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title mb-4">Bienvenido, {{ $user->nombre }} </h2>

                    <p class="card-text">
                        <strong>Nombre:</strong> {{ $user->nombre }}<br>
                        <strong>Apellido:</strong> {{ $user->apellido }}<br>
                        <strong>Correo:</strong> {{ $user->correo }}<br>
                        <strong>Rol:</strong> 
                        <span class="badge bg-info text-dark text-uppercase">
                            {{ ucfirst($user->rol) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
