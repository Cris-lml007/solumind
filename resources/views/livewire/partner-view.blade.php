<div>
    {{-- The best athlete wants his opponent at his best. --}}
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Socios</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Socios</strong></h6>
        </div>
        @can('partner-permission', 3)
            <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Añadir Nuevo
                Socio</button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table1" :heads="$heads" :config="$config">
                @foreach ($data as $item)
                    <tr>
                        <td class="simpleline"><strong>{{ $item->id }}</strong></td>
                        <td class="simpleline"><strong>{{ $item->person->ci }}</strong></td>
                        <td><strong>{{ $item->person->name }}</strong><br>{{ $item->organization }}</td>
                        <td><strong>{{ $item->person->email }}</strong><br>{{ $item->person->phone }}</td>
                        <td class="simpleline"><a wire:navigate href="{{ route('dashboard.partner.form', $item->id) }}"
                                class="btn btn-primary"><i class="fa fa-ellipsis-v"></i></a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
    </div>

    <x-modal title="Nuevo Socio" id="modal" class="modal-lg">
        <livewire:partner-form></livewire:partner-form>
    </x-modal>
</div>
