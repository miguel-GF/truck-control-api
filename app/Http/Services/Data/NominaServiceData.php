<?php

namespace App\Http\Services\Data;

use App\Http\Repos\Data\NominaRepoData;

class NominaServiceData
{
	/**
	 * listar
	 *
	 * @param  mixed $filtros
	 * @return array
	 */
	public static function listar(array $filtros): array
	{
		try {
			return NominaRepoData::listar($filtros);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
