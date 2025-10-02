@extends('adminlte::page')

@section('content')
    <div>
        <h1 class="m-0">Reportes</h1>
        <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Generar Reportes</strong></h6>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body" style="width: 90%;">
                    <div class="card-title">Ingresos/Egresos</div>
                    <canvas id="transactions"></canvas>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body" style="width: 90%;">
                    <div class="card-title">Estado de Cuentas</div>
                    <canvas id="accounts"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body" style="width: 90%;">
                    <div class="card-title">Estado de Contratos</div>
                    <canvas id="contracts"></canvas>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body" style="width: 90%;">
                    <div class="card-title">Productos Vendidos</div>
                    <canvas id="products"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body" style="width: 100%;">
                    <div class="card-title">Entregas</div>
                    <canvas id="deliveries" class="border"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            function getRandomColor() {
                var letters = '0123456789ABCDEF';
                var color = '#';
                for (var i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }

            (async function() {
                Chart.defaults.backgroundColor = '#DC9B24';
                Chart.defaults.borderColor = 'white';
                Chart.defaults.color = 'white';

                let contracts = {{ Js::from($contracts) }};
                new Chart(
                    document.getElementById('contracts'), {
                        type: 'doughnut',
                        data: {
                            labels: contracts.map(row => {
                                switch (row.status) {
                                    case 1:
                                        return 'Proforma';
                                    case 2:
                                        return 'Proforma Fallida';
                                    case 3:
                                        return 'Contrato';
                                    case 4:
                                        return 'Contrato Fallido';
                                    case 5:
                                        return 'Contrato Finalizado';
                                }
                            }),
                            datasets: [{
                                data: contracts.map(row => row.count),
                                backgroundColor: contracts.map(row => getRandomColor())
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'right' // 👉 prueba con 'bottom', 'left' o 'right'
                                }
                            }
                        }
                    }
                );

                let products = {{ Js::from($products) }};
                new Chart(
                    document.getElementById('products'), {
                        type: 'doughnut',
                        data: {
                            labels: products.map(row => row.cod),
                            datasets: [{
                                data: products.map(row => row.quantity),
                                backgroundColor: products.map(row => getRandomColor())
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'right' // 👉 prueba con 'bottom', 'left' o 'right'
                                }
                            }
                        }
                    }
                );


                new Chart(
                    document.getElementById('transactions'), {
                        type: 'doughnut',
                        data: {
                            labels: ['Ingresos (Bs)', 'Egresos (Bs)'],
                            datasets: [{
                                data: [{{ $transactions[0] }}, {{ $transactions[1] }}],
                                backgroundColor: ['#59F767', '#F85353']
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'right' // 👉 prueba con 'bottom', 'left' o 'right'
                                }
                            }
                        }
                    }
                );

                let accounts = {{ Js::from($accounts) }};
                new Chart(
                    document.getElementById('accounts'), {
                        type: 'doughnut',
                        data: {
                            labels: accounts.map(row => row.name + ' (Bs)'),
                            datasets: [{
                                data: accounts.map(row => row.amount),
                                backgroundColor: accounts.map(row => getRandomColor())
                            }]
                        },
                        options: {
                            plugins: {
                                legend: {
                                    position: 'right' // 👉 prueba con 'bottom', 'left' o 'right'
                                }
                            }
                        }
                    }
                );
                let deliveries = {{ Js::from($deliveries) }};
                new Chart(
                    document.getElementById('deliveries'), {
                        type: 'bar',
                        data: {
                            labels: deliveries.map(row => row.period),
                            datasets: [{
                                label: 'Cantidad de Entregas',
                                data: deliveries.map(row => row.total_deliveries),
                                // backgroundColor: accounts.map(row => getRandomColor())
                            }]
                        }
                    }
                );
            })();
        })
    </script>
@endsection
