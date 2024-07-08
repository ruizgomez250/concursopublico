@extends('adminlte::page')



@section('content_header')
    <h1 class="m-0 custom-heading">Registrar Detalle</h1>
@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/jquery-ui-1.13.2/jquery-ui.min.css') }}">
@endsection
@section('plugins.select2', true)
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('detalleproceso.store') }}" method="post" autocomplete="off">
                        @csrf
                        @method('POST')
                        {{-- 'id', 'codigo', 'descripcion', 'detalle', 'id_categoria', 'id_estado','pcosto', 'pventa', 'observacion' --}}

                        <div class="row">
                            {{-- With Label --}}
                            @php
                                $config1 = ['format' => 'DD-MM-YYYY'];
                            @endphp



                            <x-adminlte-input type="text" id="descripcion" name="descripcion" label="Descripcion"
                                fgroup-class="col-md-12" required />


                            <x-adminlte-input type="hidden" id="proveedor_id" name="proveedor_id" />
                        </div>

                        <hr>

                        <div id="items">
                            <div class="item" style="background-color: #7CE0FE;">
                                <div class="row ml-2">
                                    <label for="" class="col-2 text-center">Fecha</label>
                                    <label for="" class="col-6 text-center">Texto</label>
                                    <label for="" class="col-2 ">Link</label>
                                </div>
                            </div>
                        </div>

                        <button onclick="addNewItem()" class="btn btn-primary mt-2" type="button">Agregar Ítem</button>

                        <!-- Agrega este elemento para mostrar la suma total -->
                        <div>Suma Total: <span id="total-sum">0</span></div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <a class="btn btn-danger mx-1" style="float: right;"
                                    href="{{ route('detalleproceso.index') }}">Cancelar</a>
                                <x-adminlte-button class="btn-group" style="float: right;" type="submit" label="Registrar"
                                    theme="primary" icon="fas fa-lg fa-save" />
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@stop

