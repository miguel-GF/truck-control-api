<?php

namespace App\Http\Repos\RH;

use App\Utils\FilterUtil;
use App\Utils\LogUtil;
use ErrorException;
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
    try {
      if (!empty($filtros['nominaId'])) {
        $query->where('n.id', $filtros['nominaId']);
      }

      if (!empty($filtros['notStatus'])) {
        $query->whereNotIn('n.status', $filtros['notStatus']);
      }

      FilterUtil::dobleFechaFiltrosQuery(
        $query,
        'n.inicio_fecha',
        $filtros['inicioFecha'] ?? null,
        'n.fin_fecha',
        $filtros['finFecha'] ?? null,
      );
    } catch (ErrorException $e) {
      LogUtil::logException("error", $e);
      throw $e;
    }
  }
}
