<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\DeduccionRepoAction;
use App\Http\Repos\Data\DeduccionRepoData;
use App\Http\Services\BO\DeduccionBO;
use App\Http\Services\BO\HelperBO;
use App\Models\Deduccion;
use App\Utils\LogUtil;
use ErrorException;
use Illuminate\Support\Facades\DB;
use Exception;
use stdClass;

class DeduccionServiceAction
{
	/**
	 * agregar
	 *
	 * @param  mixed $datos
	 * @return stdClass
	 */
	public static function agregar(array $datos): stdClass
	{
		try {
			DB::beginTransaction();
			$max = DeduccionRepoData::obtenerMaximo();
			$datos['folio'] = $max;
			$insert = DeduccionBO::armarInsert($datos);
			$id = DeduccionRepoAction::agregar($insert);
			$deduccion = DeduccionRepoData::listar([
				'deduccionesIds' => [$id]
			])[0];
			DB::commit();
			return $deduccion;
		} catch (\Throwable $th) {
			DB::rollBack();
			LogUtil::logException("error", new ErrorException ($th));
			throw new Exception("Ocurrio un error al agregar deducción");
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
			$update = DeduccionBO::armarUpdate($datos);
			DeduccionRepoAction::actualizar($update, $datos['id']);
			$deduccion = DeduccionRepoData::listar([
				'deduccionesIds' => [$datos['id']]
			])[0];
			DB::commit();
			return $deduccion;
		} catch (\Throwable $th) {
			DB::rollBack();
			LogUtil::logException("error", new ErrorException ($th));
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
			DeduccionRepoAction::actualizar($update, $datos['id']);
			$deduccionObj['status'] = $update['status'];
			$deduccionObj['status_nombre'] = "Eliminado";
			$deduccionObj['id'] = $datos['id'];
			DB::commit();
			return $deduccionObj;
		} catch (\Throwable $th) {
			DB::rollBack();
			LogUtil::logException("error", new ErrorException ($th));
			throw $th;
		}
	}

	/***** METODOS DE VALIDACION ANTES DE UNA ACCION*********/
	private static function validarEditarEliminar($datos)
	{
		try {
			$deduccion = DeduccionRepoData::listar(['deduccionesIds' => [$datos['id']]]);

			if (empty($deduccion)) {
				throw new Exception('Deducción no encontrada');
			}

			if ($deduccion[0]->status == Constantes::INACTIVO_STATUS) {
				throw new Exception('Deducción ya fue eliminada anteriormente');
			}

			if (!empty($deduccion[0]->nomina_id) && $deduccion[0]->nomina_status == Constantes::CERRADO_FINALIZADO_STATUS) {
				throw new Exception("No se puede eliminar la deducción ya que se encuentra en la nómina {$deduccion[0]->nomina_folio} con status {$deduccion[0]->nomina_status_nombre}");
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
