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
                        <td>{{ Carbon\Carbon::parse($item->date)->toDateString() }}</td>
                        <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->contract->cod ?? '' }}</td>
                        <td>{{ $item->account->name ?? '' }}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        <td class="bg-primary"><strong>{{ Illuminate\Support\Number::format($t_income, precision: 2) }}
                                Bs</strong></td>
                        <td class="bg-secondary"><strong>{{ Illuminate\Support\Number::format($t_expense, precision: 2) }}
                                Bs</strong></td>
                    </tr>
                    <tr>
                        <th>SALDO EFECTIVO</th>
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

    <x-modal id="modal-transaction" title="Nuevo Asiento" class="">
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
    <script>
        $(document).ready(function() {
            $('#table-diary').DataTable().destroy();

            $('#table-diary').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        title: '',
                        filename: 'Libro_Diario {{ now() }}',
                        orientation: 'portrait',
                        pageSize: 'A4',
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
