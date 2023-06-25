<?php

namespace App\Http\Services\Data;

use App\Http\Repos\Data\OperadorRepoData;

class OperadorServiceData
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
			return OperadorRepoData::listar($filtros);
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
