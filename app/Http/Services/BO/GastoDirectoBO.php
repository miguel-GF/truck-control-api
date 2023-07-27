<?php

namespace App\Http\Services\BO;

use App\Constants\Constantes;
use App\Utils\DateUtil;
use App\Utils\StringUtil;

class GastoDirectoBO
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
      return [
        'operador_id' => $datos['operadorId'],
        'cat_gasto_directo_id' => $datos['catGastoDirectoId'],
        'cantidad' => $datos['cantidad'] ?? 1,
        'precio' => $datos['precio'],
        'total' => $datos['total'],
        'status' => Constantes::ACTIVO_STATUS,
        'folio' => $datos['folio'],
        'serie_folio' => "GD" . StringUtil::cadenaPadding($datos['folio']),
        'registro_fecha' => DateUtil::now(),
        'aplicacion_fecha' => $datos['aplicacionFecha'],
        'comentario' => $datos['comentario'] ?? null,
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
        'cantidad' => $datos['cantidad'],
        'precio' => $datos['precio'],
        'total' => $datos['total'],
        'aplicacion_fecha' => $datos['aplicacionFecha'],
        'actualizacion_fecha' => DateUtil::now(),
        'comentario' => $datos['comentario'] ?? null,
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
