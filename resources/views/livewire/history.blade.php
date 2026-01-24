<div>
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Historial</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Historial</strong></h6>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="transactions-tab" data-toggle="tab"
                        href="#transactions" role="tab">Transacciones</a></li>
                <li class="nav-item"><a class="nav-link" id="users-tab" data-toggle="tab" href="#users"
                        role="tab">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" id="products-tab" data-toggle="tab" href="#products"
                        role="tab">Productos</a></li>
                <li class="nav-item"><a class="nav-link" id="partners-tab" data-toggle="tab" href="#partners"
                        role="tab">Socios</a></li>
                <li class="nav-item"><a class="nav-link" id="client-tab" data-toggle="tab" href="#client"
                        role="tab">Clientes</a></li>
                <li class="nav-item"><a class="nav-link" id="contracts-tab" data-toggle="tab" href="#contracts"
                        role="tab">Contractos</a></li>
                <li class="nav-item"><a class="nav-link" id="detail_contracts-tab" data-toggle="tab"
                        href="#detail_contracts" role="tab">Detalles de Contractos</a></li>
                <li class="nav-item"><a class="nav-link" id="item_details-tab" data-toggle="tab" href="#item_details"
                        role="tab">Detalles de Ensamblajes</a></li>
                <li class="nav-item"><a class="nav-link" id="categories-tab" data-toggle="tab" href="#categories"
                        role="tab">Categorias</a></li>
                <li class="nav-item"><a class="nav-link" id="accounts-tab" data-toggle="tab" href="#accounts"
                        role="tab">Cuentas</a></li>
                <li class="nav-item"><a class="nav-link" id="suppliers-tab" data-toggle="tab" href="#suppliers"
                        role="tab">Proveedores</a></li>
                <li class="nav-item"><a class="nav-link" id="contract_partners-tab" data-toggle="tab"
                        href="#contract_partners" role="tab">Intereses de Socios</a></li>
                <li class="nav-item"><a class="nav-link" id="assemblies-tab" data-toggle="tab" href="#assemblies"
                        role="tab">Ensamblajes</a></li>
            </ul>

            <div class="tab-content mt-3" id="myTabContent">

                <div class="tab-pane fade show active" id="transactions" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableTransactions" :heads="['Fecha', 'ID', 'Descripción', 'Monto (Bs)', 'Cuenta', 'Acciones']">
                        @foreach ($list['transactions'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ $item->account()->withTrashed()->first()->name .($item->account()->onlyTrashed()->first() != null? ' (Borrado)': '') }}
                                </td>
                                <td>
                                    <button data-model='Transaction' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Transaction' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="users" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableUsers" :heads="['Fecha', 'ID', 'nombre', 'Correo', 'Acciones']">
                        @foreach ($list['users'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <button data-model='User' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='User' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="products" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableProducts" :heads="['Fecha', 'Codigo', 'Nombre', 'Tamaño', 'Acciones']">
                        @foreach ($list['products'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->cod }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->size }}</td>
                                <td>
                                    <button data-model='Product' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Product' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="partners" role="tabpanel">
                    <x-adminlte.tool.datatable id="tablePartners" :heads="['Fecha', 'CI', 'Nombre', 'Organización', 'Acciones']">
                        @foreach ($list['partners'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->person->ci }}</td>
                                <td>{{ $item->person->name }}</td>
                                <td>{{ $item->organization }}</td>
                                <td>
                                    <button data-model='Partner' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Partner' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="client" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableClient" :heads="['Fecha', 'ID', 'NIT', 'Organización', 'Acciones']">
                        @foreach ($list['clients'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->nit }}</td>
                                <td>{{ $item->organization }}</td>
                                <td>
                                    <button data-model='Client' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Client' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="contracts" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableContracts" :heads="['Fecha', 'Codigo', 'Cliente', 'Acciones']">
                        @foreach ($list['contracts'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->client()->withTrashed()->first()?->nit ?? '' }}</td>
                                <td>
                                    <button data-model='Contract' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Contract' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="detail_contracts" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableDetailContracts" :heads="['Fecha', 'Codigo', 'Nombre', 'Contrato', 'Acciones']">
                        @foreach ($list['detail_contracts'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item?->detailable()?->withTrashed()?->first()?->cod ?? '' }}</td>
                                <td>{{ $item?->detailable()?->withTrashed()?->first()?->name ?? '' }}</td>
                                <td>{{ $ite?->contract()->withTrashed()->first()->name ?? '' }}</td>
                                <td>
                                    <button data-model='DetailContract' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='DetailContract' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="item_details" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableItemDetails" :heads="['Fecha', 'ID', 'Codigo', 'Nombre', 'Ensamblaje', 'Acciones']">
                        @foreach ($list['detail_items'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item?->product()?->withTrashed()?->first()?->cod }}</td>
                                <td>{{ $item?->product()?->withTrashed()?->first()?->name }}</td>
                                <td>{{ $item?->item()->withTrashed()->first()->cod }}</td>
                                <td>
                                    <button data-model='DetailItem' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='DetailItem' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="categories" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableCategories" :heads="['Fecha', 'ID', 'Nombre', 'Acciones']">
                        @foreach ($list['categories'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <button data-model='Category' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Category' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="accounts" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableAccounts" :heads="['Fecha', 'ID', 'Nombre', 'Acciones']">
                        @foreach ($list['accounts'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <button data-model='Account' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Account' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="suppliers" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableSuppliers" :heads="['Fecha', 'NIT', 'Organización', 'Nombre', 'Acciones']">
                        @foreach ($list['suppliers'] as $item)
                            <tr>
                                <td>{{ $item->delete_at }}</td>
                                <td>{{ $item->nit }}</td>
                                <td>{{ $item->organization }}</td>
                                <td>{{ $item->person()->withTrashed()->first()->name }}</td>
                                <td>
                                    <button data-model='Supplier' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Supplier' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="contract_partners" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableContractPartners" :heads="['Fecha', 'ID', 'Contrato', 'Socio', 'Monto (Bs)', 'Acciones']">
                        @foreach ($list['contract_partners'] as $item)
                            <tr>
                                <td>{{ $item->delete_at }}</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->contract()->withTrashed()->first()->cod }}</td>
                                <td>{{ $item->partner()->withTrashed()->first()?->person?->ci ?? '' }}</td>
                                <td>{{ $item->interest }}</td>
                                <td>
                                    <button data-model='ContractPartner' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='ContractPartner' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>

                <div class="tab-pane fade" id="assemblies" role="tabpanel">
                    <x-adminlte.tool.datatable id="tableAssemblies" :heads="['Fecha', 'Codigo', 'Nombre', 'Acciones']">
                        @foreach ($list['assemblies'] as $item)
                            <tr>
                                <td>{{ $item->deleted_at }}</td>
                                <td>{{ $item->cod }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <button data-model='Assembly' data-id="{{ $item->id }}"
                                        class="restore-btn btn btn-primary"><i class="nf nf-md-restore"></i></button>
                                    <button data-model='Item' data-id="{{ $item->id }}"
                                        class="remove-btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        document.addEventListener('click', async (e) => {
            const restoreBtn = e.target.closest('.restore-btn');
            if (restoreBtn) {
                let model = restoreBtn.getAttribute('data-model'); // Ej: 'User', 'Assembly'
                let id = restoreBtn.getAttribute('data-id');

                Swal.fire({
                    title: 'Restaurar?',
                    text: '¿Está seguro que desea restaurar?',
                    icon: 'warning',
                    inputLabel: 'Ingrese su contraseña',
                    input: 'password',
                    confirmButtonText: 'Restaurar',
                    confirmButtonColor: 'green',
                    cancelButtonText: 'Cancelar',
                    cancelButtonColor: 'red',
                    showCancelButton: true,
                    preConfirm: async (password) => {
                        return $wire.valideWithPassword(password)
                            .then(result => {
                                if (!result.success) {
                                    throw new Error(result.message || 'Error al validar');
                                } else {
                                    // Llamada dinámica al método de Livewire: restore + modelo
                                    let methodName = 'restore' + model;
                                    $wire[methodName](id);
                                    return $wire.$refresh();
                                }
                            })
                            .catch(error => {
                                Swal.showValidationMessage(`Error: ${error.message}`);
                            });
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: '¡Restaurado!',
                            text: `El registro ${model} se ha restaurado correctamente.`,
                            icon: 'success',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                });
            }


            const removeBtn = e.target.closest('.remove-btn');
            if (removeBtn) {
                let model = removeBtn.getAttribute('data-model'); // Ej: 'User', 'Assembly'
                let id = removeBtn.getAttribute('data-id');

                // Llamamos a Livewire para obtener los registros enlazados
                let linked = await $wire["linkedData" + model](id);

                let linkedHtml = 'Este es un proceso Peligroso, esta eliminación tambien borrara:<br>';
                for (let rel in linked) {
                    linkedHtml += `<strong>${rel}</strong><br>`;
                    linked[rel].forEach(rid => {
                        linkedHtml += `ID: ${rid.id} <br>`;
                    });
                }

                Swal.fire({
                    title: 'Eliminar permanentemente?',
                    html: linkedHtml || 'No hay registros enlazados.',
                    icon: 'warning',
                    background: 'red',
                    color: 'white',
                    inputLabel: 'Ingrese su contraseña',
                    input: 'password',
                    confirmButtonText: 'Eliminar',
                    confirmButtonColor: '#A30505',
                    cancelButtonText: 'Cancelar',
                    cancelButtonColor: 'gray',
                    showCancelButton: true,
                    preConfirm: async (password) => {
                        return $wire.valideWithPassword(password)
                            .then(result => {
                                if (!result.success) throw new Error(result.message ||
                                    'Error al validar');
                                return $wire['remove' + model](id);
                            })
                            .catch(error => Swal.showValidationMessage(
                                `Error: ${error.message}`));
                    }
                });
            }
        });


        Livewire.hook('morphed', ({
            el,
            component
        }) => {
            $('#tableTransactions').DataTable({
                destroy: true
            });
            $('#tableUsers').DataTable({
                destroy: true
            });
            $('#tableProducts').DataTable({
                destroy: true
            });
            $('#tablePartners').DataTable({
                destroy: true
            });
            $('#tableClient').DataTable({
                destroy: true
            });
            $('#tableContracts').DataTable({
                destroy: true
            });
            $('#tableDetailContracts').DataTable({
                destroy: true
            });
            $('#tableAccounts').DataTable({
                destroy: true
            });
            $('#tableSuppliers').DataTable({
                destroy: true
            });
            $('#tableAssemblies').DataTable({
                destroy: true
            });
            $('#tableContractPartners').DataTable({
                destroy: true
            });
            $('#tableCategories').DataTable({
                destroy: true
            });
            $('#tableItemDetails').DataTable({
                destroy: true
            });

        })
    </script>
@endscript
