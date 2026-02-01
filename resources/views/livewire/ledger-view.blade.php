<div>
    <div class="d-flex justify-content-between mb-3">
        <div>
            <h1 class="m-0">Libro Mayor</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Libro Mayor</strong>
            </h6>
        </div>
        <div>
            <div class="input-group">
                <button wire:click="export" class="btn btn-secondary"><i class="nf nf-cod-file_pdf"></i> Exportar</button>
                <!-- <button class="btn btn-secondary"><i class="nf nf-md-file_excel"></i> Excel</button> -->
                <!-- <button class="btn btn-secondary"><i class="nf nf-fa-print"></i> Imprimir</button> -->
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <span for="">Cantidad:</span>
            <input type="number" class="form-control" style="width: 100px;" wire:model.live="q">
            <span>/ {{ $all }}</span>
        </div>
        <div class="d-flex align-items-center mb-3" style="gap: 0.5rem; justify-content: center;">
            <input type="date" class="form-control" wire:model="filterStartDate">
            <span style="margin: 0 0.5rem;">a</span>
            <input type="date" class="form-control" wire:model="filterEndDate">
            <button class="btn btn-primary" wire:click="ir">Ir</button>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th>Descripción</th>
                <th>Contrato</th>
                <th>Fondo</th>
                <th>Cuenta</th>
            </tr>
            <tr>
                <th><input type="text" class="form-control" placeholder="Buscar fecha" wire:model.live="filterDate">
                </th>
                <th><input type="text" class="form-control" placeholder="Buscar ingreso"
                        wire:model.live="filterIncome"></th>
                <th><input type="text" class="form-control" placeholder="Buscar egreso"
                        wire:model.live="filterExpense"></th>
                <th><input type="text" class="form-control" placeholder="Buscar descripción"
                        wire:model.live="filterDescription"></th>
                <th><input type="text" class="form-control" placeholder="Buscar contrato"
                        wire:model.live="filterContract"></th>
                <th><input type="text" class="form-control" placeholder="Buscar fondo" wire:model.live="filterFondo">
                </th>
                <th><input type="text" class="form-control" placeholder="Buscar cuenta"
                        wire:model.live="filterAccount"></th>
            </tr>
        </thead>
        @php
            $t_income = 0;
            $t_expense = 0;
        @endphp
        @foreach ($data0 ?? [] as $item)
            @php
                $t_income += $item->type == 1 ? $item->amount : 0;
                $t_expense += $item->type == 2 ? $item->amount : 0;
            @endphp
            <tr onclick="edit({{ $item->id }})" class="item-table">
                <td>{{ Carbon\Carbon::parse($item->date)->toDateString() }}</td>
                <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                </td>
                <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                </td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->contract->cod ?? '' }}</td>
                <td>{{ __('messages.' . $item->assigned->name) }}</td>
                <td>{{ $item->account->name ?? '' }}</td>
            </tr>
        @endforeach
        <tfoot>
            <tr>
                <th><input type="text" class="form-control" placeholder="Buscar fecha" wire:model.live="filterDate">
                </th>
                <th><input type="text" class="form-control" placeholder="Buscar ingreso"
                        wire:model.live="filterIncome"></th>
                <th><input type="text" class="form-control" placeholder="Buscar egreso"
                        wire:model.live="filterExpense"></th>
                <th><input type="text" class="form-control" placeholder="Buscar descripción"
                        wire:model.live="filterDescription"></th>
                <th><input type="text" class="form-control" placeholder="Buscar contrato"
                        wire:model.live="filterContract"></th>
                <th><input type="text" class="form-control" placeholder="Buscar fondo" wire:model.live="filterFondo">
                </th>
                <th><input type="text" class="form-control" placeholder="Buscar cuenta"
                        wire:model.live="filterAccount"></th>
            </tr>
            <tr>
                <th>Fecha</th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th>Descripción</th>
                <th>Contrato</th>
                <th>Fondo</th>
                <th>Cuenta</th>
            </tr>
            <tr>
                <th style="text-align:right">Totales:</th>
                <th>{{ Illuminate\Support\Number::format($t_income, precision: 2) }} Bs</th>
                <th>{{ Illuminate\Support\Number::format($t_expense, precision: 2) }} Bs</th>
                <th colspan="4"></th>
            </tr>
            <tr>
                <th style="text-align:right">Patrimonio:</th>
                <th colspan="2" id="total-diferencia">
                    {{ Illuminate\Support\Number::format($t_income - $t_expense, precision: 2) }} Bs</th>
                <th colspan="4"></th>
            </tr>
        </tfoot>
    </table>
    {{ $data0->links() }}
</div>
