<?php

namespace App\Http\Repos\RH;

use App\Constants\OrdenarConstantes;
use App\Utils\FilterUtil;
use App\Utils\LogUtil;
use ErrorException;
use Illuminate\Database\Query\Builder;

class OperadorRH
{
  /**
   * filtrosListar
   *
   * @param  mixed $query
   * @param  mixed $filtros
   * @return void
   */
  public static function filtrosListar(Builder &$query, $filtros)
  {
    try {
      //code...
      if (!empty($filtros["clave"])) {
        $query->where("clave", $filtros["clave"]);
      }
  
      if (!empty($filtros["status"])) {
        $query->whereIn("status", FilterUtil::parsearArreglo($filtros["status"]));
      }
  
      switch ($filtros["ordenar"] ?? OrdenarConstantes::NOMBRE_ASC) {
        case OrdenarConstantes::NOMBRE_DESC:
          $query->orderByRaw("nombre_operador DESC");
          break;
        case OrdenarConstantes::CLAVE_ASC:
          $query->orderBy("clave");
          break;
        
        case OrdenarConstantes::CLAVE_DESC:
          $query->orderByDesc("clave");
          break;
  
        case OrdenarConstantes::REGISTRO_ASC:
          $query->orderBy("registro_fecha");
          break;
        
        case OrdenarConstantes::REGISTRO_DESC:
          $query->orderByDesc("registro_fecha");
          break;
        
        default:
          $query->orderByRaw("nombre_operador ASC");
          break;
      }
    } catch (ErrorException $e) {
      LogUtil::logException("error", $e);
      throw $e;
    }
  }
}
