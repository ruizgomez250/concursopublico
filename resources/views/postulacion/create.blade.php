@extends('adminlte::page')
@section('content_header')
    <h1 class="m-0 custom-heading">Registrar Postulacion</h1>
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui-1.13.2/jquery-ui.min.css') }}">
@endsection
@section('plugins.select2', true)
@push('js')
    <script>
        $(document).ready(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'bottom-right',
                showConfirmButton: false,
                timer: 5000
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '<label style="font-size: 1.6rem !important;">{{ session('success') }}</label>',                    
                    customClass: {
                        popup: 'toast-personalizado'
                    }
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '<label style="font-size: 1.6rem !important;">Error Inesperado!</label>',
                    text: '{{ session('error') }}',
                });
            @endif
        });
    </script>
    <script src="{{ asset('vendor/jquery-ui-1.13.2/jquery-ui.min.js') }}"></script>
    <script>
        var detalles = @json($detalles);
        var links = @json($links);
        
        // Script para agregar y eliminar dinámicamente ítems de compra
        const itemsContainer = document.getElementById('items');
        const totalSumElement = document.getElementById('total-sum');
        let totalSum = 0;
        addNewItem();

        // Función para agregar un nuevo ítem de compra
        function addNewItem() {
            const newItem = document.createElement("div");
            newItem.classList.add("item");

            // Construir las opciones del select usando JavaScript
            let selectOptions = '';
            detalles.forEach(function(detalle) {
                selectOptions += `<option value="${detalle.id}">${detalle.descripcion}</option>`;
            });

            let selectOptions1 = '';
            links.forEach(function(link) {
                selectOptions1 += `<option value="${link.id}">${link.descripcion}</option>`;
            });

            newItem.innerHTML = `
                <div class="row ml-1">
                    <input type="text" name="codigo[]" class="codigo_id form-control col-1" required>
                    <input type="text" name="dependencia[]" class="codigo_id form-control col-1" required>
                    <input type="text" name="puesto[]" class="codigo_id form-control col-1" required>
                    <input type="text" name="tipo[]" class="codigo_id form-control col-1" required>
                    <input type="number" name="vacancia[]" class="codigo_id form-control col-1" required>
                    <select class="form-control col-1" name="link[]">
                        ${selectOptions1}
                    </select>
                    <select class="form-control col-1" name="detalle[]">
                        ${selectOptions}
                    </select>
                    <input type="date" name="inicio[]" class="codigo_id form-control col-2" required>
                    <input type="date" name="fin[]" class="codigo_id form-control col-2" required>
                    <button class="btn-remove btn btn-outline-danger ml-2" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
                </div>
            `;

            // Agregar el nuevo elemento al contenedor
            itemsContainer.appendChild(newItem);

            // Agregar el evento click para eliminar el ítem después de que se haya agregado al contenedor
            const btnRemove = newItem.querySelector(".btn-remove");
            btnRemove.addEventListener("click", function() {
                removeItem(newItem);
            });
        }

        function removeItem(itemToRemove) {
            itemsContainer.removeChild(itemToRemove);
            actualizarSumaTotal();
        }
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('postulacion.store') }}" method="post" autocomplete="off">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <x-adminlte-input type="text" id="descripcion" name="descripcion" label="Descripcion" fgroup-class="col-md-12" required />
                            <x-adminlte-input type="hidden" id="proveedor_id" name="proveedor_id" />
                        </div>
                        <hr>
                        <div id="items">
                            <div class="item" style="background-color: #7CE0FE;">
                                <div class="row ml-2">
                                    <label for="" class="col-1 text-center">Perfil</label>
                                    <label for="" class="col-1 text-center">Dependencia</label>
                                    <label for="" class="col-1 text-center">Profesión</label>
                                    <label for="" class="col-1 text-center">Tipo Concurso</label>
                                    <label for="" class="col-1 text-center">Vacancia</label>
                                    <label for="" class="col-1 text-center">Perfil y Matriz</label>
                                    <label for="" class="col-1 text-center">Detalles del Proceso</label>
                                    <label for="" class="col-1 text-center">Inicio Postulacion</label>
                                    <label for="" class="col-1 text-center">Fin Postulacion</label>
                                </div>
                            </div>
                        </div>
                        <button onclick="addNewItem()" class="btn btn-primary mt-2" type="button">Agregar Ítem</button>
                        <div>Suma Total: <span id="total-sum">0</span></div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <a class="btn btn-danger mx-1" style="float: right;" href="{{ route('postulacion.index') }}">Cancelar</a>
                                <x-adminlte-button class="btn-group" style="float: right;" type="submit" label="Registrar" theme="primary" icon="fas fa-lg fa-save" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
