<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\GastoDirectoRepoAction;
use App\Http\Repos\Data\GastoDirectoRepoData;
use App\Http\Services\BO\GastoDirectoBO;
use App\Http\Services\BO\HelperBO;
use App\Models\GastoDirecto;
use App\Utils\LogUtil;
use ErrorException;
use Illuminate\Support\Facades\DB;
use Exception;
use stdClass;

class GastoDirectoServiceAction
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
			DB::beginTransaction();
			$max = GastoDirectoRepoData::obtenerMaximo();
			$datos['folio'] = $max;
			$insert = GastoDirectoBO::armarInsert($datos);
			$id = GastoDirectoRepoAction::agregar($insert);
			$gastoDirecto = GastoDirectoRepoData::listar([
				'gastosDirectosIds' => [$id]
			])[0];
			DB::commit();
			return $gastoDirecto;
		} catch (ErrorException $e) {
			DB::rollBack();
			LogUtil::logException("error", $e);
			throw new Exception("Ocurrio un error al agregar gasto directo");
		}
	}
	
	/**
	 * editar
	 *
	 * @param  mixed $datos
	 * @return stdClass
	 */
	public static function editar(array $datos): stdClass
	{
		try {
			self::validarEditarEliminar($datos);
			DB::beginTransaction();
			$update = GastoDirectoBO::armarUpdate($datos);
			GastoDirectoRepoAction::actualizar($update, $datos['id']);
			$gastoDirecto = GastoDirectoRepoData::listar([
				'gastosDirectosIds' => [$datos['id']]
			])[0];
			DB::commit();
			return $gastoDirecto;
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
			self::validarEditarEliminar($datos);
			DB::beginTransaction();
			$update = HelperBO::armarDeleteGlobal($datos);
			GastoDirectoRepoAction::actualizar($update, $datos['id']);
			$gastoObj['status'] = $update['status'];
			$gastoObj['status_nombre'] = "Eliminado";
			$gastoObj['id'] = $datos['id'];
			DB::commit();
			return $gastoObj;
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
		}
	}

	/***** METODOS DE VALIDACION ANTES DE UNA ACCION*********/
	private static function validarEditarEliminar($datos)
	{
		try {
			$gasto = GastoDirectoRepoData::listar(['gastosDirectosIds' => [$datos['id']]]);

			if (empty($gasto)) {
				throw new Exception('Gasto directo no encontrado');
			}

			if ($gasto[0]->status == Constantes::INACTIVO_STATUS) {
				throw new Exception('Gasto directo ya fue eliminado anteriormente');
			}

			if (!empty($gasto[0]->nomina_id) && $gasto[0]->nomina_status == Constantes::CERRADO_FINALIZADO_STATUS) {
				throw new Exception("No se puede eliminar el gasto ya que se encuentra en la nómina {$gasto[0]->nomina_folio} con status {$gasto[0]->nomina_status_nombre}");
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
