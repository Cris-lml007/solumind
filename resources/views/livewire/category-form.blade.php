<div>
    <div class="modal-body">
        <div class="d-flex">
            <div class="w-50">
                <label for="alias">Identificador</label>
                <input type="text" class="form-control" wire:model.live="alias">
                <div class="text-danger" style="height: 20px;">
                    @error('alias')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="w-50 ms-1">
                <label for="alias">Nombre</label>
                <input type="text" class="form-control" wire:model.live="name">
                <div class="text-danger" style="height: 20px;">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @can('config-permission', 3)
        @if ($category->id == null)
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="save">Guardar</button>
                <button class="btn btn-sencodary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @else
            <hr>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" wire:click="save">Guardar</button>
                <button class="btn btn-danger ms-1" id="btn-remove">Eliminar</button>
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
            })
        }
    </script>
@endscript
