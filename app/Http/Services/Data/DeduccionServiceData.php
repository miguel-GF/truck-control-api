<?php

namespace App\Http\Services\Data;

use App\Http\Repos\Data\DeduccionRepoData;

class DeduccionServiceData
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
			return DeduccionRepoData::listar($filtros);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
