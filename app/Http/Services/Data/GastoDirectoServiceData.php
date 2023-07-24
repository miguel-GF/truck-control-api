<?php

namespace App\Http\Services\Data;

use App\Http\Repos\Data\GastoDirectoRepoData;

class GastoDirectoServiceData
{
	/**
	 * listar catálogo
	 *
	 * @return array
	 */
	public static function listarCatalogo(): array
	{
		try {
			return GastoDirectoRepoData::listarCatalogo();
		} catch (\Throwable $th) {
			throw $th;
		}
	}

	/**
	 * listar general
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