@push('js')
    <script src="{{ asset('vendor/jquery-ui-1.13.2/jquery-ui.min.js') }}"></script>
    <script>
        var links = @json($links);
        $('input[name="fechaemision"]').on('keydown', function(e) {
            // Verifica si la tecla presionada es "Enter"
            if (e.key === 'Enter') {
                e.preventDefault();
                // Enfoca en el campo de fecha
                $('input[name="nrofactura"]').focus();
            }
        });
        $('input[name="nrofactura"]').on('keydown', function(e) {
            // Verifica si la tecla presionada es "Enter"
            if (e.key === 'Enter') {
                e.preventDefault();
                // Enfoca en el campo de fecha
                $('input[name="timbrado"]').focus();
            }
        });
        $('input[name="timbrado"]').on('keydown', function(e) {
            // Verifica si la tecla presionada es "Enter"
            if (e.key === 'Enter') {
                e.preventDefault();
                // Enfoca en el campo de fecha
                $('input[name="cod_proveedor"]').focus();
            }
        });
        $('input[name="cod_proveedor"]').on('keydown', function(e) {
            // Verifica si la tecla presionada es "Enter"
            if (e.key === 'Enter') {
                e.preventDefault();
                // Enfoca en el campo de fecha
                $('input[name="codigo1[]"]').focus();
            }
        });
        cambiarCod();

        function sanitizeInput(input) {
            // Obtén el valor actual del campo de entrada
            let value = input.value;

            // Elimina cualquier carácter que no sea un número o un punto decimal
            value = value.replace(/[^0-9.]/g, '');

            // Reemplaza comas por puntos para números decimales
            value = value.replace(/,/g, '.');

            // Actualiza el valor del campo de entrada
            input.value = value;
            actualizarSumaTotal();
        }

        function cambiarCod() {

        }

        function actualizarNumeroDocumento() {
            var select2 = document.getElementById("id_proveedor");
            var numeroDocumentoInput = document.getElementById("numero_documento");
            var selectedOption = select2.options[select2.selectedIndex];
            var numeroDocumento = selectedOption.getAttribute("data-ruc");
            numeroDocumentoInput.value = numeroDocumento;
            var cod_proveedor = selectedOption.value;
            document.getElementById("cod_proveedor").value = cod_proveedor;
        }
       

      

        // Script para agregar y eliminar dinámicamente ítems de compra
        const itemsContainer = document.getElementById('items');
        const totalSumElement = document.getElementById('total-sum');
        let totalSum = 0;
        addNewItem();
        // Función para agregar un nuevo ítem de compra
        function addNewItem() {
            const newItem = document.createElement("div");
            newItem.classList.add("item");
            borrar =
                '<button class="btn-remove btn btn-outline-danger ml-2" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>';

            // Construir las opciones del select usando JavaScript
            let selectOptions = '<option value="0">Ninguno</option>';
            links.forEach(function(link) {
                selectOptions += `<option value="${link.id}">${link.descripcion}</option>`;
            });

            newItem.innerHTML = `
        <div class="row ml-1">
            <input type="date" name="fecha[]" class="codigo_id form-control col-2" required>
            <textarea name="texto[]" class="codigo_id form-control col-6" placeholder="Escriba el Texto"></textarea>
            <select class="form-control col-3" name="link[]">
                ${selectOptions}
            </select>
            <button class="btn-remove btn btn-outline-danger ml-2" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </div>
    `;

            const codigoInput = newItem.querySelector('input[name="fecha[]"]');
            codigoInput.addEventListener('keydown', function(e) {
                // Verifica si la tecla presionada es "Enter"
            });

            // Agregar el nuevo elemento al contenedor
            itemsContainer.appendChild(newItem);

            // Agregar el evento click para eliminar el ítem después de que se haya agregado al contenedor
            const btnRemove = newItem.querySelector(".btn-remove");
            btnRemove.addEventListener("click", function() {
                removeItem(newItem);
            });
        }



        function actualizarSumaTotal() {
            totalSum = 0;
            const priceInputs = document.getElementsByName("precio[]"); //trae todos los precios para recorrer
            const cantidadInputs = document.getElementsByName(
                'cantidad[]'); //trae todas las cantidades para recorrer
            const ivaInputs = document.getElementsByName(
                "iva[]"); //trae todos los impuestos para ver si es exenta iva 5 o iva 10
            const exentaInputs = document.getElementsByName(
                "exenta[]"); //trae todos los totales exentas
            const itemInputs = document.getElementsByName(
                "item[]"); //trae todos los totales exentas
            const cincoInputs = document.getElementsByName(
                "cinco[]"); //trae todos los totales cinco
            const diezInputs = document.getElementsByName(
                "diez[]"); //trae todos los totales diez
            // Itera a través de los elementos utilizando un bucle for
            itemN = 0;
            for (let i = 0; i < priceInputs.length; i++) {
                const input = priceInputs[i];
                const price = parseFloat(input.value) || 0;

                const cantidadV = cantidadInputs[i];
                const cantidad = parseFloat(cantidadV.value) || 0;

                const ivaV = ivaInputs[i];
                const iva = parseFloat(ivaV.value) || 0;

                itemN++;
                const itemV = itemInputs[i];
                itemV.value = itemN;

                const exentaV = exentaInputs[i];

                const cincoV = cincoInputs[i];

                const diezV = diezInputs[i];

                tot = cantidad * price;
                switch (iva) {
                    case 0:
                        exentaV.value = tot;
                        cincoV.value = 0;
                        diezV.value = 0;
                        break;
                    case 5:
                        exentaV.value = 0;
                        cincoporc = 0;
                        tot = tot + cincoporc
                        cincoV.value = tot;
                        diezV.value = 0;
                        break;
                    case 10:
                        exentaV.value = 0;
                        cincoV.value = 0;
                        diezporc = 0;
                        tot = tot + diezporc;
                        diezV.value = tot;
                        break;
                    default:
                        // Hacer algo si iva no coincide con ningún caso
                }


                totalSum += tot;
            }

            totalSumElement.textContent = totalSum.toFixed(2); // Mostrar la suma con dos decimales
        }



        function removeItem(itemToRemove) {
            itemsContainer.removeChild(itemToRemove);
            actualizarSumaTotal();
        }

        function cambiarDescripcion(inputCodigo) {
            var codigoValue = inputCodigo.value;
            traerCargarDatosProducto(codigoValue, inputCodigo);
        }

        function cambiarCodigo(inputProducto) {

            // Obtén el valor del producto desde el elemento actual
            var productoId = '';
            if ($(inputProducto).data('ui-autocomplete').selectedItem) {
                productoId = $(inputProducto).data('ui-autocomplete').selectedItem.codigo;
            }
            traerCargarDatosProducto(productoId, inputProducto);
            actualizarSumaTotal();

        }

    
        

        // Agregar el evento click para agregar un nuevo ítem
        //btnAddItem.addEventListener("click", addNewItem);
    </script>
@endpush
