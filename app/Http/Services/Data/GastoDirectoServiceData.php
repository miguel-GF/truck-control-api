<?php

namespace App\Http\Services\Data;

use App\Http\Repos\Data\GastoDirectoRepoData;

class GastoDirectoServiceData
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
			return GastoDirectoRepoData::listar($filtros);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
