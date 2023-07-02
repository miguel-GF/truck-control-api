<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::table('cat_deducciones')->insert([
            ['clave' => '001', 'nombre' => 'Descuento Diésel', 'descripcion' => 'Falta de combustible', 'created_at' => '2023-07-02 07:00:00'],
            ['clave' => '002', 'nombre' => 'Talacha', 'descripcion' => 'Talacha a neumático', 'created_at' => '2023-07-02 07:00:00'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
