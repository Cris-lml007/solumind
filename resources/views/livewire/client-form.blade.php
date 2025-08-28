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
                    <label for="phone">Celular</label>
                    <input type="tel" name="phone" class="form-control mb-3"
                        placeholder="Ingrese número de celular" wire:model="phone">
                </div>
            </div>
           
        </form>
    </div>
    <div class="row">
        <h5 class="col-12">Información de Empresa (Opcional)</h5>
        <div class="form-group col-md-12">
            <input wire:model.defer="organization" type="text" class="form-control" placeholder="Ingrese nombre de organización">
            @error('organization') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
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