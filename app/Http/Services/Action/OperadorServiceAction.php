<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\OperadorRepoAction;
use App\Http\Repos\Data\OperadorRepoData;
use App\Http\Services\BO\OperadorBO;
use App\Models\Operador;
use Illuminate\Support\Facades\DB;
use Exception;

class OperadorServiceAction
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
			$max = OperadorRepoData::obtenerMaximo();
			$datos['clave'] = $max;
			DB::beginTransaction();
			$insert = OperadorBO::armarInsert($datos);
			$id = OperadorRepoAction::agregar($insert);
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
			$operador = Operador::where('status', Constantes::ACTIVO_STATUS)->find($datos['id']);

			if (empty($operador)) {
				throw new Exception('Operador no encontrado');
			}
			DB::beginTransaction();
			$update = OperadorBO::armarUpdate($datos);
			OperadorRepoAction::actualizar($update, $datos['id']);
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
			$operador = Operador::where('status', Constantes::INACTIVO_STATUS)->find($datos['id']);

			if (!empty($operador)) {
				throw new Exception('Operador ya fue eliminado anteriormente');
			}
			DB::beginTransaction();
			$update = OperadorBO::armarDelete($datos);
			OperadorRepoAction::actualizar($update, $datos['id']);
			DB::commit();
		} catch (\Throwable $th) {
			DB::rollBack();
			throw $th;
		}
	}
}
