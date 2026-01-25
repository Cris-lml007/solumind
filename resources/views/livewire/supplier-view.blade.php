<div>
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Proveedores</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Proveedores</strong></h6>
        </div>
        @can('supplier-permission', 3)
            <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Añadir Nuevo
                Proveedor</button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <livewire:table :heads="$heads" name="Supplier" wire:key="suppliersTable">
                @foreach ($data as $item)
                <tr>
                        <td class="simpleline"><strong>{{ $item->id }}</strong>
                        <td class="simpleline"><strong>{{ $item->nit == null ? $item->person->ci : $item->nit }}</strong>
                        </td>
                        <td><strong>{{ $item->person->name }}</strong><br>{{ $item->organization }}</td>
                        <td><strong>{{ $item->email == null ? $item->person->email : $item->email }}</strong><br>{{ $item->phone == null ? $item->person->phone : $item->phone }}
                        </td>
                        <!-- <td class="simpleline"><strong>Social</strong></td> -->
                        <td class="simpleline"><a wire:navigate href="{{ route('dashboard.supplier.form', $item->id) }}"
                                class="btn btn-primary"><i class="fa fa-ellipsis-v"></i></a>
                        </td>
                    </tr>
                    @endforeach
                    <livewire:slot name="paginate">
                        {{ $data->links() }}
                    </livewire:slot>
            </livewire:table>
        </div>
    </div>
    <x-modal id="modal" title="Nuevo Proveedor" class="modal-lg">
        <livewire:supplier-form></livewire:supplier-form>
    </x-modal>
</div>
