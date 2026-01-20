<div>
    @if ($item->id == null)
        <div class="modal-body">
            <div class="d-flex">
                <div class="w-50">
                    <label for="category">Categoria</label>
                    <select class="form-select" wire:model.live="category">
                        <option>Seleccione una categoria</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name . ' (' . $item->alias . ')' }}</option>
                        @endforeach
                    </select>
                    <div class="text-danger" style="height: 20px;">
                        @error('category')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="w-50 ms-1">
                    <label for="code">Codigo</label>
                    <div class="input-group">
                        <span class="input-group-text" style="height: 38px;">{{ $alias ?? '' }}</span>
                        <input type="text" name="code" class="form-control" wire:model="code">
                    </div>
                    <div class="text-danger" style="height: 20px;">
                        @error('code')
                            {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            <label for="name">Nombre</label>
            <input type="text" name="name" class="form-control" wire:model.live="name">
            <div class="text-danger" style="height: 20px;">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            @can('item-permission', 3)
                <button class="btn btn-primary" wire:click="create">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            @endcan
        </div>
        @else
        <div class="mb-3">
            <h1 style="color: #F7B924;">Ensamblaje</h1>
            <strong>Dashboard > Ensamblaje > {{ $alias . $code }}</strong>
        </div>
        <div>
            <div class="d-flex">
                <div style="width: 50%;">
                    <label for="code">Categoria/Codigo</label>
                    <div class="input-group">
                        <select class="form-select mb-1" name="category" wire:model.live="category"
                            style="width: 40%;">
                            <option>Seleccione una categoria</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name . ' (' . $item->alias . ')' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="input-group-text" style="height: 38px;">{{ $alias }}</span>
                        <input type="text" name="code" class="form-control mb-1"
                            placeholder="Ingrese codigo del producto" wire:model="code">
                    </div>
                    <div class="text-danger" style="height: 20px;">
                        @error('code')
                            {{ $message }}
                        @enderror
                    </div>
                    <label for="category">Nombre</label>
                    <input type="text" name="name" class="form-control mb-1"
                        placeholder="Ingrese nombre del ensamblaje" wire:model="name">
                    <div class="text-danger" style="height: 20px;">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                    <label for="description">Unidad de Medida</label>
                    <input type="text" class="form-control" wire:model="unit">
                    <div>
                        <label for="description">Descripción</label>
                        <textarea class="form-control mb-1" name="description" rows="3" placeholder="Ingrese descripción del producto"
                            wire:model="description"></textarea>
                        <div class="text-danger" style="height: 20px;">
                            @error('description')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div style="width: 50%; margin-left: 10px;">
                    <label>Imagen del Producto</label>
                    <div class="d-flex">
                        <div style="width: 100%;">
                            <input type="file" name="image1" class="form-control mb-3" wire:model="img">
                        </div>
                    </div>
                    @if (!empty($item->id))
                        <div class="d-flex justify-content-center">
                            <img src="{{ $img }}" class="img-fluid" alt="Sin Imagen"
                                style="width: auto;height: 220px;">
                        </div>
                    @endif
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between mb-3">
                <h5 class="align-self-center m-0 p-0"><strong>Lista de Materiales</strong></h5>
                @can('item-permission', 3)
                    <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary"><i
                            class="fa fa-plus"></i> Añadir Producto</button>
                @endcan
            </div>
            <table class="table table-striped">
                <thead>
                    <th>Id</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach ($products as $iter)
                        <tr>
                            <td>{{ $iter['id'] }}</td>
                            <td>{{ $iter['name'] }}</td>
                            <td>{{ $iter['quantity'] }}</td>
                            <td>{{ $iter['price'] }}</td>
                            <td>{{ (int) $iter['quantity'] * (int) $iter['price'] }}</td>
                            <td>
                                <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary"
                                    wire:click="update({{ $iter['id'] }})"><i class="fa fa-pen"></i></button>
                                <button wire:click="delete({{ $iter['id'] }})" class="btn btn-danger"><i
                                        class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3"></th>
                        <th>Total</th>
                        <th colspan="2">{{ $price }} Bs</th>
                    </tr>
                </tfoot>
            </table>
            <div class="d-flex">
                <div class="w-50">
                    <label for="price">Imprevistos (Bs)</label>
                    <input type="number" name="price" class="form-control mb-3" placeholder="Ingrese imprevistos"
                        wire:model.lazy="extra">
                </div>
                <div class="w-50">
                    <label for="price">Precio (Bs)</label>
                    <input type="number" name="price" class="form-control mb-3" placeholder="Ingrese precio"
                        value="{{ $price + (empty($extra) ? 0 : $extra) }}" disabled>
                </div>
            </div>
            <hr>
            @can('item-permission', 3)
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-primary me-1" wire:click="save">Guardar</button>
                    <button class="btn btn-danger" id="btn-remove">Eliminar</button>
                </div>
            @endcan

            <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Añadir Producto</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <label for="search">Buscar</label>
                            <input type="text" wire:model.lazy="search" class="form-control mb-3">
                            <div class="d-flex">
                                <input type="number" id="ipd" class="d-none" wire:model="ipd">
                                <div class="w-50 me-1">
                                    <label for="n">Producto</label>
                                    <select id="n" class="form-select mb-3" wire:model.lazy="na">
                                        <option>Seleccione Producto</option>
                                        @foreach ($list as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="q">Cantidad</label>
                                    <input type="number" id="q" class="form-control mb-3"
                                        wire:model.lazy="q">
                                </div>
                                <div class="w-50">
                                    <label for="supplier">Proveedor</label>
                                    <input type="text" disabled class="form-control mb-3" wire:model="s" disabled>
                                    <label for="p">Precio (Bs)</label>
                                    <input type="number" id="p" class="form-control mb-3"
                                        wire:model.lazy="p">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="btn-add">Añadir</button>
                            <button data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if (!empty($code))
    @script
        <script>
            const p = document.getElementById('p');
            const n = document.getElementById('n');
            const q = document.getElementById('q');
            const ipd = document.getElementById('ipd');
            document.getElementById('btn-add').addEventListener('click', () => {
                const i = n.selectedIndex;
                console.log(ipd.value)
                $wire.dispatch('add', [n.value, ipd.value, n.options[i].text, q.value, p.value]);
                // p.value = null;
                // n.value = null;
                // q.value = null;
            });


            document.getElementById('btn-remove').addEventListener('click', () => {
                Swal.fire({
                    title: 'Esta Seguro?...',
                    text: 'Este proceso borrara este registro logicamente, pero aun se podra recuperar',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: "#F7B924",
                    cancelButtonColor: "red",
                    confirmButtonText: "Si, deseo borrar!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) $wire.dispatch('remove');
                })
            });
        </script>
    @endscript
@endif
