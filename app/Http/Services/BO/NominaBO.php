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
      $inicioFecha = $datos['inicioFecha'];
      return [
        'folio' => $datos['folio'],
        'status' => Constantes::ACTIVO_STATUS, 
        'serie_folio' => "DE" . StringUtil::cadenaPadding($datos['folio'], "0", 4),
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

    /**
   * armarUpdateAsociarGastoDeduccion
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
        $totalGastos = $deducciones->sum('total');
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
}
