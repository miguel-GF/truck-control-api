<?php

namespace App\Http\Repos\RH;

use Illuminate\Database\Query\Builder;

class NominaRH
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
    if (!empty($filtros['clave'])) {
      $query->where('clave', $filtros['clave']);
    }
  }
}
