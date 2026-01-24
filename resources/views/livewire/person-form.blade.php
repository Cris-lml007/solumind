<div>
    <div class="modal-body">
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
                    wire:model.live="email">
                <div class="text-danger" style="height: 20px;">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div style="width: 50%;">
                <label for="name">Nombre Completo</label>
                <input type="text" name="name" class="form-control mb-1" placeholder="Ingrese nombre completo"
                    wire:model.live="name">
                <div class="text-danger" style="height: 20px;">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
                <label for="cellular">Celular</label>
                <input type="tel" name="cellular" class="form-control mb-1" placeholder="Ingrese número de celular"
                    wire:model.live="phone">
                <div class="text-danger" style="height: 20px;">
                    @error('phone')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @can('config-permission', 3)
        @if ($person->id == null)
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="save">Guargar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @else
            <hr>
            <div class="d-flex justify-content-end my-3">
                <button wire:click="save" class="btn btn-primary">Guargar</button>
                <button id="btn-remove" class="ms-1 btn btn-danger">Eliminar</button>
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
