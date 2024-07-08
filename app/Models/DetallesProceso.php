<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesProceso extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'detallesprocesos';

    // Campos que se pueden asignar masivamente
    protected $fillable = ['descripcion'];
    public function detalles()
    {
        return $this->hasMany(DetallesProcesoDetalle::class, 'iddetalle');
    }
}
