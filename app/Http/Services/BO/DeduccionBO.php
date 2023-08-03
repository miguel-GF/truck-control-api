<?php

namespace App\Http\Services\BO;

use App\Constants\Constantes;
use App\Utils\DateUtil;
use App\Utils\StringUtil;

class DeduccionBO
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
        'cat_deduccion_id' => $datos['catDeduccionId'],
        'cantidad' => $datos['cantidad'] ?? 1,
        'precio' => $datos['precio'],
        'total' => $datos['total'],
        'folio' => $datos['folio'],
        'status' => Constantes::ACTIVO_STATUS,        
        'serie_folio' => "DE" . StringUtil::cadenaPadding($datos['folio']),
        //'registro_autor_id' => "DE" . StringUtil::cadenaPadding($datos['folio']),
        'registro_fecha' => DateUtil::now(),
        //'actualizacion_autor_id' => 
        //'actualizacion_fecha' => DateUtil::now(),
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
        'cat_deduccion_id' => $datos['catDeduccionId'],
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
