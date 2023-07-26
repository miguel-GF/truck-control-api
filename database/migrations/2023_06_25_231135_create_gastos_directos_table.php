<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gastos_directos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operador_id');
            $table->unsignedBigInteger('cat_gasto_directo_id');
            $table->integer('cantidad')->default(1);
            $table->double('precio');
            $table->double('total');
            $table->integer('folio');
            $table->integer('status');
            $table->string('serie_folio', 10);
            $table->integer('registro_autor_id')->nullable();
            $table->timestamp('registro_fecha');
            $table->integer('actualizacion_autor_id')->nullable();
            $table->timestamp('actualizacion_fecha')->nullable();
            $table->date('aplicacion_fecha');
            // Llaves foráneas
            $table->foreign('operador_id')->references('id')->on('operadores');
            $table->foreign('cat_gasto_directo_id')->references('id')->on('cat_gastos_directos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gastos_directos');
    }
};
