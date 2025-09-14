<div>
    <div class="modal-body">
        <div class="d-flex">
            <div class="w-50">
                <label for="name">Nombre</label>
                <div class="input-group">
                    <input type="text" name="name" class="form-control" wire:model="name">
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
                <label for="password">Contraseña</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" wire:model="password">
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <div class="w-50 ms-1">
                <label for="email">Correo</label>
                <div class="input-group">
                    <input type="email" name="email" class="form-control" wire:model="email">
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
                <label for="password-verify">Confimar Contraseña</label>
                <div class="input-group">
                    <input type="password" name="password-verify" class="form-control"
                        wire:model="password_confirmation">
                </div>
                <div class="text-danger" style="height: 20px;">
                    @error('password_confirmation')
                        {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        @if (Auth::user()->permission->config >= 2)
            <div class="mb-3">
                <label for="">Enlazar con Persona</label>
                <select wire:model="person_id" id="" class="form-select">
                    <option value="null">Seleccione una Persona</option>
                    @foreach ($persons as $item)
                        <option value="{{ $item->id }}">{{ $item->name . ' (' . $item->ci . ')' }}</option>
                    @endforeach
                </select>
                <div class="text-danger" style="height: 20px;">
                    @error('person_id')
                        {{ $message }}
                    @enderror
                </div>
            </div>
            <h6><strong>Permisos</strong></h6>
            <div class="row">
                <div class="col border rounded">
                    <label for="product">Gestión de Proveedores</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p1" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p1" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p1" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
                <div class="col border rounded">
                    <label for="product">Gestión de Productos</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p2" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p2" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p2" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col border rounded">
                    <label for="product">Gestión de Ensamblajes</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p3" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p3" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p3" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
                <div class="col border rounded">
                    <label for="product">Gestión de Entregas</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p4" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p4" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p4" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col border rounded">
                    <label for="product">Gestión de Socios</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p5" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p5" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p5" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
                <div class="col border rounded">
                    <label for="product">Gestión de Libro Diario</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p6" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p6" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p6" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col border rounded">
                    <label for="product">Gestión de Clientes</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p7" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p7" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p7" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
                <div class="col border rounded">
                    <label for="product">Gestión de Comprobantes</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p8" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p8" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p8" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col border rounded">
                    <label for="product">Libro Mayor</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p9" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p9" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                </div>
                <div class="col border rounded">
                    <label for="product">Reportes</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p10" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p10" value="2" class="form-check-input">
                        <label for="">Lectura</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col border rounded">
                    <label for="product">Configuraciones</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p11" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p11" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
                <div class="col border rounded">
                    <label for="product">Historial de Movimientos</label>
                    <div class="form-check">
                        <input type="radio" wire:model="p12" value="1" class="form-check-input">
                        <label for="">Ninguno</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" wire:model="p12" value="3" class="form-check-input">
                        <label for="">Lectura y Escritura</label>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @can('config-permission', 3)
        @if ($user->id == null)
            <div class="modal-footer">
                <div class="">
                    <input type="checkbox" class="form-check-input" wire:model="is_active">
                    <label for="" class="form-check-label">Activo</label>
                </div>
                <button class="btn btn-primary" wire:click="save">Guardar</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        @else
            <hr>
            <div class="d-flex justify-content-end my-3">
                @if (Auth::user()->permission->config >= 2)
                    <div class="m-0 pt-2">
                        <input type="checkbox" class="form-check-input" wire:model="is_active">
                        <label for="" class="form-check-label">Activo</label>
                    </div>
                @endif
                <button class="btn btn-primary ms-1" wire:click="save">Guardar</button>
                @if (Auth::user()->permission->config >= 2)
                    <button class="btn btn-danger ms-1" id="btn-remove">Eliminar</button>
                @endif
            </div>
        @endif
    @else
        @if (Auth::user()->id == $user->id)
            <hr>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary ms-1" wire:click="save">Guardar</button>
            </div>
        @endif
    @endcan
</div>

@script
    <script>
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
                if (result.isConfirmed) $wire.remove();
            })
        });
    </script>
@endscript
