<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('postulacion_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_cabecera')->constrained('postulacion_cabecera');
            $table->string('codigo');
            $table->string('dependencia');
            $table->string('puesto');
            $table->string('tipo_concurso');
            $table->integer('vacancia');
            $table->foreignId('perfil')->constrained('links');
            $table->foreignId('informacion')->constrained('detallesprocesos');
            $table->date('inicio');
            $table->date('fin');
            $table->integer('estado');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('postulacion_detalle');
    }
};
