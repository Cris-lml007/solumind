<div>
    <div class="modal-body">
        <h6><strong>Información Personal</strong></h6>
        <div class="d-flex">
            <div style="width: 50%;">
                <label for="ci">CI</label>
                <input type="number" name="ci" class="form-control mb-1" placeholder="Ingrese CI" wire:model.live="ci">
                <div class="text-danger" style="height: 20px;">
                    @error('ci')
                        {{ $message }}
                    @enderror
                </div>
                <label for="email">Correo</label>
                <input type="email" name="email" class="form-control mb-1" placeholder="Ingrese correo"
                    wire:model="email">
                <div class="text-danger" style="height: 20px;">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div style="width: 50%; margin-left: 10px;">
                <label for="name">Nombre Completo</label>
                <input type="text" name="name" class="form-control mb-1" placeholder="Ingrese nombre completo"
                    wire:model="name">
                <div class="text-danger" style="height: 20px;">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
                <label for="phone">Celular</label>
                <input type="tel" name="phone" class="form-control mb-1" placeholder="Ingrese número de celular"
                    wire:model="phone">
                <div class="text-danger" style="height: 20px;">
                    @error('phone')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <h5 class="col-12">Información de Empresa (Opcional)</h5>
        <div class="d-flex">
            <div class="w-50">
                <label for="nit">NIT</label>
                <input type="text" class="form-control mb-1" wire:model="nit" name="nit">
                <div class="text-danger" style="height: 20px;">
                    @error('nit')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="w-50 ms-1">
                <label for="organization">Nombre de Organización</label>
                <input wire:model.defer="organization" type="text" class="form-control mb-1"
                    placeholder="Ingrese nombre de organización" name="organization">
                <div class="text-danger" style="height: 20px;">
                    @error('organization')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="d-flex">
            <div class="w-50">
                <label for="email">Correo</label>
                <input type="email" name="email" class="form-control mb-3" placeholder="Ingrese correo"
                    wire:model="bussiness_email">
            </div>
            <div class="w-50 ms-1">
                <label for="phone">Celular</label>
                <input type="tel" name="phone" class="form-control mb-3" placeholder="Ingrese número de celular"
                    wire:model="bussiness_phone">
            </div>
        </div>
    </div>

    @can('client-permission', 3)
        @if (!empty($client->id))
            <hr class="w-100">
            <div class="d-flex justify-content-end my-3">
                <button class="btn btn-primary me-1" wire:click="save">Modificar</button>
                <button class="btn btn-danger" id="btn-remove">Eliminar</button>
            </div>
        @else
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="save">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @endif
    @endcan
</div>

@script
    <script>
        if ($wire.status == 1) {
            document.getElementById('btn-remove').addEventListener('click', () => {
                Swal.fire({
                    title: 'Esta Seguro?...',
                    text: 'Este proceso borrara este registro logicamente, pero aun se podra recuperar',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#F7B924",
                    cancelButtonColor: "red",
                    confirmButtonText: "Si, deseo borrar!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) $wire.dispatch('remove');
                })
            });
        }
    </script>
@endscript
