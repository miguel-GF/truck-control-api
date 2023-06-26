<?php

namespace App\Http\Repos\Data;

use App\Http\Repos\RH\GastoDirectoRH;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class GastoDirectoRepoData
{
  /**
   * listar
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listar(array $filtros): array
  {
    try {
      $query = DB::table('gastos_directos')
        ->select('*');
      GastoDirectoRH::filtrosListar($query, (array) $filtros);
      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en gastos directos -> $e");
      throw new Exception("Error al listar gastos directos");
    }
  }

  /**
   * obtenerMaximo
   *
   * @return mixed
   */
  public static function obtenerMaximo()
  {
    try {
      $result = DB::table('gastos_directos')
        ->selectRaw("COALESCE(MAX(folio), 0) + 1 as maximo")
        ->sharedLock()
        ->first()
        ;
        if ($result) {
          return $result->maximo;
        } else {
            return 1; // Si no hay registros, se devuelve 1 como valor predeterminado
        }
    } catch (QueryException $e) {
      Log::error("Error de db max en gastos directos -> $e");
      throw new Exception("Error al insertar gasto directo");
    }
  }
}
