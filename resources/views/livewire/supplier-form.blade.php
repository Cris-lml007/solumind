<x-slot name="path_dir">Dashboard > Proveedores > {{ $nit }}</x-slot>

<div>
    <div class="{{ $is_update ? 'container' : 'modal-body' }}">
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
                <label for="cellular">Celular</label>
                <input type="tel" name="cellular" class="form-control mb-1" placeholder="Ingrese número de celular"
                    wire:model="cellular">
                <div class="text-danger" style="height: 20px;">
                    @error('cellular')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <h6><strong>Información de Empresa</strong></h6>
        <div class="d-flex">
            <div style="width: 50%;">
                <label for="nit">NIT</label>
                <input type="number" name="nit" class="form-control mb-1" placeholder="Ingrese NIT o CI"
                    wire:model="nit">
                <div class="text-danger" style="height: 20px;">
                    @error('nit')
                        {{ $message }}
                    @enderror
                </div>
                <label for="email-corporation">Correo</label>
                <input type="email" name="email-corporation" class="form-control mb-1" placeholder="Ingrese correo"
                    wire:model="business_email">
                <div class="text-danger" style="height: 20px;">
                    @error('business_email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div style="width: 50%; margin-left: 10px;">
                <label for="organization">Organización</label>
                <input type="text" name="nit" class="form-control mb-1"
                    placeholder="Ingrese numbre de organización" wire:model="business_name">
                <div class="text-danger" style="height: 20px;">
                    @error('business_name')
                        {{ $message }}
                    @enderror
                </div>
                <label for="cellular-corporation">Celular</label>
                <input type="tel" name="cellular-corporation" class="form-control mb-1"
                    placeholder="Ingrese número de celular" wire:model="business_cellular">
                <div class="text-danger" style="height: 20px;">
                    @error('business_cellular')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @can('supplier-permission', 3)
        @if ($is_update)
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
        if ($wire.is_update == true) {
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
