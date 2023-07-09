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
}
