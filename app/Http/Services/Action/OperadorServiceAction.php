<?php

namespace App\Http\Services\Action;

use App\Http\Repos\Action\OperadorRepoAction;
use App\Http\Repos\Data\OperadorRepoData;
use App\Http\Services\BO\OperadorBO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
      Log::info("antes");
      $max = OperadorRepoData::obtenerMaximo();
      $datos['clave'] = $max;
      DB::beginTransaction();
      $insert = OperadorBO::armarInsert($datos);
      DB::commit();
			return OperadorRepoAction::agregar($insert);
		} catch (\Throwable $th) {
      DB::rollBack();
			throw $th;
		}
	}
}
