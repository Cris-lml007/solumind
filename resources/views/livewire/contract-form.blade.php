<x-slot name="path_dir">Dashboard > Comprobantes > Proformas > {{ $code }}</x-slot>
<div>
    @if ($contract->id != null)
        @php
            $total = 0;
        @endphp
        <ul class="nav nav-tabs" id="myTab" role="tablist" wire:ignore>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-tab-pane"
                    type="button" role="tab" aria-controls="info-tab-pane"
                    aria-selected="true">Información</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-tab-pane"
                    type="button" role="tab" aria-controls="products-tab-pane"
                    aria-selected="false">Productos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="partners-tab" data-bs-toggle="tab" data-bs-target="#partners-tab-pane"
                    type="button" role="tab" aria-controls="partners-tab-pane"
                    aria-selected="false">Inversión</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab"
                tabindex="0" wire:ignore.self>
                <h5 class="mt-3"><strong>Información General</strong></h5>
                <label for="code">Codigo</label>
                <input type="text" wire:model="code" class="form-control" {{$contract->status->value > 2 ? 'disabled' : ''}}>
                <div class="text-danger" style="height: 20px;">
                    @error('code')
                        {{ $message }}
                    @enderror
                </div>
                <label>Cliente</label>
                <div class="input-group">
                    <select name="searchable-item" id="searchable-item" class="form-select"
                        wire:model.live="searchable_item" {{$contract->status->value > 2 ? 'disabled' : ''}}>
                        <option>selecione un cliente</option>
                        @foreach ($clients as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->organization . ' (' . $item->person->name . ')' }}
                            </option>
                        @endforeach
                    </select>
                    <button {{$contract->status->value > 2 ? 'disabled' : ''}} id="btn-searchable" class="btn btn-primary" style="height: 38px;"><i
                            class="fa fa-search"></i></button>
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('searchable_item')
                        {{ $message }}
                    @enderror
                </div>
                <div id="search" class="border rounded p-1 d-none">
                    <label>Buscar</label>
                    <input type="text" class="form-control mb-3" placeholder="Ingrese cliente a buscar"
                        wire:model.live="searchable">
                </div>
                <label for="description">Descripción</label>
                <textarea id="description" class="form-control mb-1" wire:model="description" {{$contract->status->value > 2 ? 'disabled' : ''}}></textarea>
                <div class="text-danger" style="height: 20px;">
                    @error('description')
                        {{ $message }}
                    @enderror
                </div>
                <div class="row mt-0 mb-3">
                    <div class="col">
                        <label>Presupuesto</label>
                        @php
                            #dd($contract->partners()->sum('amount'));
                        @endphp
                        <div class="input-group">
                            <input type="number" class="form-control"
                                value="{{ $contract->partners()->sum('amount') }}" disabled>
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                    <div class="col">
                        <label>Saldo</label>
                        <div class="input-group">
                            <input type="number"
                                class="form-control {{ $contract->partners()->sum('amount') - $contract->detail_contract()->sum('purchase_total') >= 0 ? 'bg-success' : 'bg-danger' }}"
                                value="{{ $contract->partners()->sum('amount') - $contract->detail_contract()->sum('purchase_total') }}"
                                disabled>
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                </div>
                <div>
                    <h5><strong>Utilidades</strong></h5>
                    <x-adminlte.tool.datatable id="table-utilities" :heads="['CI', 'Nombre Completo', '%', 'Utilidad (Bs)']">
                        @php
                            $utotal = $contract->detail_contract()->sum(DB::raw('sale_price * quantity')) - $contract->detail_contract()->sum('purchase_total');
                            $ptotal = 0;
                        @endphp
                        @foreach ($contract->partners as $item)
                            <tr>
                                <td>{{ $item->person->ci }}</td>
                                <td>{{ $item->person->name }}</td>
                                <td>{{ $item->pivot->interest }}</td>
                                <td>{{ $utotal * ($item->pivot->interest / 100) }}</td>
                            </tr>
                            @php
                                $ptotal += $utotal * ($item->pivot->interest / 100);
                                #dd($utotal);
                            @endphp
                        @endforeach
                        <tfoot>
                            <th colspan="3">TOTAL UTILIDAD</th>
                            <th>{{ $ptotal }} Bs</th>
                        </tfoot>
                    </x-adminlte.tool.datatable>
                </div>
                <hr>
                @if ($contract->status < 3)
                    <div class="d-flex justify-content-end my-3">
                        <button class="btn btn-success" wire:click="aprove">Aprobar</button>
                        <button class="btn btn-primary" wire:click="create">Guardar</button>
                        <button class="btn btn-danger" data-bs-dismiss="modal">Eliminar</button>
                    </div>
                @endif
            </div>
            <div class="tab-pane fade" id="products-tab-pane" role="tabpanel" aria-labelledby="products-tab"
                tabindex="0" wire:ignore.self>
                <div>
                    <div class="my-3 d-flex justify-content-between py-0">
                        <h5 class="m-0 p-0" style="align-self: center;"><strong>Detalle de Contrato</strong></h5>
                        <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary" {{$contract->status->value > 2 ? 'disabled' : ''}}><i
                                class="fa fa-plus"></i> Añadir Producto</button>
                    </div>
                    <x-adminlte.tool.datatable id="detail" :heads="$heads">
                        @foreach ($list ?? [] as $item)
                            <tr>
                                <td>{{ $item->detailable()->withTrashed()->first()->name }}</td>
                                <td>{{ $item->sale_price }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ (float) $item->sale_price * (int) $item->quantity }}</td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#modal"
                                        wire:click="loadProduct({{ $item->id }})" class="btn btn-primary" {{$contract->status->value > 2 ? 'disabled' : ''}}><i
                                            class="fa fa-pen"></i></button>
                                    <button class="btn btn-danger" wire:click="delete({{ $item->id }})" {{$contract->status->value > 2 ? 'disabled' : ''}}><i
                                            class="fa fa-trash"></i></button>
                                </td>
                                @php
                                    $total += (float) $item->sale_price * (int) $item->quantity;
                                @endphp
                            </tr>
                        @endforeach
                        <tfoot>
                            <th colspan="2"></th>
                            <th>Total</th>
                            <th>{{ $total }}</th>
                            <th></th>
                        </tfoot>
                    </x-adminlte.tool.datatable>
                </div>
            </div>
            <div class="tab-pane fade" id="partners-tab-pane" role="tabpanel" aria-labelledby="partners-tab"
                tabindex="0" wire:ignore.self>
                <div class="d-flex justify-content-between mt-3 mb-3">
                    <h5 class="my-o py-0"><strong>Detalle de Inversión</strong></h5>
                    <button data-bs-toggle="modal" data-bs-target="#modal-partner" class="btn btn-primary"><i
                            class="fa fa-plus"></i> Añadir Inversión</button>
                </div>
                <div id="table-partner">
                    <x-adminlte.tool.datatable id="partner" :heads="['CI', 'Nombre Completo', 'Inversión (Bs)', 'Interes (%)', 'Acciones']">
                        @foreach ($contract->inversions ?? [] as $item)
                            <tr>
                                <td>{{ $item->partner->person->ci }}</td>
                                <td>{{ $item->partner->person->name }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ $item->interest }}</td>
                                <td>
                                    <button class="btn btn-primary"><i class="fa fa-pen"></i></button>
                                    <button wire:click="deleteInversion({{ $item->id }})"
                                        class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>

        <x-modal id="modal-partner" title="Añadir Inversión" class="modal-lg">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="w-50">
                        <label for="">CI</label>
                        <input type="number" class="form-control" wire:model.live="partner_ci">
                        <div class="text-danger" style="height: 20px;">
                            @error('partner_ci')
                                {{ $message }}
                            @enderror
                        </div>
                        <label for="">Tipo</label>
                        <input type="text" class="form-control" wire:model="partner_type">
                        <div class="text-danger" style="height: 20px;">
                            @error('partner_type')
                                {{ $message }}
                            @enderror
                        </div>
                        <label for="">Inversión (Bs)</label>
                        <input type="number" class="form-control" wire:model="partner_amount">
                        <div class="text-danger" style="height: 20px;">
                            @error('partner_amount')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="w-50 ms-1">
                        <label for="">Nombre Completo</label>
                        <select type="number" class="form-select" wire:model.live="partner_id">
                            <option value="0">Seleccione a un socio</option>
                            @foreach ($partners as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->person->name . ' (' . $item->organization . ')' }}</option>
                            @endforeach
                        </select>
                        <div class="text-danger" style="height: 20px;">
                            @error('partner_id')
                                {{ $message }}
                            @enderror
                        </div>
                        <label for="">Interes (%)</label>
                        <input type="number" class="form-control" wire:model="partner_interest">
                        <div class="text-danger" style="height: 20px;">
                            @error('partner_interest')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <label for="">Descripción</label>
                <textarea class="form-control" wire:model="partner_description"></textarea>
                <div class="text-danger" style="height: 20px;">
                    @error('partner_description')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="btn-save-inversion">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </x-modal>



        <x-modal id="modal" class="modal-lg" title="Añadir Producto">
            <div class="modal-body">
                <h6><strong>Información de Producto</strong></h6>
                <div class="d-flex">
                    <div class="w-50">
                        <label for="product">Codigo de Producto</label>
                        <div class="input-group">
                            <input type="text" class="form-control mb-3" placeholder="Ingrese codigo de producto"
                                wire:model.live="code_product">
                            <button id="btn-searchable1" class="btn btn-primary" style="height: 38px;"><i
                                    class="fa fa-search"></i></button>
                        </div>
                        <div class="text-danger" style="height: 20px;">
                            @error('code_product')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="w-50 ms-1">
                        <label for="product">Nombre de Producto</label>
                        <input type="text" class="form-control mb-3" placeholder="Nombre de producto" disabled
                            wire:model="name_product">
                    </div>
                </div>
                <div id="search1" class="border rounded p-1 d-none mb-3" wire:ignore.self>
                    <label>Buscar</label>
                    <input type="text" class="form-control mb-3" placeholder="Ingrese producto a buscar"
                        wire:model.live="searchable_product">
                    <label for="">Producto</label>
                    <select class="form-select" wire:model.live="searchable_product_item">
                        <option>Seleccionar Producto</option>
                        @foreach ($products as $item)
                            <option value="{{ $item->cod }}">
                                {{ $item->cod .
                                    ' - ' .
                                    $item->name .
                                    ($item->supplier != null
                                        ? ' (' .
                                            ($item->supplier->organization == null ? $item->supplier->person->name : $item->supplier->organization) .
                                            ')'
                                        : '') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex">
                    <div class="col-4 ps-0 pe-0">
                        <label for="purchase-price">Precio Adquisición</label>
                        <div class="input-group">
                            <input type="number" class="form-control mb-3"
                                placeholder="Ingrese precio de Adquisición" wire:model.lazy="purchase_price">
                            <span class="input-group-text" style="height: 38px;">Bs</span>
                        </div>
                        <div class="text-danger" style="height: 20px;">
                            @error('purchase_price')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-4 ms-0 ps-1 pe-0">
                        <label for="sale-price">Precio Venta</label>
                        <div class="input-group">
                            <input type="number" class="form-control mb-3" placeholder="Ingrese precio de Venta"
                                wire:model.lazy="sale_price">
                            <span class="input-group-text" style="height: 38px;">Bs</span>
                        </div>
                        <div class="text-danger" style="height: 20px;">
                            @error('sale_price')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-4 pe-0 ms-0 ps-1">
                        <label for="sale-price">Cantidad</label>
                        <input type="number" class="form-control mb-3" placeholder="Ingrese cantidad"
                            wire:model.lazy="quantity">
                        <div class="text-danger" style="height: 20px;">
                            @error('quantity')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description">Descripción</label>
                    <textarea class="form-control" rows="4" wire:model="description_product"></textarea>
                </div>
                <h6><strong>Desglose Financiero</strong></h6>
                <div class="d-flex">
                    <div class="w-50">
                        <label for="bill">Factura</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bill" wire:model.live="bill">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="number" name="bill-value" disabled
                                style="text-align: end;"
                                value="{{ (((float) $sale_price ?? 0) * (float) ($bill ?? 0)) / 100 }}">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="operating">Gastos de Funcionamiento</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="operating" wire:model.live="operating">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="number" name="operating-value" disabled
                                value="{{ (((float) $sale_price ?? 0) * (float) ($operating ?? 0)) / 100 }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="bank">Banco</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bank" wire:model.live="bank">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="number" name="bank-value" disabled
                                value="{{ (((float) $sale_price ?? 0) * (float) ($bank ?? 0)) / 100 }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>


                        @php
                            #$subtotal = (((float) $sale_price ?? 0) * (float) ($bill ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($interest ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($operating ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($comission ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($bank ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($unexpected ?? 0)) / 100 + (float) $purchase_price ?? 0;
                        @endphp
                        <label for="bill">Costo Subtotal</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bill-value" disabled
                                value="{{ $subtotal }}" style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="bill">Costo Total de Venta</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bill-value" disabled
                                value="{{ (float) $sale_price * (int) $quantity }}" style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                    <div class="w-50 ms-1">
                        <label for="interest">Interes</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="interest" wire:model.live="interest">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="number" name="interest-value" disabled
                                value="{{ (((float) $sale_price ?? 0) * (float) ($interest ?? 0)) / 100 }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="comission">Comisión</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="comission" wire:model.live="comission">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="number" name="comission-value" disabled
                                value="{{ (((float) $sale_price ?? 0) * (float) ($comission ?? 0)) / 100 }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="unexpected">Imprevistos</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="unexpected"
                                wire:model.live="unexpected">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="number" name="unexpected-value" disabled
                                value="{{ (((float) $sale_price ?? 0) * (float) ($unexpected ?? 0)) / 100 }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>


                        <label for="bill">Costo Total de Adquisición</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bill-value" disabled
                                value="{{ $subtotal * (int) $quantity }}" style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="bill">Utilidad Total</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bill-value" disabled
                                value="{{ (float) $sale_price * (int) $quantity - (float) $subtotal * (int) $quantity }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="add">Añadir</button>
                <button data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
            </div>
        </x-modal>
    @else
        <div class="modal-body">
            <h6><strong>Información General</strong></h6>
            <label for="code">Codigo</label>
            <input type="text" wire:model="code" class="form-control">
            <div class="text-danger" style="height: 20px;">
                @error('code')
                    {{ $message }}
                @enderror
            </div>
            <label>Cliente</label>
            <div class="input-group">
                <select name="searchable-item" id="searchable-item" class="form-select"
                    wire:model.live="searchable_item">
                    <option>selecione un cliente</option>
                    @foreach ($clients as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->organization . ' (' . $item->person->name . ')' }}
                        </option>
                    @endforeach
                </select>
                <button id="btn-searchable" class="btn btn-primary" style="height: 38px;"><i
                        class="fa fa-search"></i></button>
            </div>
            <div class="text-danger" style="height: 20px;">
                @error('searchable_item')
                    {{ $message }}
                @enderror
            </div>
            <div id="search" class="border rounded p-1 d-none" wire:ignore.self>
                <label>Buscar</label>
                <input type="text" class="form-control mb-3" placeholder="Ingrese cliente a buscar"
                    wire:model.live="searchable">
            </div>
            <label for="description">Descripción</label>
            <textarea id="description" class="form-control mb-1" wire:model="description"></textarea>
            <div class="text-danger" style="height: 20px;">
                @error('description')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" wire:click="create">Guardar</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
    @endif
</div>

@script
    <script>
        Livewire.hook('morphed', ({
            el,
            component
        }) => {
            $('#partner').DataTable({
                destroy: true
            })
            $('#table-utilities').DataTable({
                destroy: true
            })
            $('#detail').DataTable({
                destroy: true
            })
        })

        document.getElementById('btn-searchable').addEventListener('click', () => {
            document.getElementById('search').classList.toggle('d-none');
        });
        document.getElementById('btn-searchable1').addEventListener('click', () => {
            document.getElementById('search1').classList.toggle('d-none');
        });

        document.getElementById('btn-save-inversion').addEventListener('click', () => {
            $wire.dispatch('saveInversion');
        });
    </script>
@endscript
