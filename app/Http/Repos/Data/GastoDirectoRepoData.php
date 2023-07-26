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
   * listar catálogo de gastos directos
   *
   * @return array
   */
  public static function listarCatalogo(): array
  {
    try {
      $query = DB::table('cat_gastos_directos')
        ->select(
          'id',
          'clave',
          'nombre',
          'descripcion'
        )
        ->orderBy("clave");
      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en gastos directos catálogo -> $e");
      throw new Exception("Error al listar gastos directos catálogo");
    }
  }

  /**
   * listar general
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listar(array $filtros): array
  {
    try {
      $query = DB::table('gastos_directos as gd')
        ->select(
          'gd.*',
          DB::raw("CASE
            WHEN gd.status = 200 THEN 'Activo'
            WHEN gd.status = 300 THEN 'Eliminado'
            ELSE 'Sin definir'
          END as status_nombre"),
          DB::raw("CONCAT(o.nombre,' ',o.apellidos) as nombre_operador"),
          'cgd.nombre as nombre_gasto_directo'
        )
        ->join('operadores as o', 'o.id', 'gd.operador_id')
        ->join('cat_gastos_directos as cgd', 'cgd.id', 'gd.cat_gasto_directo_id')
        ;
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
