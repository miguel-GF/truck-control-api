<?php

namespace App\Http\Services\Action;

use App\Constants\Constantes;
use App\Http\Repos\Action\OperadorRepoAction;
use App\Http\Repos\Data\OperadorRepoData;
use App\Http\Services\BO\HelperBO;
use App\Http\Services\BO\OperadorBO;
use App\Models\Operador;
use App\Utils\LogUtil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ErrorException;
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
			$insert['id'] = $id;
			$insert['nombre_operador'] = "{$insert['nombre']} {$insert['apellidos']}";
			$insert['status_nombre'] = "Activo";
			
			DB::commit();
			return $insert;
		} catch (ErrorException $e) {
			DB::rollBack();
			LogUtil::log("error", $e);
			throw new Exception("Ocurrio un error al agregar operador");
		}
	}

	/**
	 * editar
	 *
	 * @param  mixed $datos
	 * @return array
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
			$operador['nombre_operador'] = "{$update['nombre']} {$update['apellidos']}";
			$operador['nombre'] = $update['nombre'];
			$operador['apellidos'] = $update['apellidos'];
			$operador['telefono'] = $update['telefono'];
			$operador['id'] = $datos['id'];
			
			DB::commit();
			return $operador;
		} catch (ErrorException $e) {
			DB::rollBack();
			LogUtil::log("error", $e);
			throw new Exception("Ocurrio un error al editar operador");
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
			$operador = Operador::where('status', Constantes::INACTIVO_STATUS)->find($datos['id']);

			if (!empty($operador)) {
				throw new Exception('Operador ya fue eliminado anteriormente');
			}
			DB::beginTransaction();
			$update = HelperBO::armarDeleteGlobal($datos);
			OperadorRepoAction::actualizar($update, $datos['id']);
			$operador['status'] = $update['status'];
			$operador['status_nombre'] = "Eliminado";
			$operador['id'] = $datos['id'];
			
			DB::commit();
			return $operador;
		} catch (ErrorException $e) {
			DB::rollBack();
			LogUtil::log("error", $e);
			throw new Exception("Ocurrio un error al eliminar operador");
		}
	}
}
