<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\DeduccionRepoAction;
use App\Http\Repos\Data\DeduccionRepoData;
use App\Http\Services\BO\DeduccionBO;
use App\Http\Services\BO\HelperBO;
use App\Models\Deduccion;
use Illuminate\Support\Facades\DB;
use Exception;

class DeduccionServiceAction
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
			$max = DeduccionRepoData::obtenerMaximo();
			$datos['folio'] = $max;
			DB::beginTransaction();
			$insert = DeduccionBO::armarInsert($datos);
			$id = DeduccionRepoAction::agregar($insert);
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
			$gasto = Deduccion::where('status', Constantes::ACTIVO_STATUS)->find($datos['id']);

			if (empty($gasto)) {
				throw new Exception('Gasto directo no encontrado');
			}
			DB::beginTransaction();
			$update = DeduccionBO::armarUpdate($datos);
			DeduccionRepoAction::actualizar($update, $datos['id']);
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
			$gasto = Deduccion::where('status', Constantes::INACTIVO_STATUS)->find($datos['id']);

			if (!empty($gasto)) {
				throw new Exception('Gasto directo ya fue eliminado anteriormente');
			}
			DB::beginTransaction();
			$update = HelperBO::armarDeleteGlobal($datos);
			DeduccionRepoAction::actualizar($update, $datos['id']);
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
		}
	}
}
