<?php

namespace App\Http\Repos\RH;

use Illuminate\Database\Query\Builder;

class DeduccionRH
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
    if (!empty($filtros['deduccionesIds'])) {
      $query->whereIn('d.id', $filtros['deduccionesIds']);
    }
  }
}
