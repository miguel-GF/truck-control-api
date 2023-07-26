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
        Schema::create('operadores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('apellidos', 150);
            $table->integer('clave');
            $table->string('telefono', 20)->nullable();
            $table->integer('status');
            $table->integer('registro_autor_id')->nullable();
            $table->timestamp('registro_fecha');
            $table->integer('actualizacion_autor_id')->nullable();
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
        Schema::dropIfExists('operadores');
    }
};
