<div>
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Ensamblajes</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Ensamblaje</strong></h6>
        </div>
        @can('item-permission', 3)
            <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Añadir
                Nuevo
                Ensamblaje</button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table1" :heads="$heads" :config="$config">
                @foreach ($data as $item)
                    <tr>
                        <td><strong>{{ $item->id }}</strong></td>
                        <td><strong>{{ $item->cod }}</strong></td>
                        <td><strong>{{ $item->name }}</strong><br></td>
                        <td><strong>{{ Illuminate\Support\Number::format($item->price + $item->extra, precision: 2) }}</strong>
                        </td>
                        <td><a href="{{ route('dashboard.assembly.form', $item->cod) }}" class="btn btn-primary"><i
                                    class="fa fa-ellipsis-v"></i></a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
    </div>
    <x-modal id="modal" title="Nuevo Proveedor" class="modal-lg">
        <livewire:assembly-form></livewire:assembly-form>
    </x-modal>
</div>

@script
    <script>
        window.addEventListener('close-modal', event => {
            $('#modal').modal('hide');

            // $('#table1').DataTable().destroy();
            // $('#table1').DataTable();

        });


        Livewire.hook('morphed', ({
            el,
            component
        }) => {
            $('#table1').DataTable({
                destroy: true
            })

            $(document).ready(function() {
                $('#table-transactions').DataTable();
            });
        })
    </script>
@endscript
