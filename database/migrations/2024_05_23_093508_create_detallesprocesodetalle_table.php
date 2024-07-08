<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('detallesprocesodetalle', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('iddetalle');
            $table->text('texto')->nullable();
            $table->text('link')->nullable();
            $table->timestamps();

            // Definición de la clave foránea
            $table->foreign('iddetalle')->references('id')->on('detallesprocesos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detallesprocesodetalle');
    }
};
