<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\DeduccionRepoAction;
use App\Http\Repos\Action\GastoDirectoRepoAction;
use App\Http\Repos\Action\NominaRepoAction;
use App\Http\Repos\Data\NominaRepoData;
use App\Http\Services\BO\HelperBO;
use App\Http\Services\BO\NominaBO;
use App\Http\Services\Helper\NominaHelper;
use App\Models\Nomina;
use App\Utils\DateUtil;
use App\Utils\LogUtil;
use ErrorException;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;

class NominaServiceAction
{
	/**
	 * agregar
	 *
	 * @param  mixed $datos
	 * @return array
	 */
	public static function agregar(array $datos): array
	{
		try {
			NominaHelper::validarAgregarNominaOperador($datos);
			DB::beginTransaction();

			// obtener maximo de la tabla nominas
			$max = NominaRepoData::obtenerMaximo();
			$datos['folio'] = $max;

			// insert principal de nómina
			$insert = NominaBO::armarInsert($datos);
			$id = NominaRepoAction::agregar($insert);		

			// Se agregan los detalles de nomina
			self::agregarDetalles([
				'nominaId' => $id,
				'inicioFecha' => $datos['inicioFecha'],
				'finFecha' => $datos['finFecha'],
			]);

			$totales = self::calcularTotalesNominaService([
				'nominaId' => $id,
				'esAlta' => 'si',
			]);

			$insert['id'] = $id;
			$insert['status_nombre'] = "Activo";
			$insert = array_merge($insert, (array) $totales);
			DB::commit();
			return $insert;
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			DB::rollBack();
			throw $e;
		} catch (ErrorException $e) {
			LogUtil::logException("error", $e);
			DB::rollBack();
			throw $e;
		}
	}
	
	/**
	 * agregarDetalles
	 *
	 * @param  mixed $datos [nominaId]
	 * @return void
	 */
	public static function agregarDetalles(array $datos)
	{
		try {
			// obtenemos operadores con sus gastos y deducciones asociadas
			$operadores = NominaRepoData::listarGastosDeduccionesOperador($datos);

			if (empty($operadores)) {
				throw new Exception("Debe existir al menos un operador");
			}

			foreach ($operadores as $operador) {
				self::agregarDetalleService([
					'nominaId' => $datos['nominaId'],
					'operadorId' => $operador->id,
					'gastosDirectos' => $operador->gastos_directos,
					'deducciones' => $operador->deducciones,
				]);
			}
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
		}
	}
	
	/**
	 * agregarDetalleService
	 *
	 * @param  mixed $datos [nominaId, operadorId, gastosDirectos, deducciones]
	 * @return void
	 */
	public static function agregarDetalleService(array $datos, $esAlta = true, $detalleNominaId = null)
	{
		try {
			// Tratamos los gastos directos y deducciones
			$totales = NominaBO::tratarTotalesGastosDeducciones($datos);
			$gastosDirectosIds = $totales['gastosDirectosIds'];
			$deduccionesIds = $totales['deduccionesIds'];
			
			unset($totales['gastosDirectosIds']);
			unset($totales['deduccionesIds']);
			
			$datos = array_merge($datos, $totales);
			
			// Se arma insert detalle y se agrega caso contrario solo se actualiza
			if ($esAlta) {
				$insert = NominaBO::armarInsertDetalle($datos);
				$detalleNominaId = NominaRepoAction::agregarDetalle($insert);
			} else {
				$updateDetalle = NominaBO::armarUpdateDetalleRecalculo($datos);
				NominaRepoAction::actualizarDetalle($updateDetalle, $detalleNominaId);
			}
			
			// Se agregan las nominas ids en gastos
			self::asociarGastosDirectosNomina([
				'nominaId' => $datos['nominaId'],
				'detalleNominaId' => $detalleNominaId,
				'gastosDirectosIds' => $gastosDirectosIds,
			]);
			
			// Se agregan las nominas ids en deducciones
			self::asociarDeduccionesNomina([
				'nominaId' => $datos['nominaId'],
				'detalleNominaId' => $detalleNominaId,
				'deduccionesIds' => $deduccionesIds,
			]);
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
		}
	}

	public static function asociarGastosDirectosNomina(array $datos)
	{
		try {
			foreach ($datos['gastosDirectosIds'] as $gastoDirectoId) {
				$update = NominaBO::armarUpdateAsociarGastoDeduccion($datos);
				GastoDirectoRepoAction::actualizar($update, $gastoDirectoId);
			}
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
		}
	}

	public static function asociarDeduccionesNomina(array $datos)
	{
		try {
			foreach ($datos['deduccionesIds'] as $deduccionId) {
				$update = NominaBO::armarUpdateAsociarGastoDeduccion($datos);
				DeduccionRepoAction::actualizar($update, $deduccionId);
			}
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
		}
	}

