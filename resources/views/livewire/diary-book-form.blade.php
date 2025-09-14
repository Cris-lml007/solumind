<div>
    <div class="modal-body">
        <div class="d-flex">
            <div class="w-50">
                <label>Fecha</label>
                <input type="date" class="form-control" wire:model="date">
                <div class="text-danger" style="height: 20px;">
                    @error('date')
                        {{ $message }}
                    @enderror
                </div>
                <label>Importe</label>
                <div class="input-group">
                    <input type="number" class="form-control" placeholder="Ingrese importe" wire:model="import">
                    <span class="input-group-text">Bs</span>
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('import')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="w-50 ms-1">
                <label>Tipo</label>
                <select class="form-select" wire:model="type">
                    <option value="null">Selecione Tipo</option>
                    <option value="1">Ingreso</option>
                    <option value="2">Egreso</option>
                </select>
                <div class="text-danger" style="height: 20px;">
                    @error('type')
                        {{ $message }}
                    @enderror
                </div>
                <label>Contrato</label>
                <div class="input-group">
                    <select name="search-item" class="form-select" style="height: 38px;" wire:model="contract_id">
                        <option value="null">Seleccione contrato</option>
                        @foreach ($contracts as $item)
                            <option value="{{ $item->id }}">{{ $item->cod . ' - ' . $item->client->person->name }}
                            </option>
                        @endforeach
                    </select>
                    <button id="btn-search-contract" class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('contract_id')
                        {{ $message }}
                    @enderror
                </div>
                <div id="search-contract" class="d-none p-1 glow-border" wire:ignore.self>
                    <label for="search">Buscar</label>
                    <input type="text" name="search" class="form-control mb-3" wire:model.live="search_contract"
                        placeholder="Buscar codigo">
                </div>
            </div>
        </div>
        <label>Descripción</label>
        <textarea class="form-control" wire:model="description"></textarea>
        <div class="text-danger" style="height: 20px;">
            @error('description')
                {{ $message }}
            @enderror
        </div>
        <label>Cuenta</label>
        <select class="form-select" wire:model="account_id">
            <option>Selecione una cuenta</option>
            @foreach ($data['accounts'] as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <div class="text-danger" style="height: 20px;">
            @error('account_id')
                {{ $message }}
            @enderror
        </div>
    </div>
    @can('transaction-permission', 3)
        @if ($transaction->id == null)
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="save">Registrar</button>
                <button data-bs-dismiss="modal" class="btn btn-secondary">Cancelar</button>
            </div>
        @else
            <hr>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary me-1" id="btn-update">Actualizar</button>
                <button class="btn btn-danger" id="btn-remove">Eliminar</button>
            </div>
        @endif
    @endcan
</div>

@script
    <script>
        document.getElementById('btn-search-contract').addEventListener('click', function() {
            document.getElementById('search-contract').classList.toggle('d-none');
        });

        document.getElementById('btn-update').addEventListener('click', () => {
            Swal.fire({
                title: 'Actualizar?...',
                icon: 'warning',
                text: 'Esta seguro que desea Actualizar?, esto podria causar inconsistencias futuras.',
                inputLabel: 'ingrese su contraseña',
                input: 'password',
                confirmButtonText: 'Actualizar',
                confirmButtonColor: 'green',
                cancelButtonText: 'Cancelar',
                cancelButtonColor: 'red',
                showCancelButton: true,
                preConfirm: async (password) => {
                    return $wire.updateWithPassword(password).then(result => {
                        if (!result.success) {
                            throw new Error(result.message || 'Error al Actualizar');
                        }
                        return result;
                    }).catch(error => {
                        Swal.showValidationMessage(`Error: ${error.message}`);
                    })
                }
            });
        })

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
    </script>
@endscript
