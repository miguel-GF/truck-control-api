<?php

namespace App\Http\Repos\Data;


use App\Http\Repos\RH\DeduccionRH;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class DeduccionRepoData
{
  /**
   * listar catálogo de deducciones
   *
   * @return array
   */
  public static function listarCatalogo(): array
  {
    try {
      $query = DB::table('cat_deducciones')
        ->select(
          'id',
          'clave',
          'nombre',
          'descripcion'
        )
        ->orderBy("clave");
      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en deducciones catálogo -> $e");
      throw new Exception("Error al listar deducciones catálogo");
    }
  }

  /**
   * listar
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listar(array $filtros): array
  {
    try {
      $query = DB::table('deducciones as d')
        ->select(
          'd.*',
          DB::raw("CASE
            WHEN d.status = 200 THEN 'Activo'
            WHEN d.status = 300 THEN 'Eliminado'
            ELSE 'Sin definir'
          END as status_nombre"),
          DB::raw("CONCAT(o.nombre,' ',o.apellidos) as nombre_operador"),
          'cd.nombre as nombre_deduccion',
          // Nomina
          'n.serie_folio as nomina_folio',
          'n.status as nomina_status',
          DB::raw("CASE
            WHEN n.status = 200 THEN 'Activo'
            WHEN n.status = 400 THEN 'Cerrado'
            WHEN n.status = 300 THEN 'Eliminado'
            ELSE 'Sin definir'
          END as nomina_status_nombre")
        )
        ->join('operadores as o', 'o.id', 'd.operador_id')
        ->join('cat_deducciones as cd', 'cd.id', 'd.cat_deduccion_id')
        ->leftJoin('nominas as n', 'n.id', 'd.nomina_id')
        ;
      DeduccionRH::filtrosListar($query, (array) $filtros);
      return $query->get()->toArray();
    } catch (QueryException $e) {
      Log::error("Error de db en deducciones -> $e");
      throw new Exception("Error al listar deducciones");
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
      $result = DB::table('deducciones')
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
      Log::error("Error de db max en deducciones -> $e");
      throw new Exception("Error al insertar deduccion");
    }
  }
}
