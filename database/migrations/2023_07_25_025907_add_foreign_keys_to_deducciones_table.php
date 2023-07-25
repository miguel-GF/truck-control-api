<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deducciones', function (Blueprint $table) {
            // Agregar nuevas columnas como llaves foráneas
            $table->unsignedBigInteger('nomina_id')->nullable();
            $table->unsignedBigInteger('detalle_nomina_id')->nullable();
            $table->foreign('nomina_id')->references('id')->on('nominas');
            $table->foreign('detalle_nomina_id')->references('id')->on('detalles_nominas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deducciones', function (Blueprint $table) {
            $table->dropForeign(['nomina_id']);
            $table->dropColumn('nomina_id');
            $table->dropForeign(['detalle_nomina_id']);
            $table->dropColumn('detalle_nomina_id');
        });
    }
};
