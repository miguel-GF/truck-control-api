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
    if (!empty($filtros['catGastosDirectosIds'])) {
      $query->whereIn('gd.cat_gasto_directo_id', $filtros['catGastosDirectosIds']);
    }
  }
}
