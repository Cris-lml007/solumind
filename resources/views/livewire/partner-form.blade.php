<x-slot name="path_dir">Dashboard > Proveedores > {{ $ci }}</x-slot>

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
                <label for="organization">Organización</label>
                <input type="text" name="organization" class="form-control mb-1"
                    placeholder="Ingrese nombre de la organización perteneciente" wire:model="organization">
                <div class="text-danger" style="height: 20px;">
                    @error('organization')
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
                <label for="organization">Cargo</label>
                <input type="text" name="post" class="form-control mb-1" placeholder="Ingrese cargo que ocupa"
                    wire:model="post">
                <div class="text-danger" style="height: 20px;">
                    @error('post')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @if (!empty($partner->id))
        <h6><strong>Tabla de Inversión</strong></h6>
        <div>
            <x-adminlte.tool.datatable id="inversions" :heads="$heads_t" :config="$config_t">
                @foreach ($data_t as $item)
                    <tr>
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
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

@script
    <script>
        $wire.on('alerta', () => {
            alert("ad");
        });
    </script>
@endscript
