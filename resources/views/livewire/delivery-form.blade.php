<div>
    <div class="modal-body">
        <h5><strong>Información de Entrega</strong></h5>
        <div class="d-flex">
            <div class="w-50">
                <label for="">Contrato</label>
                <select name="" id="" class="form-select" wire:model.live="contract_cod"
                    @if ($edit == 1) disabled @endif>
                    <option value="null">Selecione contrato</option>
                    @foreach ($contracts as $item)
                        <option value="{{ $item->cod }}">{{ $item->cod }}</option>
                    @endforeach
                </select>
                <div class="contract_cod" style="height: 20px;">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="w-50 ms-1">
                <label for="">Fecha</label>
                <input type="date" class="form-control" wire:model="date"
                    @if ($edit == 1) disabled @endif>
                <div class="date" style="height: 20px;">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="d-flex">
            <div class="w-50">
                <label for="">Recibido por</label>
                <input type="text" class="form-control" wire:model="receiver_by"
                    @if ($edit == 1) disabled @endif>
                <div class="text-danger" style="height: 20px;">
                    @error('receiver_by')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="w-50 ms-1">
                <label for="">Importe</label>
                <div class="input-group">
                    <input type="number" class="form-control" placeholder="Ingrese importe" wire:model="amount">
                    <span class="input-group-text">Bs</span>
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('amount')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <hr>
        <h5><strong>Detalle de Entrega</strong></h5>
        <div class="mt-3">
            <div class="d-flex mb-3">
                <div class="w-50">
                    <label for="">Producto</label>
                    <select name="" id="detail_id" class="form-select" wire:model.live="detail_id"
                        @if ($edit == 1) disabled @endif>
                        <option value="0">Selecione Producto</option>
                        @foreach ($products as $item)
                            @php
                                $words = explode(
                                    ' ',
                                    $item
                                        ?->detailable()
                                        ?->withTrashed()
                                        ?->first()?->cod,
                                );
                                $s = 0;
                                foreach ($words as $word) {
                                    if (strlen($word) > 2) {
                                        $s += 3;
                                    }
                                }
                                $size = substr(
                                    $item
                                        ?->detailable()
                                        ?->withTrashed()
                                        ?->first()?->cod,
                                    $s,
                                    strlen(
                                        $item
                                            ->detailable()
                                            ->withTrashed()
                                            ->first()?->cod,
                                    ),
                                );
                            @endphp
                            <option value="{{ $item?->id }}">
                                {{ ($item?->detailable()?->withTrashed()?->first()?->name ??' ') .' - ' .$size }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-50 ms-1">
                    <label for="">Cantidad</label>
                    <div class="input-group">
                        <input type="number" class="form-control" placeholder="max: {{ $max_quantity }}"
                            wire:model="quantity" @if ($edit == 1) disabled @endif>
                        <button wire:click="add" class="btn btn-primary"
                            @if ($edit == 1) disabled @endif><i class="fa fa-plus"></i> Añadir</button>
                    </div>
                </div>
            </div>
            <table class="table table-striped border">
                <thead>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Eliminar</th>
                </thead>
                <tbody>
                    @foreach ($list as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td><button wire:click="delete({{ $item['id'] }})" class="btn btn-danger"
                                    @if ($edit == 1) disabled @endif><i
                                        class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if ($edit == 1)
        <hr>
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary" wire:click="canceled">Cobrar</button>
            <a class="btn btn-danger ms-1" href="{{ route('dashboard.delivery') }}">Cancelar</a>
        </div>
    @else
        <div class="modal-footer">
            @can('delivery-permission', 3)
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="switchCheckDefault"
                        wire:model="is_canceled">
                    <label class="form-check-label" for="switchCheckDefault">Cobrado</label>
                </div>
                <button class="btn btn-primary" wire:click="save">Guardar</button>
            @endcan
            <button data-bs-dismiss="modal" class="btn btn-secondary">Cancelar</button>
        </div>
    @endif
</div>

@script
    <script>
        let products = [];

        function deleteProduct(cod) {
            const index = products.findIndex(product => product.cod == cod);
            if (index !== -1) {
                products.splice(index, 1);
                $wire.list = products;
                $wire.$refresh();
            }
        }
    </script>
@endscript
