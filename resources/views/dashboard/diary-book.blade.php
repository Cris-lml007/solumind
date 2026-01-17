@extends('adminlte::page')
@section('title', 'Libro Diario')
@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Libro Diario</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Libro Diario</strong></h6>
        </div>
        @can('transaction-permission', 3)
            <button data-bs-target="#modal-transaction" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i>
                Añadir Nuevo
                Asiento</button>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table-diary" :heads="$heads" hoverable with-buttons>
                @php
                    $t_income = 0;
                    $t_expense = 0;
                @endphp
                @foreach ($data as $item)
                    @php
                        $t_income += $item->type == 1 ? $item->amount : 0;
                        $t_expense += $item->type == 2 ? $item->amount : 0;
                    @endphp
                    <tr onclick="edit({{ $item->id }})" class="item-table">
                        <td>{{ $item->id }}</td>
                        <td class="col-fecha">{{ Carbon\Carbon::parse($item->date)->toDateString() }}</td>
                        <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->contract->cod ?? '' }}</td>
                        <td>{{ __('messages.'.$item->assigned->name) }}</td>
                        <td>{{ $item->account->name ?? '' }}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <td class="bg-primary"><strong>{{ Illuminate\Support\Number::format($t_income, precision: 2) }}
                                Bs</strong></td>
                        <td class="bg-secondary"><strong>{{ Illuminate\Support\Number::format($t_expense, precision: 2) }}
                                Bs</strong></td>
                    </tr>
                    <tr>
                        <th colspan="2">SALDO EFECTIVO</th>
                        <td colspan="2" style="text-align: center;"
                            class="{{ $t_income - $t_expense >= 0 ? 'bg-success' : 'bg-danger' }}">
                            <strong>{{ Illuminate\Support\Number::format($t_income - $t_expense, precision: 2) }}
                                Bs</strong>
                        </td>
                    </tr>
                </tfoot>
            </x-adminlte.tool.datatable>
        </div>
    </div>

    <x-modal id="modal-transaction" title="Nuevo Asiento" class="modal-xl">
        <livewire:diary-book-form>
        </livewire:diary-book-form>
    </x-modal>
@endsection

@section('css')
    <style>
        .item-table {
            cursor: pointer;
        }
    </style>
@endsection

@section('js')
    <script>
        function edit($id) {
            window.location.replace("{{ route('dashboard.diary_book.form', 'q') }}".slice(0, -1) + $id)
            {{-- console.log("{{ route('dashboard.diary_book.form','q') }}".slice(0,-1)) --}}
        }
    </script>
@endsection


@push('js')

<!-- DataTables principal -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

<!-- Extensiones de Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>

<!-- Dependencias para exportar -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<!-- Botones de exportación -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- Plugin de paginación con input -->
<script src="https://cdn.datatables.net/plug-ins/1.13.8/pagination/input.js"></script>




<!-- DataTables actualizado -->
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css"> --}}
{{-- <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script> --}}
{{-- <script src="https://cdn.datatables.net/plug-ins/1.13.8/pagination/input.js"></script> --}}

<style>


.col-fecha {
    width: 120px;
    white-space: nowrap;
}


/* Contenedor principal de la paginación */
.dataTables_paginate {
    display: flex;
    align-items: center;
    justify-content: flex-end; /* alinea a la derecha (como DataTables por defecto) */
    flex-wrap: wrap;
    gap: 6px; /* espacio entre elementos */
    margin-top: 0.5rem;
}

/* Botones de paginación */
.page-item,
.paginate_button {
    cursor: pointer;
    background-color: #F7B924;
    color: black !important;
    border-radius: 5px;
    border: 1px solid white;
    padding: 5px 10px;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}

.paginate_button:hover:not(.disabled),
.page-item:hover:not(.disabled) {
    background-color: #ffca3a;
}

.paginate_button.disabled,
.page-item.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Input de número de página */
.paginate_input {
    width: 60px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px;
    outline: none;
    font-weight: 500;
}

.paginate_input:focus {
    border-color: #F7B924;
    box-shadow: 0 0 3px #f7b924aa;
}

/* Texto "Page", "of", etc. */
.dataTables_paginate span {
    font-weight: 500;
    color: #333;
}

/* Alineación del bloque informativo (opcional, para mantenerlo junto) */
.dataTables_info {
    margin-right: auto;
    align-self: center;
    font-size: 0.9rem;
    color: #555;
}

@media (max-width: 768px) {
    /* Contenedor principal: línea horizontal con scroll */
    .dataTables_paginate {
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-start;
        gap: 6px;
        overflow-x: auto;
        padding: 6px 0;
        scrollbar-width: thin;
        scrollbar-color: #F7B924 transparent;
    }

    /* Mejora el desplazamiento en móviles */
    .dataTables_paginate::-webkit-scrollbar {
        height: 6px;
    }
    .dataTables_paginate::-webkit-scrollbar-thumb {
        background-color: #F7B924;
        border-radius: 4px;
    }

    /* Elementos en línea */
    .dataTables_paginate span,
    .dataTables_paginate .paginate_input,
    .dataTables_paginate .paginate_button {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
    }

    /* Texto informativo arriba y centrado */
    .dataTables_info {
        text-align: center;
        width: 100%;
        margin-bottom: 8px;
        font-size: 0.85rem;
    }

    /* Ajustes visuales */
    .paginate_input {
        width: 45px;
        font-size: 0.9rem;
        padding: 4px;
    }

    .paginate_button,
    .page-item {
        font-size: 0.9rem;
        padding: 4px 8px;
    }
}
</style>

    <script>
        $(document).ready(function() {
            $('#table-diary').DataTable().destroy();

            $('#table-diary').DataTable({
                order: [0, 'desc'],
                pagingType: 'input',
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        title: '',
                        filename: 'Libro_Diario {{ now() }}',
                        orientation: 'portrait',
                        pageSize: 'A4',
                        footer: true,
                        customize: function(doc) {
                            // --- Insertar título centrado debajo del logo ---
                            doc.content.unshift({
                                text: 'LIBRO DIARIO',
                                alignment: 'center',
                                margin: [0, 10, 0, 15],
                                fontSize: 16,
                                bold: true
                            });
                            // --- Insertar logo arriba a la derecha ---
                            doc.content.unshift({
                                image: '{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png'))) }}',
                                width: 100,
                                alignment: 'right',
                                margin: [0, 0, 0, 10] // [left, top, right, bottom]
                            });


                            // --- Ajustar estilos generales de la tabla ---
                            doc.styles.tableHeader.fillColor = '#007bff';
                            doc.styles.tableHeader.color = 'white';
                            doc.styles.tableHeader.alignment = 'center';
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        title: 'Libro Diario',
                        filename: 'Libro_Diario {{ now() }}',
                        customize: function(xlsx) {
                            const sheet = xlsx.xl.worksheets['sheet1.xml'];

                            // Insertar texto o imagen es más limitado en Excel
                            // Pero puedes insertar una fila adicional arriba:
                            const firstRow = $('row:first', sheet);
                            firstRow.before(`
                        <row r="1">
                            <c t="inlineStr" r="A1"><is><t>LIBRO DIARIO - Movimientos Económicos</t></is></c>
                        </row>
                    `);
                        }
                    }, {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        footer: true,
                        customize: function(win) {
                            $(win.document.body).prepend(`
                    <div style="display: flex; justify-content: end; align-items: center; margin-bottom: 20px;">
                        <div>
                            <img src="{{ asset('img/logo.png') }}" style="height: 60px;">
                        </div>
                    </div>
                `);
                        }
                    }
                ]
            });
        });
    </script>
@endpush