	/**
	 * editar
	 *
	 * @param  mixed $datos
	 * @return void
	 */
	public static function cerrarNomina(array $datos)
	{
		try {
			$operador = Nomina::where('status', Constantes::ACTIVO_STATUS)->find($datos['id']);

			if (empty($operador)) {
				throw new Exception('Nomina no encontrado');
			}
			DB::beginTransaction();
			$update = NominaBO::armarUpdateStatus($datos);
			NominaRepoAction::actualizar($update, $datos['id']);
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
		}
	}

	/**
	 * eliminar
	 *
	 * @param  mixed $datos
	 * @return array
	 */
	public static function eliminar(array $datos): array
	{
		try {
			$nomina = Nomina::where('status', Constantes::INACTIVO_STATUS)->find($datos['id']);

			if (!empty($nomina)) {
				throw new Exception('Nomina ya fue eliminado anteriormente');
			}
			DB::beginTransaction();

			// Actualizamos status de nomina
			$update = HelperBO::armarDeleteGlobal($datos);
			NominaRepoAction::actualizar($update, $datos['id']);
			$nominaOperadorObj['id'] = $datos['id'];
			$nominaOperadorObj['status'] = $update['status'];
			$nominaOperadorObj['status_nombre'] = "Eliminado";

			// Liberamos gastos y deducciones
			self::liberarGastosDirectosService(["nominaId" => $datos["id"]]);
			self::liberarDeduccionesService(["nominaId" => $datos["id"]]);
			
			DB::commit();
			return $nominaOperadorObj;
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
		}
	}

	/**
	 * calcularTotalesNominaService
	 *
	 * @param  mixed $datos [nominaId, esAlta]
	 * @return stdClass
	 */
	public static function calcularTotalesNominaService(array $datos): stdClass
	{
		try {
			$totales = NominaRepoData::obtenerTotalNomina([
				'nominaId' => $datos['nominaId']
			]);
			$update = NominaBO::armarUpdateTotales($datos, $totales);
			NominaRepoAction::actualizar($update, $datos['nominaId']);
			return $totales;
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
		}
	}

	/**
	 * recalcularNomina
	 *
	 * @param  mixed $datos [id]
	 * @return void
	 */
	public static function recalcularNomina(array $datos): void
	{
		try {
			DB::beginTransaction();

			// Liberamos gastos y deducciones
			self::liberarGastosDirectosService(["nominaId" => $datos["id"]]);
			self::liberarDeduccionesService(["nominaId" => $datos["id"]]);
			
			// Obtenemos detalles de nomina y operadores asociados id
			$detallesNomina = NominaRepoData::listarDetallesNomina(["nominaId" => $datos["id"]]);
			$operadoresIds = collect($detallesNomina)->pluck("operador_id")->toArray();
			$nomina = NominaRepoData::listar(["nominaId" => $datos["id"]])[0];
			
			// Obtenemos operadores con sus gastos y deducciones asociadas
			$operadores = NominaRepoData::listarGastosDeduccionesOperador([
				"inicioFecha" => $nomina->inicio_fecha,
				"finFecha" => $nomina->fin_fecha,
				"operadoresIds" => $operadoresIds,
			]);

			// Actualizamos detalles
			foreach ($operadores as $operador) {
				$detalleNominaId = current(array_filter($detallesNomina, function ($dn) use ($operador) {
					return $dn->operador_id == $operador->id;
				}))->id;
				self::agregarDetalleService([
					'nominaId' => $datos['id'],
					'operadorId' => $operador->id,
					'gastosDirectos' => $operador->gastos_directos,
					'deducciones' => $operador->deducciones,
				],
				false,
				$detalleNominaId
				);
			}

			// calculamos sus nuevos totales
			self::calcularTotalesNominaService([
				'nominaId' => $datos['id'],
				'esAlta' => 'no',
			]);

			DB::commit();
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			DB::rollBack();
			throw $e;
		}
	}
	
	/**
	 * liberarGastosDirectosService
	 *
	 * @param  mixed $datos [nominaId]
	 * @return void
	 */
	public static function liberarGastosDirectosService(array $datos)
	{
		try {
			$update = NominaBO::armarUpdateLiberarGastoDeduccion();
			NominaRepoAction::liberarGastosDeducciones($update, $datos["nominaId"], "gastos_directos");
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
		}
	}

	/**
	 * liberarDeduccionesService
	 *
	 * @param  mixed $datos [nominaId]
	 * @return void
	 */
	public static function liberarDeduccionesService(array $datos)
	{
		try {
			$update = NominaBO::armarUpdateLiberarGastoDeduccion();
			NominaRepoAction::liberarGastosDeducciones($update, $datos["nominaId"], "deducciones");
		} catch (Exception $e) {
			LogUtil::logException("error", new ErrorException ($e->getMessage()));
			throw $e;
		}
	}
}
