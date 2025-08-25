<x-slot name="path_dir">
    <strong>Dashboard > Productos > {{ $product->id }}</strong>
</x-slot>

<div>
    <div class="modal-body">
        <form>
            <div class="d-flex">
                <div style="width: 50%;">
                    <label for="name">Nombre de Producto</label>
                    <input type="text" name="name" class="form-control mb-3" placeholder="Ingrese nombre del producto"
                        wire:model="name">
                    <label for="supplier">Proveedor</label>
                    <div class="input-group mb-3">
                        <input type="number" name="supplier" class="form-control mb-3"
                            placeholder="Ingrese NIT del proveedor" wire:model.live="nit">
                        <span class="input-group-text" style="height: 38px;"><i
                                class="nf {{ $code }}"></i></span>
                    </div>
                </div>
                <div style="width: 50%; margin-left: 10px;">
                    <label for="price">Precio (Bs)</label>
                    <input type="number" name="price" class="form-control mb-3"
                        placeholder="Ingrese precio del producto" wire:model="price">
                    <label for="category">Categoria</label>
                    <select class="form-select mb-3" name="category" wire:model="category">
                        <option>Seleccione una categoria</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="description">Descripción</label>
                <textarea class="form-control mb-3" name="description" rows="3" placeholder="Ingrese descripción del producto"
                    wire:model="description"></textarea>
            </div>
            <h6><strong>Imagen del Producto</strong></h6>
            <div class="d-flex">
                <div style="width: 100%;">
                    <input type="file" name="image1" class="form-control mb-3" wire:model="img">
                </div>
            </div>

            @if (!empty($product->id))
                <div class="d-flex justify-content-center">
                    <img src="{{ $img }}" class="img-fluid" alt="Sin Imagen" style="width: auto;height: 300px;">
                </div>
            @endif
        </form>
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
            <button class="btn btn-danger" wire:click="remove">Eliminar</button>
        </div>
    @endif
</div>
