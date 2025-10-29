<div>
    <div class="modal-body">
        <div class="">
            <div class="w-100">
                <div class="d-flex">
                    <div class="w-50">
                        <label>Fecha</label>
                        <input type="date" class="form-control" wire:model="date">
                        <div class="text-danger" style="height: 20px;">
                            @error('date')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="w-50 ms-1">
                        <label>Tipo</label>
                        <select class="form-select" wire:model.lazy="type">
                            <option value="null">Selecione Tipo</option>
                            <option value="1">Ingreso</option>
                            <option value="2">Egreso</option>
                        </select>
                        <div class="text-danger" style="height: 20px;">
                            @error('type')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100">
                <div class="d-flex">
                    <div class="w-100">
                        <label>Contrato</label>
                        <div class="input-group">
                            <select name="search-item" class="form-select" style="height: 38px;"
                                wire:model.live="contract_id">
                                <option value="null">Seleccione contrato</option>
                                @foreach ($contracts as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->cod . ' - ' . $item->client->person->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button id="btn-search-contract" class="btn btn-primary"><i
                                    class="fa fa-search"></i></button>
                        </div>
                        <div class="text-danger" style="height: 20px;">
                            @error('contract_id')
                                {{ $message }}
                            @enderror
                        </div>
                        <div id="search-contract" class="d-none p-1 glow-border" wire:ignore.self>
                            <label for="search">Buscar Contrato</label>
                            <input type="text" name="search" class="form-control mb-3"
                                wire:model.live="search_contract" placeholder="Buscar codigo">
                        </div>
                    </div>
                </div>
                <label>Asignar fondos</label>
                <div class="w-100 d-flex justify-content-center">
                    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                        <input type="radio" autocomplete="off" class="btn-check" id="btn-check-0" wire:model.lazy="assigned" value="0">
                        <label class="btn btn-outline-primary" for="btn-check-0">Ninguno</label>

                        <input type="radio" autocomplete="off" class="btn-check" id="btn-check-1" wire:model.lazy="assigned" value="1">
                        <label class="btn btn-outline-primary" for="btn-check-1">Facturación</label>

                        <input type="radio" autocomplete="off" class="btn-check" id="btn-check-2" wire:model.lazy="assigned" value="2">
                        <label class="btn btn-outline-primary" for="btn-check-2">Funcionamiento</label>

                        <input type="radio" autocomplete="off" class="btn-check" id="btn-check-3" wire:model.lazy="assigned" value="3">
                        <label class="btn btn-outline-primary" for="btn-check-3">Comisión</label>

                        <input type="radio" autocomplete="off" class="btn-check" id="btn-check-4" wire:model.lazy="assigned" value="4">
                        <label class="btn btn-outline-primary" for="btn-check-4">Banco</label>

                        <input type="radio" autocomplete="off" class="btn-check" id="btn-check-5" wire:model.lazy="assigned" value="5">
                        <label class="btn btn-outline-primary" for="btn-check-5">Interes</label>

                        <input type="radio" autocomplete="off" class="btn-check" id="btn-check-6" wire:model.lazy="assigned" value="6">
                        <label class="btn btn-outline-primary" for="btn-check-6">Imprevistos</label>
                    </div>
                </div>



                <div class="w-100">
                    <label>Importe</label>
                    <div class="input-group">
                        <input type="number"
                               placeholder="{{ App\Models\Contract::where('id', $this->contract_id)->exists() && $this->type == 2 ? 'Total: ' . Number::format($this->balance, precision: 2) . ' Bs' : 'Ingrese Importe' }}"
                               wire:model.live="import" @class([
                                                'form-control',
                                                'bg-danger' =>
                                                $this->balance - (empty($this->import) ? 0 : $this->import) < 0 &&
                               App\Models\Contract::where('id', $this->contract_id)->exists() && $this->type == 2,
                               'bg-success' =>
                               $this->balance - (empty($this->import) ? 0 : $this->import) >= 0 &&
                               App\Models\Contract::where('id', $this->contract_id)->exists(),
                               ])>
                               <span class="input-group-text">Bs</span>
                    </div>
                    <div class="text-danger" style="height: 20px;">
                        @error('import')
                        {{ $message }}
                        @enderror
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
            <div class="input-group">
                <select class="form-select" wire:model="account_id">
                    <option>Selecione una cuenta</option>
                    @foreach ($accounts as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <button id="btn-search-account" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
            <div class="text-danger" style="height: 20px;">
                @error('account_id')
                    {{ $message }}
                @enderror
            </div>
            <div id="search-account" class="d-none p-1 glow-border" wire:ignore.self>
                <label for="search">Buscar Cuenta</label>
                <input type="text" name="search" class="form-control mb-3" wire:model.live="search_account"
                    placeholder="Buscar cuenta">
            </div>

            @if (Number::parse($this->import ?? 0) > $this->balance &&
            App\Models\Contract::where('id', $this->contract_id)->exists() && $this->type == 2)
                <div class="card">
                    <div class="card-body bg-warning">
                        <i class="nf nf-cod-warning"></i> Presupuesto Superado.
                    </div>
                </div>
            @endif
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

    @script
        <script>
            document.getElementById('btn-search-contract').addEventListener('click', function() {
                document.getElementById('search-contract').classList.toggle('d-none');
            });

            document.getElementById('btn-search-account').addEventListener('click', function() {
                document.getElementById('search-account').classList.toggle('d-none');
            });

            if ($wire.status == 1) {
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
                                $wire.save();
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
            }
        </script>
    @endscript
