<x-slot name="path_dir">
    <strong>Dashboard > Productos > {{ $product->id }}</strong>
</x-slot>

<div>
    <div class="modal-body">
        <div class="d-flex">
            <div style="width: 50%;">
                <label for="name">Nombre de Producto/Medida</label>
                <div class="input-group">
                    <input type="text" name="name" class="form-control mb-1" placeholder="Nombre del producto"
                        wire:model.live="name" style="width: 40%;">
                    <input type="text" placeholder="Medida" wire:model.live="size" class="form-control"
                        style="width: 15%;">
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
                <label for="supplier">Proveedor</label>
                <div class="input-group mb-1">
                    <input type="number" name="supplier" class="form-control mb-1"
                        placeholder="Ingrese NIT del proveedor" wire:model.live="nit">
                    <span class="input-group-text" style="height: 38px;"><i class="nf {{ $code }}"></i></span>
                    <a id="btn-searchable" class="btn btn-primary" style="height: 38px;"><i
                            class="fa fa-search"></i></a>
                </div>
                <div id="searchable" class="d-none p-1 glow-border" wire:ignore.self>
                    <label for="search">Buscar</label>
                    <input type="text" name="search" class="form-control mb-3" wire:model.live="searchable"
                        placeholder="Organización o Persona">
                    <label for="search-item">Proveedor</label>
                    <select name="search-item" class="form-select" style="height: 38px;"
                        wire:model.live="item_searchable">
                        <option>Seleccione Proveedor</option>
                        @foreach ($list ?? [] as $item)
                            <option value="{{ $item->nit }}">
                                {{ ($item->organization ?? '') . ' (' . $item->person->name . ')' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('nit')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div style="width: 50%; margin-left: 10px;">
                <label for="price">Precio (Bs)</label>
                <input type="number" name="price" class="form-control mb-1" placeholder="Ingrese precio del producto"
                    wire:model="price">
                <div class="text-danger" style="height: 20px;">
                    @error('price')
                        {{ $message }}
                    @enderror
                </div>
                <label for="category">Categoria/Codigo</label>
                <div class="input-group">
                    <select class="form-select mb-1" name="category" wire:model.live="category" style="width: 40%;">
                        <option>Seleccione una categoria</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name . ' (' . $item->alias . ')' }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-text" style="height: 38px;">{{ $alias }}</span>
                    <input type="text" class="form-control" wire:model="cod">
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('category')
                        {{ $message . '; ' }}
                    @enderror
                    @error('cod')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div>
            <label for="description">Descripción</label>
            <textarea class="form-control mb-1" name="description" rows="3" placeholder="Ingrese descripción del producto"
                wire:model="description"></textarea>
        </div>
        <h6><strong>Imagen del Producto</strong></h6>
        <div class="d-flex">
            <div style="width: 100%;">
                <input type="file" name="image1" class="form-control mb-1" wire:model="img">
            </div>
        </div>

        @if (!empty($product->id))
            <div class="d-flex justify-content-center">
                <img src="{{ $img }}" class="img-fluid" alt="Sin Imagen" style="width: auto;height: 300px;">
            </div>
        @endif
    </div>
    @can('product-permission', 3)
        @if (empty($product->id))
            <div class="modal-footer">
                <button class="btn btn-primary" wire:click="save">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @else
            <hr>
            <div class="d-flex justify-content-end mb-3">
                <button class="btn btn-primary me-1" wire:click="save">Guardar</button>
                <button id="btn-remove" class="btn btn-danger">Eliminar</button>
            </div>
        @endif
    @endcan
</div>

@section('css')
    <style>
        .glow-border {
            border: 2px solid #ffca2c;
            border-radius: 5px;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0% {
                box-shadow: 0 0 5px #ffca2c, 0 0 10px #ffca2c;
            }

            50% {
                box-shadow: 0 0 0px #ffca2c;
            }

            100% {
                box-shadow: 0 0 5px #ffca2c, 0 0 10px #ffca2c;
            }
        }
    </style>
@endsection

@script
    <script>
        if ($wire.status == 1) {
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
        }

        document.getElementById('btn-searchable').addEventListener('click', () => {
            document.getElementById('searchable').classList.toggle('d-none');
        });
    </script>
@endscript
