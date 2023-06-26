<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\GastoDirectoRepoAction;
use App\Http\Repos\Data\GastoDirectoRepoData;
use App\Http\Services\BO\GastoDirectoBO;
use App\Http\Services\BO\HelperBO;
use App\Models\GastoDirecto;
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
			$max = GastoDirectoRepoData::obtenerMaximo();
			$datos['folio'] = $max;
			DB::beginTransaction();
			$insert = GastoDirectoBO::armarInsert($datos);
			$id = GastoDirectoRepoAction::agregar($insert);
			DB::commit();
			return $id;
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
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
