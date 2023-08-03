<?php

namespace App\Http\Services\BO;

use App\Constants\Constantes;
use App\Utils\DateUtil;
use App\Utils\StringUtil;
use stdClass;

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
      $inicioFecha = $datos['inicioFecha'];
      return [
        'folio' => $datos['folio'],
        'status' => Constantes::ACTIVO_STATUS, 
        'serie_folio' => "NO" . StringUtil::cadenaPadding($datos['folio'], "0", 4),
        'inicio_fecha' => $datos['inicioFecha'],
        'fin_fecha' => $datos['finFecha'],
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
   * armarInsertDetalle
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarInsertDetalle(array $datos): array
  {
    try {
      return [
        'operador_id' => $datos['operadorId'],
        'nomina_id' => $datos['nominaId'],
        'status' => Constantes::ACTIVO_STATUS, 
        'total_gastos' => $datos['totalGastos'],
        'total_deducciones' => $datos['totalDeducciones'],
        'total' => $datos['total'],
        //'registro_autor_id' => "",
        'registro_fecha' => DateUtil::now(),
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * armarUpdateDetalleRecalculo
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarUpdateDetalleRecalculo(array $datos): array
  {
    try {
      return [
        'total_gastos' => $datos['totalGastos'],
        'total_deducciones' => $datos['totalDeducciones'],
        'total' => $datos['total'],
        //'actualizacion' => "",
        'actualizacion_fecha' => DateUtil::now(),
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * armarUpdateStatus
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarUpdateStatus(array $datos): array
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

    /**
   * tratarTotalesGastosDeducciones
   *
   * @param  mixed $datos
   * @return array $totales
   */
  public static function tratarTotalesGastosDeducciones(array $datos): array
  {
    try {
      // Tratamos los gastos directos
			$totalGastos = 0;
			$totales['gastosDirectosIds'] = [];
      if (!empty($datos['gastosDirectos'])) {
        $gastosDirectos = collect(json_decode($datos['gastosDirectos']));
        $totalGastos = $gastosDirectos->sum('total');
				$totales['gastosDirectosIds'] = $gastosDirectos->pluck('id')->toArray();
      }
			$totales['totalGastos'] = $totalGastos;

			// Tratamos las deducciones
			$totalDeducciones = 0;
			$totales['deduccionesIds'] = [];
      if (!empty($datos['deducciones'])) {
        $deducciones = collect(json_decode($datos['deducciones']));
        $totalDeducciones = $deducciones->sum('total');
				$totales['deduccionesIds'] = $deducciones->pluck('id')->toArray();
      }
			$totales['totalDeducciones'] = $totalDeducciones;

			$totales['total'] = $totalGastos - $totalDeducciones;

      return $totales;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * armarUpdateAsociarGastoDeduccion
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarUpdateAsociarGastoDeduccion(array $datos): array
  {
    try {
      return [
        'nomina_id' => $datos['nominaId'],
        'detalle_nomina_id' => $datos['detalleNominaId'],
        'actualizacion_fecha' => DateUtil::now(),
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * armarUpdateTotales
   *
   * @param  mixed $datos [esAlta]
   * @param  mixed $totales
   * @return array
   */
  public static function armarUpdateTotales(array $datos, stdClass $totales): array
  {
    try {
      $actualizacionFecha = $datos['esAlta'] == 'si' ? null : DateUtil::now();
      $update = (array) $totales;
      $update['actualizacion_fecha'] = $actualizacionFecha;
      return $update;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * armarUpdateLiberarGastoDeduccion
   *
   * @return array
   */
  public static function armarUpdateLiberarGastoDeduccion(): array
  {
    try {
      return [
        'nomina_id' => null,
        'detalle_nomina_id' => null,
        'actualizacion_fecha' => DateUtil::now(),
      ];
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
