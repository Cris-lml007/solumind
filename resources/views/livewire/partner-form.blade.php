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
                    @php
                        $utotal = $item->detail_contract()->sum(DB::raw('sale_price * quantity')) - $item->detail_contract()->sum('purchase_total');
                    @endphp
                    <tr>
                        <td><strong>{{ $item->id }}</strong></td>
                        <td>{{ $item->cod }}</td>
                        <td>{{ $item->pivot->amount }}</td>
                        <td>{{ $item->pivot->interest }}</td>
                        <td>{{ App\Models\ContractPartner::find($item->pivot->id)->transactions()->sum('amount') }}
                        </td>
                        <td>{{ $utotal * ($item->pivot->interest / 100) -App\Models\ContractPartner::find($item->pivot->id)->transactions()->sum('amount') }}
                        </td>
                        <td><button data-bs-toggle="modal" data-bs-target="#modal-pay" class="btn btn-primary"
                                x-on:click="$wire.contractPartner = '{{ $item->pivot->id }}'"><i
                                    class="fa fa-money-bill"></i></button></td>
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
        <hr class="w-100">
        @can('partner-permission', 3)
            <div class="d-flex justify-content-end my-3">
                <button class="btn btn-primary me-1" wire:click="save">Modificar</button>
                <button class="btn btn-danger" id="btn-remove">Eliminar</button>
            </div>
        @endcan
    @else
        @can('partner-permission', 3)
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="save">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @endcan
    @endif

    <x-modal id="modal-pay" title="Pagar Utilidad" class="">
        <div class="modal-body">
            <label for="">Importe</label>
            <div class="input-group">
                <input type="number" class="form-control" wire:model="pay_amount">
                <span class="input-group-text">Bs</span>
            </div>
            <div class="text-danger" style="height: 20px;">
                @error('amount')
                    {{ $message }}
                @enderror
            </div>
            <label for="">Descripción</label>
            <textarea class="form-control" wire:model="pay_description"></textarea>
            <div class="text-danger" style="height: 20px;">
                @error('description')
                    {{ $message }}
                @enderror
            </div>
        </div>
        @can('partner-permission', 3)
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="payUtility">Pagar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @endcan
    </x-modal>
</div>

@script
    <script>
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
