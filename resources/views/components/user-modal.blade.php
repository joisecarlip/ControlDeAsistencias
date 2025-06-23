<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" method="POST">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>

                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Tipo de Usuario <span class="text-danger">*</span></label>
                        <select class="form-select" id="rol" name="rol" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="administrador">Administrador</option>
                            <option value="docente">Docente</option>
                            <option value="estudiante">Estudiante</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo electrónico <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>

                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña <span class="text-danger" id="password-required">*</span></label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena">
                        <div class="form-text">Mínimo 8 caracteres</div>
                    </div>

                    <div class="mb-3">
                        <label for="contrasena_confirmation" class="form-label">Confirmar contraseña <span class="text-danger" id="confirm-password-required">*</span></label>
                        <input type="password" class="form-control" id="contrasena_confirmation" name="contrasena_confirmation">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
