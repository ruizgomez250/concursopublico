<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesProcesoDetalle extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'detallesprocesodetalle';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['fecha', 'iddetalle', 'texto', 'link'];


    // Definir la relación con el modelo DetallesProceso
    public function detalleproceso()
    {
        return $this->belongsTo(DetallesProceso::class, 'iddetalle');
    }

    
}
