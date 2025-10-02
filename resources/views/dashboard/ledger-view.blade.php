@extends('adminlte::page')
@section('title', 'Libro Mayor')
@section('content')
    <div class="my-3">
        <h1 class="m-0">Libro Mayor</h1>
        <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Libro Mayor</strong></h6>
    </div>
    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table" :heads="$heads" hoverable with-buttons>
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
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->contract->cod ?? '' }}</td>
                        <td>{{ $item->account->name ?? ''}}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th>Fecha</th>
                        <th>Ingreso</th>
                        <th>Egreso</th>
                        <th>Descripción</th>
                        <th>Contrato</th>
                        <th>Cuenta</th>
                    </tr>
                    <tr>
                        <th style="text-align:right">Totales:</th>
                        <th id="total-ingreso"></th>
                        <th id="total-egreso"></th>
                        <th colspan="3"></th>
                    </tr>
                    <tr>
                        <th style="text-align:right">Patrimonio:</th>
                        <th colspan="2" id="total-diferencia"></th>
                        <th colspan="3"></th>
                    </tr>
                </tfoot>
            </x-adminlte.tool.datatable>
            <div class="my-3">
                <h5>Estado de Cuentas</h5>
            </div>
            <x-adminlte.tool.datatable id="table1" :heads="['ID', 'Nombre', 'Cantidad (Bs)']">
                @foreach ($data1 as $item)
                    <tr>
                        <td>{{ $item->account_id }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ Illuminate\Support\Number::format($item->balance, precision: 2) }} Bs</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th></th>
                        <th>Total</th>
                        <th id="total"></th>
                    </tr>
                </tfoot>
            </x-adminlte.tool.datatable>
        </div>
    </div>
@endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script> <!-- Or your preferred jQuery version -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>

    <!-- DataTables Buttons Extension -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.5/css/buttons.dataTables.css">
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.html5.js"></script> <!-- For HTML5 export buttons -->
    <script src="https://cdn.datatables.net/buttons/3.2.5/js/buttons.print.js"></script> <!-- For print button -->

    <!-- Required for Excel export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#table')) {
                $('#table').DataTable().destroy();
            }
            if ($.fn.DataTable.isDataTable('#table1')) {
                $('#table1').DataTable().destroy();
            }

            $('#table1').DataTable({
                paging: false,
                searching: false,
                dom: '<"d-flex justify-content-end align-items-center"fB>rtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                    'print'
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // función para convertir a número eliminando Bs y comas
                    var parseValue = function(i) {
                        return typeof i === 'string' ?
                            parseFloat(i.replace(/[^0-9.-]+/g, '')) || 0 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // sumar la columna 2 (balance)
                    var total = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return parseValue(a) + parseValue(b);
                        }, 0);

                    // actualizar el footer
                    $(api.column(2).footer()).html(
                        new Intl.NumberFormat('es-BO', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(total) + ' Bs'
                    );
                }
            })



            $('#table').DataTable({
                // dom: '', // Required to display the buttons
                dom: '<"d-flex justify-content-between align-items-center"fB>rtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                    'print'
                ],
                paging: false,
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i :
                            0;
                    };

                    // Total ingresos
                    var totalIngresos = api
                        .column(1, {
                            page: 'current'
                        })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Total egresos
                    var totalEgresos = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Diferencia
                    var diferencia = totalIngresos - totalEgresos;

                    // Insertar en el footer
                    $('#total-ingreso').html(totalIngresos.toFixed(2) + ' Bs');
                    $('#total-egreso').html(totalEgresos.toFixed(2) + ' Bs');
                    $('#total-diferencia').html(diferencia.toFixed(2) + ' Bs');
                },
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            let column = this;
                            let title = column.footer().textContent;

                            // Crear input solo en la primera fila
                            if ($(column.footer()).closest("tr").index() === 0) {
                                let input = document.createElement('input');
                                input.placeholder = title;
                                column.footer().replaceChildren(input);

                                input.addEventListener('keyup', () => {
                                    if (column.search() !== input.value) {
                                        column.search(input.value).draw();
                                    }
                                });
                            }
                        });
                }
            });
        });






        // $(document).ready(function() {
        //     if ($.fn.DataTable.isDataTable('#table')) {
        //         $('#table').DataTable().destroy(); // Destroy existing instance
        //     }
        //     $('#table').DataTable({
        //         paging: false,
        //         initComplete: function() {
        //             this.api()
        //                 .columns()
        //                 .every(function() {
        //                     let column = this;
        //                     let title = column.footer().textContent;
        //
        //                     // Create input element
        //                     let input = document.createElement('input');
        //                     input.placeholder = title;
        //                     column.footer().replaceChildren(input);
        //
        //                     // Event listener for user input
        //                     input.addEventListener('keyup', () => {
        //                         if (column.search() !== this.value) {
        //                             column.search(input.value).draw();
        //                         }
        //                     });
        //                 });
        //         }
        //     })
        // })
    </script>
@endsection
