<?php

namespace App\Http\Repos\Data;

use App\Http\Repos\RH\NominaRH;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class NominaRepoData
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
      $query = DB::table('nominas as n')
        ->select(
          'n.*',
          DB::raw("CASE
            WHEN n.status = 200 THEN 'Activo'
            WHEN n.status = 201 THEN 'Cerrado'
            WHEN n.status = 300 THEN 'Eliminado'
            ELSE 'Sin definir'
          END as status_nombre")
        );
      NominaRH::filtrosListar($query, (array) $filtros);
      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en nominas -> $e");
      throw new Exception("Error al listar nominas");
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
      $result = DB::table('nominas')
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
      Log::error("Error de db max en nominas -> $e");
      throw new Exception("Error al insertar nomina");
    }
  }

  /**
   * listarGastosDeduccionesOperador
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarGastosDeduccionesOperador(array $filtros): array
  {
    try {
      $query = DB::table("operadores as o")
      ->select(
          "o.id",
          "o.nombre",
          DB::raw("(
            SELECT JSON_ARRAYAGG(JSON_OBJECT('id', gd.id, 'total', gd.total, 'aplicacion_fecha', gd.aplicacion_fecha))
              FROM gastos_directos as gd
              WHERE gd.operador_id = o.id AND gd.status = 200 AND gd.nomina_id IS NULL
              AND gd.aplicacion_fecha BETWEEN '{$filtros['inicioFecha']}' AND '{$filtros['finFecha']}'
          ) as gastos_directos"),
          DB::raw("(
            SELECT JSON_ARRAYAGG(JSON_OBJECT('id', d.id, 'total', d.total, 'aplicacion_fecha', d.aplicacion_fecha))
              FROM deducciones as d
              WHERE d.operador_id = o.id AND d.status = 200 AND d.nomina_id IS NULL
              AND d.aplicacion_fecha BETWEEN '{$filtros['inicioFecha']}' AND '{$filtros['finFecha']}'
          ) as deducciones")
      )
      ->where("o.status", 200)
      ->when(isset($filtros['operadorId']), function ($query) use ($filtros) {
        return $query->whereRaw("o.id = {$filtros['operadorId']}");
      })
      ->groupBy("o.id", "o.nombre");

      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en gastos directos operadores -> $e");
      throw new Exception("Error al listar gastos directos operadores");
    }
  }
}
