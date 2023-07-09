<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\NominaRepoAction;
use App\Http\Repos\Data\NominaRepoData;
use App\Http\Services\BO\HelperBO;
use App\Http\Services\BO\NominaBO;
use App\Models\Nomina;
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
