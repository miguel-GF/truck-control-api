<?php

namespace App\Http\Services\BO;

use App\Constants\Constantes;
use App\Utils\DateUtil;

class HelperBO
{
  /**
   * armarUpdate
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarDeleteGlobal(array $datos): array
  {
    try {
      return [
        'actualizacion_fecha' => DateUtil::now(),
        'status' => Constantes::INACTIVO_STATUS,
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
