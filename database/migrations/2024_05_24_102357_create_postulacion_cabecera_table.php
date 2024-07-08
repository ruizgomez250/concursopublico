<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('postulacion_cabecera', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            // Otros campos aquí
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('postulacion_cabecera');
    }
};
