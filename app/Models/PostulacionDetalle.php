<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulacionDetalle extends Model
{
    use HasFactory;

    protected $table = 'postulacion_detalle';
    protected $fillable = ['id_cabecera', 'codigo', 'dependencia', 'puesto', 'tipo_concurso', 'vacancia', 'perfil', 'informacion', 'inicio', 'fin', 'estado'];
    public function cabecera()
    {
        return $this->belongsTo(PostulacionCabecera::class, 'id_cabecera');
    }

    public function perfil()
    {
        return $this->belongsTo(Link::class, 'perfil');
    }

    public function informacion()
    {
        return $this->belongsTo(DetallesProceso::class, 'informacion');
    }
}
