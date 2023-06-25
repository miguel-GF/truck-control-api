<?php

namespace App\Http\Repos\Filter;

use Illuminate\Database\Query\Builder;

class OperadorFilter
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
      if (!empty($filtros['clave'])) {
        $query->where('clave', $filtros['clave']);
      }
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
