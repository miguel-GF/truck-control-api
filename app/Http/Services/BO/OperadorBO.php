<?php

namespace App\Http\Services\BO;


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
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
