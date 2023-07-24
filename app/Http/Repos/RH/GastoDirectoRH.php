<?php

namespace App\Http\Repos\RH;

use Illuminate\Database\Query\Builder;

class GastoDirectoRH
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
    if (!empty($filtros['gastosDirectosIds'])) {
      $query->whereIn('gd.id', $filtros['gastosDirectosIds']);
    }
  }
}
