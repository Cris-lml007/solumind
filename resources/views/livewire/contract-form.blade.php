<x-slot name="path_dir">
    Dashboard > Comprobantes > Proformas > {{ $code }}</x-slot>
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
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detail-tab-pane"
                    type="button" role="tab" aria-controls="detail-tab-pane" aria-selected="false"
                    wire:click="$refresh">Detalles</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery-tab-pane"
                    type="button" role="tab" aria-controls="delivery-tab-pane" aria-selected="false"
                    wire:click="$refresh">Entregados</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="info-tab-pane" role="tabpanel" aria-labelledby="info-tab"
                tabindex="0" wire:ignore.self>
                <h5 class="mt-3"><strong>Información General</strong></h5>
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
                <div id="search" class="glow-border p-1 d-none" wire:ignore.self>
                    <label>Buscar</label>
                    <input type="text" class="form-control mb-3" placeholder="Ingrese cliente a buscar"
                        wire:model.live="searchable">
                </div>
                <label for="description">Observación</label>
                <textarea id="description" class="form-control mb-1" wire:model="description"></textarea>
                <div class="text-danger" style="height: 20px;">
                    @error('description')
                        {{ $message }}
                    @enderror
                </div>
                <label for="">Tipo de Pago</label>
                <input type="text" wire:model="payment" class="form-control">
                <div class="text-danger" style="height: 20px;">
                    @error('payment')
                        {{ $message }}
                    @enderror
                </div>
                <label for="">Validez (Dias)</label>
                <input type="number" wire:model="valide" class="form-control">
                <div class="text-danger" style="height: 20px;">
                    @error('valide')
                        {{ $message }}
                    @enderror
                </div>
                <label for="">Plazo de Entrega (Dias)</label>
                <input type="date" wire:model="delivery" class="form-control">
                <div class="text-danger" style="height: 20px;">
                    @error('delivery')
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
                            <input type="text" class="form-control"
                                value="{{ Illuminate\Support\Number::format($contract->detail_contract()->sum('purchase_total'), precision: 2) }}"
                                disabled>
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                    <div class="col">
                        <label>Saldo</label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control {{ $contract->detail_contract()->sum('purchase_total') -$contract->transactions()->where('account_id', 2)->sum('amount') >=0? 'bg-success': 'bg-danger' }}"
                                value="{{ Illuminate\Support\Number::format($contract->detail_contract()->sum('purchase_total') -$contract->transactions()->where('type', 2)->sum('amount'),precision: 2) }}"
                                disabled>
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                </div>
                @php
                    $utotal = $contract->detail_contract()->sum(DB::raw('sale_price * quantity')) - $contract->detail_contract()->sum('purchase_total');
                    $ptotal = 0;
                    $tt = 0;
                @endphp
                <hr>
                <div class="d-flex justify-content-end my-3">
                    @can('voucher-permission', 3)
                        @if ($contract->status->value < 3)
                            <button class="btn btn-success me-1" wire:click="aprove">Aprobar</button>
                            <button class="btn btn-secondary me-1" wire:click="proofFail">Fallido</button>
                        @endif

                        @if ($contract->status->value >= 3)
                            <button class="btn btn-success me-1" wire:click="finish">Finalizar</button>
                            <button class="btn btn-secondary me-1" wire:click="contractFail">Fallido</button>
                        @endif

                        @if ($contract->id == null)
                            <button class="btn btn-primary me-1" wire:click="create">Guardar</button>
                        @else
                            <button class="btn btn-primary me-1" wire:click="save">Guardar</button>
                        @endif
                        <button class="btn btn-danger" id="btn-remove">Eliminar</button>
                    @endcan
                </div>
            </div>
            <div class="tab-pane fade" id="products-tab-pane" role="tabpanel" aria-labelledby="products-tab"
                tabindex="0" wire:ignore.self>
                <div>
                    <div class="my-3 d-flex justify-content-between py-0">
                        <h5 class="m-0 p-0" style="align-self: center;"><strong>Detalle de Contrato</strong></h5>
                        @can('voucher-permission', 3)
                            <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary"><i
                                    class="fa fa-plus"></i> Añadir
                                Producto</button>
                        @endcan
                    </div>
                    <x-adminlte.tool.datatable id="detail" :heads="$heads">
                        @foreach ($list ?? [] as $item)
                            @php
                                $words = explode(
                                    ' ',
                                    $item
                                        ->detailable()
                                        ->withTrashed()
                                        ->first()?->cod,
                                );
                                $s = 0;
                                foreach ($words as $word) {
                                    if (strlen($word) > 2) {
                                        $s += 3;
                                    }
                                }
                                $size = substr(
                                    $item
                                        ->detailable()
                                        ->withTrashed()
                                        ->first()?->cod,
                                    $s,
                                    strlen(
                                        $item
                                            ->detailable()
                                            ->withTrashed()
                                            ->first()?->cod,
                                    ),
                                );
                            @endphp
                            <tr>
                                <td><strong>{{ $item->id }}</strong></td>
                                <td>{{ $item->detailable()->withTrashed()->first()?->name .' ' .$size }}</td>
                                <td>{{ Number::format(Number::parse($item->sale_price), precision: 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ Number::format(Number::parse($item->sale_price) * (float) $item->quantity, precision: 2) }}
                                </td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#modal"
                                        wire:click="loadProduct({{ $item->id }})" class="btn btn-primary"><i
                                            class="fa fa-pen"></i></button>
                                    <button class="btn btn-danger" wire:click="delete({{ $item->id }})"
                                        {{ $contract->status->value > 2 ? 'disabled' : '' }}><i
                                            class="fa fa-trash"></i></button>
                                </td>
                                @php
                                    $total += Number::parse($item->sale_price) * (float) $item->quantity;
                                @endphp
                            </tr>
                        @endforeach
                        <tfoot>
                            <th colspan="3"></th>
                            <th>Total</th>
                            <th>{{ Illuminate\Support\Number::format($total, precision: 2) }} Bs</th>
                            <th></th>
                        </tfoot>
                    </x-adminlte.tool.datatable>
                </div>
            </div>
            <div class="tab-pane fade" id="partners-tab-pane" role="tabpanel" aria-labelledby="partners-tab"
                tabindex="0" wire:ignore.self>
                <div class="d-flex justify-content-between mt-3 mb-3">
                    <h5 class="my-o py-0"><strong>Detalle de Inversión</strong></h5>
                    @can('voucher-permission', 3)
                        <button data-bs-toggle="modal" data-bs-target="#modal-partner" class="btn btn-primary"><i
                                class="fa fa-plus"></i> Añadir Inversión</button>
                    @endcan
                </div>
                <div id="table-partner">
                    <x-adminlte.tool.datatable id="partner" :heads="['ID', 'CI', 'Nombre Completo', 'Interes (%)', 'Acciones']">
                        @foreach ($contract->inversions ?? [] as $item)
                            <tr>
                                <td><strong>{{ $item->id }}</strong></td>
                                <td>{{ $item->partner->person->ci }}</td>
                                <td>{{ $item->partner->person->name }}</td>
                                <!-- <td>{{ $item->amount }}</td> -->
                                <td>{{ $item->interest }}</td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#modal-partner"
                                        class="btn btn-primary" wire:click="loadInversion({{ $item->id }})"><i
                                            class="fa fa-pen"></i></button>
                                    @can('voucher-permission', 3)
                                        <button wire:click="deleteInversion({{ $item->id }})"
                                            class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>

            <div class="tab-pane fade show" id="detail-tab-pane" role="tabpanel" aria-labelledby="detail-tab"
                tabindex="0" wire:ignore.self>
                <div class="my-3">
                    @php
                        $tbill = $contract->detail_contract->sum(function ($item) {
                            return Number::parse($item->sale_price) * ((float) ($item->bill ?? 0) / 100) * (float) $item->quantity;
                        });
                        $toperating = $contract->detail_contract->sum(function ($item) {
                            return Number::parse($item->sale_price) * ((float) $item->operating / 100) * (float) $item->quantity;
                        });
                        $tcomission = $contract->detail_contract->sum(function ($item) {
                            return Number::parse($item->sale_price) * ((float) ($item->comission ?? 0) / 100) * (float) $item->quantity;
                        });
                        $tbank = $contract->detail_contract->sum(function ($item) {
                            return Number::parse($item->sale_price) * ((float) ($item->bank ?? 0) / 100) * (float) $item->quantity;
                        });
                        $tunexpected = $contract->detail_contract->sum(function ($item) {
                            return Number::parse($item->sale_price) * ((float) ($item->unexpected ?? 0) / 100) * (float) $item->quantity;
                        });
                        $tinterest = $contract->detail_contract->sum(function ($item) {
                            return Number::parse($item->purchase_price) * ((float) ($item->interest ?? 0) / 100) * (float) $item->quantity;
                        });
                        $tutility =
                            $contract->detail_contract->sum(function ($item) {
                                return Number::parse($item->sale_price) * (float) $item->quantity;
                            }) - $contract->detail_contract->sum('purchase_total');
                        $tpurchase = $contract->detail_contract()->sum('purchase_total');
                        $tsale = $contract->detail_contract()->sum(DB::raw('sale_price * quantity'));
                    @endphp
                    <div class="row">
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-money_bill"></i> Facturacion
                                        @if($contract->detail_contract()->count() != 0) ({{ $contract->detail_contract()->sum('bill') / $contract->detail_contract()->count() }}%) @endif :
                                        {{ Illuminate\Support\Number::format($tbill, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-truck"></i> Funcionamiento
                                        @if($contract->detail_contract()->count() != 0)({{ $contract->detail_contract()->sum('operating') / $contract->detail_contract()->count() }}%) @endif :
                                        {{ Illuminate\Support\Number::format($toperating, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-bookmark"></i>Comisión
                                        @if($contract->detail_contract()->count() != 0) ({{ $contract->detail_contract()->sum('comission') / $contract->detail_contract()->count() }}%) @endif :
                                        {{ Illuminate\Support\Number::format($tcomission, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-bank"></i> Banco
                                        @if($contract->detail_contract()->count() != 0) ({{ $contract->detail_contract()->sum('bank') / $contract->detail_contract()->count() }}%) @endif:
                                        {{ Illuminate\Support\Number::format($tbank, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-percent"></i> Interes
                                        @if($contract->detail_contract()->count() != 0) ({{ $contract->detail_contract()->sum('interest') / $contract->detail_contract()->count() }}%) @endif :
                                        {{ Illuminate\Support\Number::format($tinterest, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-warning"></i> Inprevistos
                                        @if($contract->detail_contract()->count() != 0) ({{ $contract->detail_contract()->sum('unexpected') / $contract->detail_contract()->count() }}%) @endif :
                                        {{ Illuminate\Support\Number::format($tunexpected, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-angles_down"></i> Total
                                        Adquisición:
                                        {{ Illuminate\Support\Number::format($tpurchase, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-md-point_of_sale"></i> Total Venta:
                                        {{ Illuminate\Support\Number::format($tsale, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h6 style="text-align: end;"><i class="nf nf-fa-angles_up"></i> Total Utilidad:
                                        {{ Illuminate\Support\Number::format($utotal, precision: 2) }} Bs</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5><strong>Utilidades</strong></h5>
                        <x-adminlte.tool.datatable id="table-utilities" :heads="['ID', 'CI', 'Nombre Completo', '%', 'Utilidad (Bs)']">
                            @foreach ($contract->partners as $item)
                                <tr>
                                    <td><strong>{{ $item->id }}</strong></td>
                                    <td>{{ $item->person->ci }}</td>
                                    <td>{{ $item->person->name }}</td>
                                    <td>{{ $item->pivot->interest }}</td>
                                    <td>{{ Illuminate\Support\Number::format($utotal * ($item->pivot->interest / 100), precision: 2) }}
                                    </td>
                                </tr>
                                @php
                                    $ptotal += $utotal * ($item->pivot->interest / 100);
                                    $tt += $item->pivot->interest;
                                    #dd($utotal);
                                @endphp
                            @endforeach
                            <tfoot>
                                <th colspan="3">TOTAL UTILIDAD</th>
                                <th>{{ Illuminate\Support\Number::percentage($tt) }}</th>
                                <th>{{ Illuminate\Support\Number::format($ptotal, precision: 2) }} Bs</th>
                            </tfoot>
                        </x-adminlte.tool.datatable>
                    </div>
                    @php
                        $ti = 0;
                        $te = 0;
                        @endphp
                        <h5 class="my-3"><strong> Movimientos Libro Diario</strong></h5>
                    <x-adminlte.tool.datatable id="table-transactions" :heads="['ID', 'Fecha', 'Ingreso (Bs)', 'Egreso (Bs)', 'Descripctión', 'Cuenta']">
                        @foreach ($transactions as $item)
                            @php
                                $ti += $item->type == 1 ? $item->amount : 0;
                                $te += $item->type == 2 ? $item->amount : 0;
                            @endphp
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->date }}</td>
                                <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                                </td>
                                <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                                </td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->account->name }}</td>
                            </tr>
                        @endforeach
                        <tfoot>
                            <tr>
                                <th colspan="2" style="text-align:right">Totales:</th>
                                <th></th> {{-- total ingresos --}}
                                <th></th> {{-- total egresos --}}
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </x-adminlte.tool.datatable>
                    <div class="card">
                        <div class="card-body  {{ $ti - $te > 0 ? 'bg-success' : 'bg-danger' }}">
                            <div><strong>Total Ganancia:</strong>
                                {{ Illuminate\Support\Number::format($ti - $te, precision: 2) }} Bs</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="delivery-tab-pane" role="tabpanel" aria-labelledby="delivery-tab"
                tabindex="0" wire:ignore.self>
                <div class="my-3">
                    <h5 class="mb-3"><strong>Productos Entregados</strong></h5>
                    <x-adminlte.tool.datatable id="table-delivery" :heads="['ID', 'Codigo', 'Nombre', 'Cantidad', 'Entregado', 'Disponible']">
                        @foreach ($contract->detail_contract ?? [] as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->detailable()->withTrashed()->first()?->cod }}</td>
                                <td>{{ $item->detailable()->withTrashed()->first()?->name .' ' .$item->detailable()->withTrashed()->first()?->size }}
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->deliveries()->sum('quantity') }}</td>
                                <td>{{ (int) $item->quantity - (int) $item->deliveries()->sum('quantity') }}</td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>

        <x-modal id="modal-partner" title="Añadir Inversión" class="">
            <div class="modal-body">
                <label for="">CI</label>
                <input type="number" class="form-control" wire:model.live="partner_ci">
                <div class="text-danger" style="height: 20px;">
                    @error('partner_ci')
                        {{ $message }}
                    @enderror
                </div>
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
                <label for="">Interes</label>
                <div class="input-group">
                    <input type="number" class="form-control" wire:model="partner_interest"
                        placeholder="Disponible: {{ 100 - $contract->inversions()->sum('interest') }} %">
                    <span class="input-group-text">%</span>
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('partner_interest')
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
                <div id="search1" class="glow-border p-1 d-none mb-3" wire:ignore.self>
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
                            <input type="text" class="form-control mb-3"
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
                            <input type="text" class="form-control mb-3" placeholder="Ingrese precio de Venta"
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
                    <label for="description">Observación</label>
                    <textarea class="form-control" rows="4" wire:model="description_product"></textarea>
                </div>
                <h6><strong>Desglose Financiero</strong></h6>
                <div class="d-flex">
                    <div class="w-50">
                        <label for="bill">Factura</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bill" wire:model.live="bill">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="text" name="bill-value" disabled
                                style="text-align: end;"
                                value="{{ Number::format(((Number::parse($sale_price) ?? 0) * (float) ($bill ?? 0)) / 100, precision: 2) }}">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="operating">Gastos de Funcionamiento</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="operating" wire:model.live="operating">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="text" name="operating-value" disabled
                                value="{{ Number::format(((Number::parse($sale_price) ?? 0) * (float) ($operating ?? 0)) / 100, precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="bank">Banco</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="bank" wire:model.live="bank">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="text" name="bank-value" disabled
                                value="{{ Number::format(((Number::parse($sale_price) ?? 0) * (float) ($bank ?? 0)) / 100, precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>


                        @php
                            #$subtotal = (((float) $sale_price ?? 0) * (float) ($bill ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($interest ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($operating ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($comission ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($bank ?? 0)) / 100 + (((float) $sale_price ?? 0) * (float) ($unexpected ?? 0)) / 100 + (float) $purchase_price ?? 0;
                        @endphp
                        <label for="bill">Costo Subtotal</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" name="bill-value" disabled
                                value="{{ Number::format($subtotal, precision: 2) }}" style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="bill">Costo Total de Venta</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" name="bill-value" disabled
                                value="{{ Number::format(Number::parse($sale_price) * (float) $quantity, precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                    <div class="w-50 ms-1">
                        <label for="interest">Interes</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="interest" wire:model.live="interest">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="text" name="interest-value" disabled
                                value="{{ Number::format(((Number::parse($sale_price) ?? 0) * (float) ($interest ?? 0)) / 100, precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="comission">Comisión</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="comission" wire:model.live="comission">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="text" name="comission-value" disabled
                                value="{{ Number::format(((Number::parse($sale_price) ?? 0) * (float) ($comission ?? 0)) / 100, precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="unexpected">Imprevistos</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="number" name="unexpected"
                                wire:model.live="unexpected">
                            <span class="input-group-text">%</span>
                            <input class="form-control" type="text" name="unexpected-value" disabled
                                value="{{ Number::format(((Number::parse($sale_price) ?? 0) * (float) ($unexpected ?? 0)) / 100, precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>


                        <label for="bill">Costo Total de Adquisición</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" name="bill-value" disabled
                                value="{{ Number::format($subtotal * (float) $quantity, precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                        <label for="bill">Utilidad Total</label>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" name="bill-value" disabled
                                value="{{ Number::format((Number::parse($sale_price) * (float) $quantity) - ((float) $subtotal * (float) $quantity), precision: 2) }}"
                                style="text-align: end;">
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="add">Añadir</button>
                <button data-bs-dismiss="modal" class="btn btn-secondary" wire:click="clearProduct">Cerrar</button>
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
            <label for="description">Observación</label>
            <textarea id="description" class="form-control mb-1" wire:model="description"></textarea>
            <div class="text-danger" style="height: 20px;">
                @error('description')
                    {{ $message }}
                @enderror
            </div>
        </div>
        @can('voucher-permission', 3)
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="create">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @endcan
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
            $('#table-delivery').DataTable({
                destroy: true
            })

            $(document).ready(function() {
                $('#table-transactions').DataTable({
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();

                        // Función para parsear a número
                        var intVal = function(i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                        };

                        // Total ingresos
                        var totalIngresos = api
                            .column(2, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Total egresos
                        var totalEgresos = api
                            .column(3, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Actualizar footer
                        $(api.column(2).footer()).html(totalIngresos.toFixed(
                            2)) //html(totalIngresos.toFixed(2));
                        $(api.column(3).footer()).html(totalEgresos.toFixed(2));
                    }
                });
            });
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
                if (result.isConfirmed) $wire.remove();
            })
        });
    </script>
@endscript
