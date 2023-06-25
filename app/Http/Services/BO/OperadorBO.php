<?php

namespace App\Http\Services\BO;

use App\Constants\Constantes;
use App\Utils\DateUtil;

class OperadorBO
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
        'nombre' => $datos['nombre'],
        'apellidos' => $datos['apellidos'],
        'clave' => $datos['clave'],
        'telefono' => $datos['telefono'] ?? null,
        'registro_fecha' => DateUtil::now(),
        'status' => Constantes::ACTIVO_STATUS,
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
        'nombre' => $datos['nombre'],
        'apellidos' => $datos['apellidos'],
        'telefono' => $datos['telefono'] ?? null,
        'actualizacion_fecha' => DateUtil::now(),
        'status' => Constantes::ACTIVO_STATUS,
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
  public static function armarDelete(array $datos): array
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
