<div class="modal fade" id="cursModal" tabindex="-1" aria-labelledby="cursModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cursModalLabel">Nuevo Curso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cursForm" method="POST">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">

                    <div class="mb-3">
                        <label for="codigo_curso" class="form-label">Código <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="codigo_curso" name="codigo_curso" required>
                    </div>

                    <div class="mb-3">
                        <label for="nombre_curso" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" required>
                    </div>

                    <div class="mb-3">
                        <label for="creditos" class="form-label">Créditos <span class="text-danger">*</span></label>
                        <select name="creditos" id="creditos" required>
                            <option value="">Seleccione créditos</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Curso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
