@extends('layouts.menu')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="text-center mb-4">
                <div class="bg-primary rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                    <i class="bx bx-user text-white" style="font-size: 3rem;"></i>
                </div>
                <h2 class="mb-1">{{ $user->nombre }} {{ $user->apellido }}</h2>
                <p class="text-muted">Información del Usuario</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="p-4 bg-light border rounded">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-user text-primary me-3" style="font-size: 2rem;"></i>
                            <div>
                                <strong class="text-muted">Nombre</strong>
                                <div class="fs-5">{{ $user->nombre }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-4 bg-light border rounded">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-user text-info me-3" style="font-size: 2rem;"></i>
                            <div>
                                <strong class="text-muted">Apellido</strong>
                                <div class="fs-5">{{ $user->apellido }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-4 bg-light border rounded">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-envelope text-success me-3" style="font-size: 2rem;"></i>
                            <div>
                                <strong class="text-muted">Correo</strong>
                                <div class="fs-5">{{ $user->correo }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="p-4 bg-light border rounded">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-shield text-warning me-3" style="font-size: 2rem;"></i>
                            <div>
                                <strong class="text-muted">Rol</strong>
                                <div class="mt-2">
                                    <span class="badge bg-secondary text-white text-uppercase px-3 py-2 fs-6">
                                        {{ ucfirst($user->rol) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <div class="p-4 bg-light border rounded">
                    <h5 class="text-primary mb-2">
                        <i class="bx bx-check-circle me-2"></i>
                        Bienvenido al Sistema
                    </h5>
                    <p class="text-muted mb-0 fs-6">
                        Sistema de Control de Asistencias para Estudiantes.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection