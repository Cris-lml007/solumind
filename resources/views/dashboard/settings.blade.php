@extends('adminlte::page')
@section('content_header')
    <h1>Configuraciones</h1>
@endsection
@section('content')
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <div class="d-flex w-50">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane"
                    type="button" role="tab" aria-controls="users-tab-pane" aria-selected="true">Usuarios</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="accounts-tab" data-bs-toggle="tab" data-bs-target="#accounts-tab-pane"
                    type="button" role="tab" aria-controls="accounts-tab-pane" aria-selected="false">Cuentas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-tab-pane"
                    type="button" role="tab" aria-controls="categories-tab-pane"
                    aria-selected="false">Categorias</button>
            </li>
        </div>
        <div class="d-flex justify-content-end w-50">
            <button data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-primary"><i
                    class="fa fa-plus"></i></button>
        </div>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="users-tab-pane" role="tabpanel" aria-labelledby="users-tab"
            tabindex="0">
            <div class="card">
                <div class="card-body">
                    <x-adminlte.tool.datatable id="users" :heads="['Id', 'Usuario', 'Correo', 'Acciones']">
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="accounts-tab-pane" role="tabpanel" aria-labelledby="accounts-tab" tabindex="0">
            <div class="card">
                <div class="card-body">
                    <x-adminlte.tool.datatable id="accounts" :heads="['Id', 'Nombre', 'Accciones']">
                        @foreach ($data['accounts'] as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    <a href="{{route('dashboard.settings.account',$item->id)}}" class="btn btn-primary"><i class="fa fa-pen"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="categories-tab-pane" role="tabpanel" aria-labelledby="categories-tab" tabindex="0">
            <div class="card">
                <div class="card-body">
                    <x-adminlte.tool.datatable id="categories" :heads="['Identidicador', 'Nombre', 'Acciones']">
                        @foreach ($data['categories'] as $item)
                            <tr>
                                <td>{{ $item->alias }}</td>
                                <td>{{ $item->name }}</td>
                                <td><a class="btn btn-primary"
                                        href="{{ route('dashboard.settings.category', $item->id) }}"><i
                                            class="fa fa-ellipsis-v"></i></a></td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>
    </div>

    <x-modal id="modal" title="" class="modal-lg">
        <div id="modal-user">
            <div class="modal-body">
                <div class="d-flex">
                    <div class="w-50">
                        <label for="name">Nombre</label>
                        <div class="input-group">
                            <input type="email" name="name" class="form-control">
                        </div>
                        <label for="password">Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="w-50 ms-1">
                        <label for="email">Correo</label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control">
                        </div>
                        <label for="password-verify">Confimar Contraseña</label>
                        <div class="input-group">
                            <input type="password" name="password-verify" class="form-control">
                        </div>
                    </div>
                </div>
                <h6><strong>Permisos</strong></h6>
                <div class="row">
                    <div class="col border rounded">
                        <label for="product">Gestión de Proveedores</label>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Ninguno</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura y Escritura</label>
                        </div>
                    </div>
                    <div class="col border rounded">
                        <label for="product">Gestión de Productos</label>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Ninguno</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura y Escritura</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col border rounded">
                        <label for="product">Gestión de Ensamblajes</label>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Ninguno</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura y Escritura</label>
                        </div>
                    </div>
                    <div class="col border rounded">
                        <label for="product">Gestión de Entregas</label>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Ninguno</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura y Escritura</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col border rounded">
                        <label for="product">Configuraciones</label>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Ninguno</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura y Escritura</label>
                        </div>
                    </div>
                    <div class="col border rounded">
                        <label for="product">Gestión de Libro Diario</label>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Ninguno</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="" value="" class="form-check-input">
                            <label for="">Lectura y Escritura</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary">Guardar</button>
                <button class="btn btn-sencodary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>

        <div id="modal-category" class="d-none">
            <livewire:category-form></livewire:category-form>
        </div>

        <div id="modal-account" class="d-none">
            <livewire:account-form></livewire:account-form>
        </div>
    </x-modal>
@endsection

@section('js')
    <script>
        var modalUser = document.getElementById('modal-user');
        var modalCategory = document.getElementById('modal-category');
        var modalAccount = document.getElementById('modal-account');
        var modalTitle = document.getElementById('modal-title');
        modalTitle.textContent = 'Añadir Usuario';


        var tabUsers = document.getElementById('users-tab').addEventListener('click', () => {
            modalTitle.textContent = 'Añadir Usuario';
            modalUser.classList.remove('d-none');
            modalCategory.classList.add('d-none');
            modalAccount.classList.add('d-none');
        });
        var tabAccounts = document.getElementById('accounts-tab').addEventListener('click', () => {
            modalTitle.textContent = 'Añadir Cuenta';
            modalUser.classList.add('d-none');
            modalCategory.classList.add('d-none');
            modalAccount.classList.remove('d-none');
        });
        var tabCategories = document.getElementById('categories-tab').addEventListener('click', () => {
            modalTitle.textContent = 'Añadir Categoria';
            modalUser.classList.add('d-none');
            modalCategory.classList.remove('d-none');
            modalAccount.classList.add('d-none');
        });
    </script>
@endsection
