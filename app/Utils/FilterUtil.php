<?php

namespace App\Utils;
use Illuminate\Support\Facades\Log;
use ErrorException;

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
}
