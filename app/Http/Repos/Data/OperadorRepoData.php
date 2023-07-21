<?php

namespace App\Http\Repos\Data;

use App\Http\Repos\RH\OperadorRH;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OperadorRepoData
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
      $query = DB::table('operadores')
        ->select(
          "*",
          DB::raw("CONCAT(nombre,' ',apellidos) as nombre_operador"),
          DB::raw("CASE
            WHEN status = 200 THEN 'Activo'
            WHEN status = 300 THEN 'Eliminado'
            ELSE 'Sin definir'
          END as status_nombre")
        );
      OperadorRH::filtrosListar($query, (array) $filtros);
      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en operadores -> $e");
      throw new Exception("Error al listar operadores");
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
      $result = DB::table('operadores')
        ->selectRaw("COALESCE(MAX(clave), 999) + 1 as maximo")
        ->sharedLock()
        ->first()
        ;
        if ($result) {
          return $result->maximo;
        } else {
            return 1000; // Si no hay registros, se devuelve 1 como valor predeterminado
        }
    } catch (QueryException $e) {
      Log::error("Error de db max en operadores -> $e");
      throw new Exception("Error al insertar operador");
    }
  }
}
