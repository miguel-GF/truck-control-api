<?php

namespace App\Http\Services\BO;

use App\Constants\Constantes;
use App\Utils\DateUtil;
use App\Utils\StringUtil;

class NominaBO
{
  /**
   * armarInsert
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarInsert(array $datos): array
  {
    try {
        $inicioFecha = DateUtil::now(false);
      return [
        'folio' => $datos['folio'],
        'status' => Constantes::ACTIVO_STATUS, 
        'serie_folio' => "DE" . StringUtil::cadenaPadding($datos['folio']),
        'inicio_fecha' => $inicioFecha,
        'fin_fecha' => DateUtil::sumarDias($inicioFecha, 5),
        'total_gastos' => 0,
        'total_deducciones' => 0,
        'total' => 0,
        //'registro_autor_id' => "DE" . StringUtil::cadenaPadding($datos['folio']),
        'registro_fecha' => DateUtil::now(),
        //'actualizacion_autor_id' => 
        //'actualizacion_fecha' => DateUtil::now(),
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * armarUpdate
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarUpdate(array $datos): array
  {
    try {
      return [
        'status' => $datos['status'],
        'actualizacion_fecha' => DateUtil::now(),
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
