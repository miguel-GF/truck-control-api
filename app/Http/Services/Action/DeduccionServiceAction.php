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
	 * @return array
	 */
	public static function editar(array $datos): array
	{
		try {
			$deduccion = Deduccion::where('status', Constantes::ACTIVO_STATUS)->find($datos['id']);

			if (empty($deduccion)) {
				throw new Exception('Deduccion no encontrado');
			}
			DB::beginTransaction();
			$update = DeduccionBO::armarUpdate($datos);
			DeduccionRepoAction::actualizar($update, $datos['id']);
			$update['id'] = $datos['id'];
			DB::commit();
			return $update;
		} catch (\Throwable $th) {
			DB::rollBack();
			LogUtil::logException("error", new ErrorException ($th));
			throw new Exception("Ocurrio un error al editar deducción");
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
			$deduccion = Deduccion::where('status', Constantes::INACTIVO_STATUS)->find($datos['id']);

			if (!empty($deduccion)) {
				throw new Exception('Deducción ya fue eliminada anteriormente');
			}
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
			throw new Exception("Ocurrio un error al eliminar deducción");
		}
	}
}
