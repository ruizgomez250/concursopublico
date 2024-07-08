<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Descripción -->
    <meta name="description"
        content="La Cámara de Diputados y la Universidad Nacional de Asunción (UNA) firmaron un convenio para la elaboración y corrección de exámenes dentro del concurso público de oposición para 50 nuevos contratos.">

    <!-- Palabras clave -->
    <meta name="keywords"
        content="Cámara de Diputados, Universidad Nacional de Asunción, concurso público, exámenes, transparencia, meritocracia">

    <!-- Autor -->
    <meta name="author" content="Cámara de Diputados">

    <!-- Open Graph Meta Tags para redes sociales -->
    <meta property="og:title" content="Cámara de Diputados y UNA firman convenio de cooperación">
    <meta property="og:description"
        content="Convenio firmado para la elaboración y corrección de exámenes dentro del concurso público de oposición para 50 nuevos contratos.">
    <meta property="og:image" content="{{ asset('images/Dip_Raul_Latorre_Firma_Convenio_UNA_01_850.jpg') }}">
    <meta property="og:url" content="https://concurso.diputados.gov.py">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Cámara de Diputados y UNA firman convenio de cooperación">
    <meta name="twitter:description"
        content="Convenio firmado para la elaboración y corrección de exámenes dentro del concurso público de oposición para 50 nuevos contratos.">
    <meta name="twitter:image" content="{{ asset('images/Dip_Raul_Latorre_Firma_Convenio_UNA_01_850.jpg') }}">

    <!-- Bootstrap CSS desde CDN -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Responsive DataTables CSS -->
<link href="{{ asset('vendor/datatables/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">


    <!-- Otros CSS necesarios -->
    <!-- Styles -->
    <style>
        .visit-counter {
            padding: 15px;
            background-color: #ebeef1;
            /* Fondo azul */
            color: rgb(97, 97, 97);
            /* Texto en color blanco */
            border-radius: 15px;
            /* Bordes redondeados */
            text-align: center;
            /* Texto centrado */
            margin-top: 30px;
            /* Margen superior */
            width: auto;
            /* Ancho automático basado en el contenido */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            /* Sombra para dar profundidad */
        }

        .visit-count {
            font-size: 1.5em;
            /* Tamaño de fuente más grande para el número */
            font-weight: bold;
            /* Texto en negrita */
        }
    </style>

    <!-- Scripts -->
    @yield('scripts')
</head>

<body>

    <div class="container">
        <!-- Contenido de la página -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0">En proceso de Postulación</h3>
                        
                    </div>
                    <div class="card-body table-responsive">
                        <table id="tablapost" class="table table-striped table-bordered">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th>Código</th>
                                    <th>Dependencia</th>
                                    <th>Puesto</th>
                                    <th>Tipo de Concurso</th>
                                    <th>Vacancia</th>
                                    <th>Bases y Condiciones</th>
                                    <th>Inicio Postulación</th>
                                    <th>Fin Postulación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cabecera as $row)
                                    <tr>
                                        <td>{{ $row->codigo }}</td>
                                        <td>{{ $row->dependencia }}</td>
                                        <td>{{ $row->puesto }}</td>
                                        <td>{{ $row->tipo_concurso }}</td>
                                        <td>{{ $row->vacancia }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                onclick="window.open('{{ asset('documentos/' . $row->documento) }}', '_blank')">Perfil
                                                y Matriz</button>
                                            <a href="{{ route('detalleprocesopdf', ['id' => $row->informacion]) }}"
                                                target="_blank" class="btn btn-sm btn-outline-secondary">Ver
                                                Información</a>
                                        </td>
                                        <td>{{ $row->inicio }}</td>
                                        <td>{{ $row->fin }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="visit-counter">
            <p>Total de Visitas:   <span class="visit-count">{{ $visitCount }}</span></p>
        </div>
    </div>

    <!-- jQuery -->







</body>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/responsive.bootstrap4.min.js') }}"></script>



<script>
    $('#tablapost').DataTable({
        "language": {
            "url": "{{ asset('vendor/datatableconc/es-ES.json') }}"
        }
    });
</script>

</html>
