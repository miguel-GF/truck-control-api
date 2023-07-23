<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\NominaRepoAction;
use App\Http\Repos\Data\GastoDirectoRepoData;
use App\Http\Repos\Data\NominaRepoData;
use App\Http\Services\BO\HelperBO;
use App\Http\Services\BO\NominaBO;
use App\Models\Nomina;
use App\Utils\LogUtil;
use ErrorException;
use Illuminate\Support\Facades\DB;
use Exception;

class NominaServiceAction
{
	/**
	 * agregar
	 *
	 * @param  mixed $datos
	 * @return array
	 */
	public static function agregar(array $datos)
	{
		try {
			$max = NominaRepoData::obtenerMaximo();
			$datos['folio'] = $max;
			DB::beginTransaction();
			$insert = NominaBO::armarInsert($datos);
			$id = NominaRepoAction::agregar($insert);		
			self::agregarDetalles([
				'nominaId' => $id
			]);
			DB::commit();
			return $id;
		} catch (ErrorException $e) {
			LogUtil::log("error", $e);
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
			$operadores = GastoDirectoRepoData::listarGastosOperador([]);

			if (empty($operadores)) {
				throw new Exception("Debe existir al menos un operador");
			}

			foreach ($operadores as $operador) {
				self::agregarDetalleService([
					'nominaId' => $datos['nominaId'],
					'operadorId' => $operador->id,
				]);
			}
		} catch (ErrorException $e) {
			LogUtil::log("error", $e);
			throw $e;
		}
	}
	
	/**
	 * agregarDetalleService
	 *
	 * @param  mixed $datos [nominaId, operadorId]
	 * @return void
	 */
	public static function agregarDetalleService(array $datos)
	{
		try {
			$insert = NominaBO::armarInsertDetalle($datos);
			NominaRepoAction::agregarDetalle($insert);
		} catch (ErrorException $e) {
			LogUtil::log("error", $e);
			throw $e;
		}
	}

	/**
	 * editar
	 *
	 * @param  mixed $datos
	 * @return void
	 */
	public static function editar(array $datos)
	{
		try {
			$operador = Nomina::where('status', Constantes::ACTIVO_STATUS)->find($datos['id']);

			if (empty($operador)) {
				throw new Exception('Nomina no encontrado');
			}
			DB::beginTransaction();
			$update = NominaBO::armarUpdate($datos);
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
	 * @return void
	 */
	public static function eliminar(array $datos)
	{
		try {
			$nomina = Nomina::where('status', Constantes::INACTIVO_STATUS)->find($datos['id']);

			if (!empty($nomina)) {
				throw new Exception('Nomina ya fue eliminado anteriormente');
			}
			DB::beginTransaction();
			$update = HelperBO::armarDeleteGlobal($datos);
			NominaRepoAction::actualizar($update, $datos['id']);
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
		}
	}
}
