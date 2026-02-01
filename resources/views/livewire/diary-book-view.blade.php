<div>

    <style>
        .col-fecha {
            width: 120px;
            white-space: nowrap;
        }
    </style>


    {{-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger --}}
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Libro Diario</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Libro Diario</strong>
            </h6>
        </div>
        @can('transaction-permission', 3)
            <button data-bs-target="#modal-transaction" data-bs-toggle="modal" class="btn btn-primary"><i
                    class="fa fa-plus"></i>
                Añadir Nuevo
                Asiento</button>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <div class="d-flex justify-content-end">
                    <div class="input-group">
                        <a href="{{ route('dashboard.diary_book.pdf',$search) }}" class="btn btn-secondary"><i class="nf nf-cod-file_pdf"></i> Exportar</a>
                        {{-- <button class="btn btn-secondary"><i class="nf nf-md-file_excel"></i> Excel</button> --}}
                        {{-- <button class="btn btn-secondary"><i class="nf nf-fa-print"></i> Imprimir</button> --}}
                    </div>
                </div>
            </div>
            <livewire:table :heads="$heads" name="DiaryBook" wire:key="diarybook-table">
                @php
                    $t_income = 0;
                    $t_expense = 0;
                @endphp
                @foreach ($data as $item)
                    @php
                        $t_income += $item->type == 1 ? $item->amount : 0;
                        $t_expense += $item->type == 2 ? $item->amount : 0;
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('dashboard.diary_book.form', $item->id) }}"
                                wire:navigate>{{ $item->id }}</a>
                            {{-- {{ $item->id }} --}}
                        </td>
                        <td class="col-fecha">{{ Carbon\Carbon::parse($item->date)->toDateString() }}</td>
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
                <livewire:slot name="paginate">
                    {{ $data->links() }}
                </livewire:slot>
                <livewire:slot name="footer">
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <td class="bg-primary"><strong>{{ Illuminate\Support\Number::format($t_income, precision: 2) }}
                                Bs</strong></td>
                        <td class="bg-secondary">
                            <strong>{{ Illuminate\Support\Number::format($t_expense, precision: 2) }}
                                Bs</strong>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">SALDO EFECTIVO</th>
                        <td colspan="2" style="text-align: center;"
                            class="{{ $t_income - $t_expense >= 0 ? 'bg-success' : 'bg-danger' }}">
                            <strong>{{ Illuminate\Support\Number::format($t_income - $t_expense, precision: 2) }}
                                Bs</strong>
                        </td>
                    </tr>
                </livewire:slot>
            </livewire:table>
            <!-- <div class="d-flex justify-content-end"> -->
            <!--     <div class="d-flex align-items-center mb-2"> -->
            <!--         <button id="prev-page" class="btn btn-primary me-2">Anterior</button> -->
            <!--         <input type="number" id="page-number" min="1" value="1" -->
            <!--             style="width: 60px; text-align:center;"> -->
            <!--         <button id="next-page" class="btn btn-primary ms-2">Siguiente</button> -->
            <!--         <span id="total-pages" class="ms-2"></span> -->
            <!--     </div> -->
            <!-- </div> -->
        </div>
    </div>

    <x-modal id="modal-transaction" title="Nuevo Asiento" class="modal-xl">
        <livewire:diary-book-form>
        </livewire:diary-book-form>
    </x-modal>
</div>
