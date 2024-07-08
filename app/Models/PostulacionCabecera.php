<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostulacionCabecera extends Model
{
    use HasFactory;

    protected $table = 'postulacion_cabecera';
    protected $fillable = [
        'descripcion',
    ];
    public function detalles()
    {
        return $this->hasMany(PostulacionDetalle::class, 'id_cabecera');
    }
}
