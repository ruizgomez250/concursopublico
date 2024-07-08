<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links';
    protected $fillable = [
        'descripcion',
        'documento',
    ];
    public function detallesProcesoDetalle()
    {
        return $this->hasMany(DetallesProcesoDetalle::class, 'link');
    }
}
