<?php

namespace App\Http\Services\Helper;

use App\Constants\Constantes;
use App\Http\Services\Data\NominaServiceData;
use App\Utils\LogUtil;
use ErrorException;
use Exception;

class NominaHelper
{
  /**
   * validarAgregarNominaOperador
   *
   * @param  mixed $datos [inicioFecha, finFecha]
   * @return void
   */
  public static function validarAgregarNominaOperador(array $datos): void
  {
    try {
      $datos['notStatus'] = [Constantes::INACTIVO_STATUS];
      $nominas = NominaServiceData::listar($datos);
      if (!empty($nominas)) {
        throw new Exception ("Ya existe una nómina entre las fechas seleccionadas");
      }
    } catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
    }
  }
}
