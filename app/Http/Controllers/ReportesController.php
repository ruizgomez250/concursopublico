<?php

namespace App\Http\Controllers;

use TCPDF;
use App\Helpers\NumberToWords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Caja\Arqueo;
use App\Models\DetallesProcesoDetalle;
use App\Models\Transaccion;

class ReportesController extends Controller
{


    function detalleproceso($id)
    {
        $detalles = DB::table('detallesprocesodetalle as pd')
            ->leftJoin('links as l', 'l.id', '=', 'pd.link')
            ->where('pd.iddetalle', $id)
            ->select('pd.fecha', 'pd.texto', 'l.descripcion', 'l.documento')
            ->get();

        // Verifica si se encontraron detalles del proceso detalle
        if ($detalles->isEmpty()) {
            // Maneja el caso en el que no se encontraron detalles
            return response()->json(['message' => 'No se encontraron detalles del proceso detalle para el idcabecera dado'], 404);
        }


        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetPrintHeader(false); // Deshabilita la impresión del encabezado
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->AddPage();
        // Establecer el margen derecho
        $pdf->SetLeftMargin(12); // Ajusta el margen derecho a 10 mm
        //$pdf->Image(public_path('vendor/adminlte/dist/img/coopptoelsa.jpg'), 15, 12, 25);
        $pdf->Ln(25);

        // Establecer título del documento
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetTitle('Detalles del Proceso');

        // Agregar el título en la parte superior del documento
        $pdf->SetFont('helvetica', 'B', 14);
        $pageWidth = $pdf->getPageWidth();
        $textWidth = $pdf->GetStringWidth('Detalles del Proceso');
        $x = ($pageWidth - $textWidth) / 2;

        // Escribir el texto centrado horizontalmente
        $pdf->Text($x, 10, 'Detalles del Proceso');
        $pdf->Ln(); // Añadir una línea de espacio después del título

        // Restablecer la fuente para el contenido del documento
        $pdf->SetFont('helvetica', 'B', 10);

        // Establecer la posición Y inicial para los detalles

        $y = 20; // Ajusta este valor según sea necesario para dar suficiente espacio al título

        // Recorre los detalles del proceso detalle encontrados
        foreach ($detalles as $detalle) {
            $pdf->SetXY(10, $y);
            $formattedDate = date('d/m/Y', strtotime($detalle->fecha));
            $pdf->Text(10, $y, $formattedDate);
            if ($detalle->texto) {
                $y += 5; // Incrementa la posición Y para el próximo elemento

                // Escribe el texto
                $pdf->Text(10, $y, $detalle->texto);
            }

            // Base URL para los documentos
            $baseUrl = 'http://concurso.diputados.gov.py/documentos/';

            // Si hay una descripción, escribe el link
            if ($detalle->descripcion) {
                $y += 5; // Incrementa la posición Y para el próximo elemento
                $pdf->Text(10, $y, 'Puede descargar Aqui');
                $y += 5; // Incrementa la posición Y para el próximo elemento

                // Escribe el enlace utilizando writeHTML
                $documentUrl = $baseUrl . $detalle->documento;
                // Escribir el enlace con target="_blank" para abrir en otra pestaña
                $pdf->writeHTML('<a href="' . $documentUrl . '" target="_blank">' . $detalle->descripcion . '</a>', true, false, false, false, '');

                $y += 5; // Incrementa la posición Y para el próximo elemento
            }

            // Incrementa un poco más la posición Y para separar los bloques
            $y += 10;
        }




        $pdf->Output('detalleproceso.pdf', 'I');
    }
}
