<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    DB::table('cat_gastos_directos')->insert([
        ['clave' => '001', 'nombre' => 'Pago', 'descripcion' => 'Pago sueldo', 'created_at' => '2023-06-25 17:21:25'],
    ]);
}
};
