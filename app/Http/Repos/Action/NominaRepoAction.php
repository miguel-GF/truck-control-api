<?php

namespace App\Http\Repos\Action;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class NominaRepoAction
{
  const table = "nominas";
  /**
   * agregar
   *
   * @param  mixed $insert
   * @return mixed
   */
  public static function agregar(array $insert)
  {
    try {
      return DB::table(self::table)
        ->insertGetId($insert, 'id');
    } catch (QueryException $e) {
      Log::error("Error de insert en nominas -> $e");
      throw new Exception("Error al agregar nomina");
    }
  }

  /**
   * agregarDetalle
   *
   * @param  mixed $insert
   * @return mixed
   */
  public static function agregarDetalle(array $insert)
  {
    try {
      return DB::table("detalles_nominas")
        ->insertGetId($insert, 'id');
    } catch (QueryException $e) {
      Log::error("Error de insert en detalle nominas -> $e");
      throw new Exception("Error al agregar detalle nomina");
    }
  }

  /**
   * actualizar
   *
   * @param  mixed $update
   * @return mixed
   */
  public static function actualizar(array $update, $id)
  {
    try {
      return DB::table(self::table)
        ->where('id', $id)
        ->update($update);
    } catch (QueryException $e) {
      Log::error("Error de update en nominas -> $e");
      throw new Exception("Error al actualizar nomina");
    }
  }

  /**
   * liberarGastosDeducciones
   *
   * @param  mixed $update
   * @return mixed
   */
  public static function liberarGastosDeducciones(array $update, $nominaId, $table)
  {
    try {
      return DB::table($table)
        ->where('nomina_id', $nominaId)
        ->update($update);
    } catch (QueryException $e) {
      Log::error("Error de update en $table por liberar ids nomina -> $e");
      throw new Exception("Error al liberar $table");
    }
  }

  /**
   * actualizarDetalleRecalculo
   *
   * @param  mixed $update
   * @param  mixed $detalleNominaId
   * @return void
   */
  public static function actualizarDetalle(array $update, $detalleNominaId)
  {
    try {
      DB::table("detalles_nominas")
        ->where('id', $detalleNominaId)
        ->update($update);
    } catch (QueryException $e) {
      Log::error("Error de update en actualizar detalle nomina por recalculo -> $e");
      throw new Exception("Error al liberar actualizar detalle nomina por recalculo");
    }
  }
}
