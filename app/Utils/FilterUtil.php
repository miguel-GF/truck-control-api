<?php

namespace App\Utils;
use Illuminate\Database\Query\Builder;

class FilterUtil
{
  /**
   * Método para validar los filtros de tipo array/multiple y retornarlo 
   * de forma correcta
   * @return array
   */
  public static function parsearArreglo($valor)
  {
    if (!is_array($valor)) {
      $valor = explode(",", $valor);
    }
    return $valor;
  }

  public static function fechasFiltrosQuery(Builder &$query, $inicioColumna, $inicioFecha, $finColumna, $finFecha)
  {
    if (!empty($inicioFecha) && empty($finFecha)) {
      $query->where($inicioColumna, '>=', $inicioFecha);
    } else if (empty($inicioFecha) && !empty($finFecha)) {
      $query->where($finColumna, '<=', $inicioFecha);
    } else if (!empty($inicioFecha) && !empty($finFecha)) {
      $query->where(function ($query) use ($inicioFecha, $finFecha, $inicioColumna, $finColumna) {
          $query->whereBetween($inicioColumna, [$inicioFecha, $finFecha])
              ->orWhereBetween($finColumna, [$inicioFecha, $finFecha]);
      });
    }
  }
}
