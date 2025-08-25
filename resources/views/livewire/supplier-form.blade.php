<x-slot name="path_dir">Dashboard > Proveedores > {{ $nit }}</x-slot>

<div>
    <div class="modal-body">
        <form>
            <h6><strong>Información Personal</strong></h6>
            <div class="d-flex">
                <div style="width: 50%;">
                    <label for="ci">CI</label>
                    <input type="number" name="ci" class="form-control mb-3" placeholder="Ingrese CI"
                        wire:model.live="ci">
                    <label for="email">Correo</label>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Ingrese correo"
                        wire:model="email">
                </div>
                <div style="width: 50%; margin-left: 10px;">
                    <label for="name">Nombre Completo</label>
                    <input type="text" name="name" class="form-control mb-3" placeholder="Ingrese nombre completo"
                        wire:model="name">
                    <label for="cellular">Celular</label>
                    <input type="tel" name="cellular" class="form-control mb-3"
                        placeholder="Ingrese número de celular" wire:model="cellular">
                </div>
            </div>
            <h6><strong>Información de Empresa</strong></h6>
            <div class="d-flex">
                <div style="width: 50%;">
                    <label for="nit">NIT</label>
                    <input type="number" name="nit" class="form-control mb-3" placeholder="Ingrese NIT"
                        wire:model="nit">
                    <label for="email-corporation">Correo</label>
                    <input type="email" name="email-corporation" class="form-control mb-3"
                        placeholder="Ingrese correo" wire:model="business_email">
                    <!-- <label for="category">Categoria</label> -->
                    <!-- <select class="form-select" name="category"> -->
                    <!--     <option>Seleccione categoria</option> -->
                    <!--     <option value="Al">social</option> -->
                    <!--     <option value="WL">libros</option> -->
                    <!-- </select> -->
                </div>
                <div style="width: 50%; margin-left: 10px;">
                    <label for="organization">Organización</label>
                    <input type="text" name="nit" class="form-control mb-3"
                        placeholder="Ingrese numbre de organización" wire:model="business_name">
                    <label for="cellular-corporation">Celular</label>
                    <input type="tel" name="cellular-corporation" class="form-control mb-3"
                        placeholder="Ingrese número de celular" wire:model="business_cellular">
                </div>
            </div>
        </form>
    </div>
    @if (!empty($person->ci))
        <hr class="w-100">
        <div class="d-flex justify-content-end my-3">
            <button class="btn btn-primary me-1" wire:click="save">Modificar</button>
            <button class="btn btn-danger" wire:click="remove">Eliminar</button>
        </div>
    @else
        <div class="modal-footer">
            <button class="btn btn-primary" wire:click="save">Guardar</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
    @endif
</div>
