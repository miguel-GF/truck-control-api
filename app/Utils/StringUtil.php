<?php

namespace App\Utils;

class StringUtil
{
  /**
   * Método para rellenar una string con caracteres deseados, longitud y dirección que desees
   *
   * @return mixed
   */
  public static function cadenaPadding($cadena, $caracterRellenar = "0", $longitud = 5, $direccion = STR_PAD_LEFT,)
  {
    return str_pad($cadena, $longitud, $caracterRellenar, $direccion);
  }
}
