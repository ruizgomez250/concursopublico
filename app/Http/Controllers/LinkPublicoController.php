<?php

namespace App\Http\Controllers;

use App\Models\PostulacionDetalle;
use Illuminate\Http\Request;
use App\Models\Visit;
class LinkPublicoController extends Controller
{
    public function index()
    {
        $visitCount = Visit::count();
        $cabecera = PostulacionDetalle::join('links', 'links.id', '=', 'postulacion_detalle.perfil')
    ->select('postulacion_detalle.codigo', 'postulacion_detalle.dependencia', 'postulacion_detalle.puesto', 'postulacion_detalle.tipo_concurso', 'postulacion_detalle.vacancia', 'links.documento', 'postulacion_detalle.informacion', 'postulacion_detalle.inicio', 'postulacion_detalle.fin', 'postulacion_detalle.estado')
    ->where('postulacion_detalle.estado', 1)
    ->get();
        $heads = [
            'Perfil', 'Dependencia', 'Profesión', 'Tipo de Concurso', 'Vacancia', 'Bases y Condiciones', 'Inicio de la Postulacion', 'Fin de la Postulacion'
        ];
        return view('linkpublico.index', compact('cabecera', 'heads','visitCount'));
        //return view('noexiste.index', compact('cabecera', 'heads','visitCount'));
    }
}
