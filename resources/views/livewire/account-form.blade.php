<div>
    <div class="modal-body">
        <label for="alias">Nombre</label>
        <input type="text" wire:model="name" class="form-control">
        @error('name')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    @can('config-permission', 3)
        @if ($account->id == null)
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="save">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @else
            <hr>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary me-1" wire:click="save">Guardar</button>
                <button class="btn btn-danger" wire:click="remove">Eliminar</button>
            </div>
        @endif
    @endcan

</div>

@script
    <script>
        if ($wire.status == 1) {
            $wire.on('active', () => {
                Swal.fire({
                    icon: 'question',
                    title: 'Cuenta Eliminada?...',
                    text: 'Esta Cuenta Fue Eliminada, Desea recuperala?',
                    showCancelButton: true,
                    confirmButtonColor: '#f7b924',
                    cancelButtonColor: 'red',
                    confirmButtonText: 'recuperar',
                    cancelButtonText: 'cancelar'
                }).then(result => {
                    if (result.isConfirmed) $wire.dispatch('restore');
                })
            });
        }
    </script>
@endscript
