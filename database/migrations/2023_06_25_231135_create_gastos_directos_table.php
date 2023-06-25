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
            $table->unsignedBigInteger('gasto_directo_id');
            $table->integer('cantidad')->default(1);
            $table->double('total');
            $table->integer('folio');
            $table->integer('status');
            $table->string('serie_y_folio');
            $table->string('registro_autor_id')->nullable();
            $table->timestamp('registro_fecha');
            $table->string('actualizacion_autor_id')->nullable();
            $table->timestamp('actualizacion_fecha')->nullable();

            // Llaves foráneas
            $table->foreign('operador_id')->references('id')->on('operadores');
            $table->foreign('gasto_directo_id')->references('id')->on('cat_gastos_directos');
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
