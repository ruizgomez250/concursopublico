@extends('adminlte::page')

@section('content_header')
    <div class="row">
        <div class="col-6">
            <h1 class="m-0 custom-heading">Lista de Links</h1>
        </div>
        <div class="col-6">
            <a href="{{ route('link.create') }}" class="btn btn-primary" style="float: right;">Registrar Nuevo Link</a>
        </div>
    </div>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@push('js')
    <script>
        $(document).ready(function() {
            $('#table1').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
                }
            }).buttons().container().appendTo('#table1_wrapper .col-md-6:eq(0)');
           



        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <x-adminlte-datatable id="table1" :heads="$heads" head-theme="dark" theme="light" striped
                        hoverable with-buttons>
                        @foreach ($links as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->descripcion }}</td>
                                <td style="float:right;">
                                    <button class="btn-sm btn-info"
                                        onclick="window.open('{{ asset('documentos/' . $row->documento) }}', '_blank')">
                                        @if (pathinfo($row->documento, PATHINFO_EXTENSION) == 'pdf')
                                            <i class="fas fa-file-pdf"></i>
                                        @else
                                            <i class="fas fa-file-word"></i>
                                        @endif
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>
@stop
