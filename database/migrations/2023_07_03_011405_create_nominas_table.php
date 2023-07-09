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
        Schema::create('nominas', function (Blueprint $table) {
            $table->id();  
            $table->integer('folio');
            $table->integer('status');
            $table->string('serie_folio');
            $table->date('inicio_fecha');
            $table->date('fin_fecha');
            $table->double('total_gastos');
            $table->double('total_deducciones');
            $table->double('total');
            $table->string('registro_autor_id')->nullable();
            $table->timestamp('registro_fecha');
            $table->string('actualizacion_autor_id')->nullable();
            $table->timestamp('actualizacion_fecha')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nominas');
    }
};
