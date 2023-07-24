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
			LogUtil::log("error", $e);
			throw new Exception("Ocurrio un error al agregar gasto directo");
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
			$gasto = GastoDirecto::where('status', Constantes::ACTIVO_STATUS)->find($datos['id']);

			if (empty($gasto)) {
				throw new Exception('Gasto directo no encontrado');
			}
			DB::beginTransaction();
			$update = GastoDirectoBO::armarUpdate($datos);
			GastoDirectoRepoAction::actualizar($update, $datos['id']);
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
			$gasto = GastoDirecto::where('status', Constantes::INACTIVO_STATUS)->find($datos['id']);

			if (!empty($gasto)) {
				throw new Exception('Gasto directo ya fue eliminado anteriormente');
			}
			DB::beginTransaction();
			$update = HelperBO::armarDeleteGlobal($datos);
			GastoDirectoRepoAction::actualizar($update, $datos['id']);
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
		}
	}
}
