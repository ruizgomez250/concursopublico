@extends('adminlte::page')
@section('plugins.Sweetalert2', true)

@section('content_header')
<div class="row">
    <div class="col-6">
        <h1 class="m-0 custom-heading">Postulaciones</h1>
    </div>
    <div class="col-6">
        <a href="{{ route('postulacion.create') }}" class="btn btn-primary" style="float: right;">Nueva Postulacion</a>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" theme="light" striped hoverable with-buttons>
                    @foreach ($cabecera as $row)
                    <tr>
                        <td>{{ $row->codigo }}</td>
                        <td>{{ $row->dependencia }}</td>
                        <td>{{ $row->puesto }}</td>
                        <td>{{ $row->tipo_concurso }}</td>
                        <td>{{ $row->vacancia }}</td>
                        <td>
                            <button class="btn-sm btn-outline-secondary" onclick="window.open('{{ asset('documentos/' . $row->documento) }}', '_blank')">
                                Perfil y Matriz
                            </button>
                            <a href="{{ route('detalleprocesopdf', ['id' => $row->informacion]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                Ver información
                            </a>
                        </td>
                        <td>{{ $row->inicio }}</td>
                        <td>{{ $row->fin }}</td>
                        <td>
                            <form action="{{ route('postulacion.destroy', ['postulacion' => $row->id_cabecera]) }}" method="post" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-outline-secondary delete-button">
                                    <ion-icon name="trash-outline"><i class="fa fa-sm fa-fw fa-trash"></i></ion-icon>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </x-adminlte-datatable>

                <x-adminlte-modal id="detalleModal" title="Detalles de la Compra" theme="light" size="lg">
                    <div>
                        <table class="table table-sm table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">U. Medida</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Precio Unit.</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">IVA %</th>
                                </tr>
                            </thead>
                            <tbody id="detalleContent"></tbody>
                        </table>
                    </div>
                </x-adminlte-modal>

                <x-adminlte-modal id="documentosModal" title="PDF De Documentos" theme="light" size="lg">
                    <div>
                        <table class="table table-sm table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Documento</th>
                                    <th scope="col">PDF</th>
                                </tr>
                            </thead>
                            <tbody id="detalleContent">
                                <tr>
                                    <th scope="col">Orden de Compra</th>
                                    <th scope="col"><a id="ordenCompraPdfLink" href="" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-sm fa-fw fa-file-pdf"></i>
                                        </a></th>
                                </tr>
                                <tr>
                                    <th scope="col">Nota de Recepción</th>
                                    <th scope="col"><a id="recepcionPdfLink" href="" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-sm fa-fw fa-file-pdf"></i>
                                        </a></th>
                                </tr>
                                <tr>
                                    <th scope="col">Solicitud de Bienes y Servicios</th>
                                    <th scope="col"><a id="bienesPdfLink" href="" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-sm fa-fw fa-file-pdf"></i>
                                        </a></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-adminlte-modal>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script>
    $('.delete-button').on('click', function() {
        var form = $(this).closest('.delete-form');
        Swal.fire({
            title: 'Confirmar eliminación',
            text: '¿Estás seguro de que deseas eliminar esta postulación?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Función para mostrar los detalles de la compra en un modal
    $('.ver-detalle-btn').click(function() {
        var compraId = $(this).data('compra-id');
        console.log(compraId);
        $.ajax({
            url: 'compra/' + compraId + '/detalles',
            method: 'GET',
            success: function(response) {
                var detalleHTML = '';
                response.forEach(function(detalle, index) {
                    var iva = detalle.precio - (detalle.cantidad * detalle.precio_unitario);
                    detalleHTML += '<tr>' +
                        '<th scope="row">' + (index + 1) + '</th>' +
                        '<td>' + detalle.productos.unidaddemedida.descripcion + '</td>' +
                        '<td>' + detalle.productos.codigo + '</td>' +
                        '<td>' + detalle.cantidad + '</td>' +
                        '<td>' + detalle.descripcion + '</td>' +
                        '<td>' + detalle.precio_unitario + '</td>' +
                        '<td>' + detalle.precio + '</td>' +
                        '<td>' + detalle.tipo_impuesto + '</td>' +
                        '</tr>';
                });
                $('#detalleContent').html(detalleHTML);
                $('#detalleModal').modal('show');
            },
            error: function() {
                console.log('Error al obtener detalles de la compra');
            }
        });
    });

    // Mostrar mensaje de éxito o error con SweetAlert
    var successMessage = "{{ session('success') }}";
    var errorMessage = "{{ session('error') }}";
    if (successMessage) {
        Swal.fire('Éxito', successMessage, 'success');
    } else if (errorMessage) {
        Swal.fire('Error', errorMessage, 'error');
    }
</script>
@endpush
