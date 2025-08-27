<x-slot name="path_dir">
    <strong>Dashboard > Productos > {{ $product->id }}</strong>
</x-slot>

<div>
    <div class="modal-body">
        <div class="d-flex">
            <div style="width: 50%;">
                <label for="name">Nombre de Producto</label>
                <input type="text" name="name" class="form-control mb-1" placeholder="Ingrese nombre del producto"
                    wire:model="name">
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
                <div id="searchable" class="d-none p-1" style="border: solid 1px gray;border-radius: 5px;"
                    wire:ignore.self>
                    <label for="search">Buscar</label>
                    <input type="text" name="search" class="form-control mb-3" wire:model.live="searchable"
                        placeholder="Organización o Persona">
                    <label for="search-item">Proveedor</label>
                    <select name="search-item" class="form-select" style="height: 38px;"
                        wire:model.lazy="item_searchable">
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
                <label for="category">Categoria</label>
                <select class="form-select mb-1" name="category" wire:model="category">
                    <option>Seleccione una categoria</option>
                </select>
                <div class="text-danger" style="height: 20px;">
                    @error('category')
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
</div>

@script
    <script>
        document.getElementById('btn-searchable').addEventListener('click', () => {
            document.getElementById('searchable').classList.toggle('d-none');
        })

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
            }).then((result)=>{
                if( result.isConfirmed ) $wire.dispatch('remove');
            })
        });
    </script>
@endscript
