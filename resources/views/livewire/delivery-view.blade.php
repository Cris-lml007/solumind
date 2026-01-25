<div>
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Entregas</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Entregas</strong></h6>
        </div>
        @can('delivery-permission', 3)
            <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Registrar
                Entrega</button>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">

            <livewire:table :heads="['ID', 'Fecha', 'Contrato', 'Importe (Bs)', 'Acciones']" wire:key="delivery-table" name="Delivery">
                @foreach ($data as $item)
                    @php
                        $amount = 0;
                        if ($item->is_canceled == 1) {
                            foreach ($item->detail_contract as $item1) {
                                $amount += $item1->sale_price * $item1->pivot->quantity;
                            }
                        } else {
                            $amount = $item->transaction->amount ?? 0;
                        }
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->contract->cod }}</td>
                        <td>{{ Number::format($amount, precision: 2) }}</td>
                        @can('delivery-permission', 3)
                            <td>
                                <a target="_blank" href="{{ route('dashboard.delivery.pdf', $item->id) }}"
                                    class="btn btn-primary"><i class="fa fa-file"></i></a>
                                <button wire:click="remove({{ $item->id }})" class="btn btn-danger"><i
                                        class="fa fa-trash"></i></button>
                                @if ($item->is_canceled == 1 && $item->contract->status->value == 3)
                                    <a wire:navigate href="{{ route('dashboard.delivery.form', $item->id) }}"
                                        class="btn btn-secondary"><i class="fa fa-pen"></i></a>
                                @endif
                            </td>
                        @else
                            <td></td>
                        @endcan
                    </tr>
                    @endforeach
                    <livewire:slot name="paginate">
                        {{ $data->links() }}
                    </livewire:slot>
            </livewire:table>





        </div>
    </div>

    <x-modal id="modal" class="modal-lg" title="Registrar Entrega">
        <livewire:delivery-form></livewire:delivery-form>
    </x-modal>
</div>

@script
    <script>
        $wire.on('deliveryDeleted', (id) => {
            window.Swal.fire({
                title: "Esta Seguro?...",
                icon: 'warning',
                text: 'Este proceso eliminara registros del libro diario',
                showCancelButton: true,
                confirmButtonColor: "green",
                cancelButtonColor: "red",
                inputLabel: 'Ingrese su contraseña',
                input: 'password',
                confirmButtonText: "¡Sí, bórralo!",
                cancelButtonText: "Cancelar",
                preConfirm: async (password) => {
                    return $wire.valideWithPassword(password)
                        .then(result => {
                            if (!result.success) {
                                throw new Error(result.message || 'Error al validar');
                            } else {
                                $wire.removeDelivery(id);
                            }
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Error: ${error.message}`);
                        });
                }
            })
        })

        function remove(id) {}
    </script>
@endscript
