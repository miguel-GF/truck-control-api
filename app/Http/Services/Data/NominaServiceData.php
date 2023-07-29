<?php

namespace App\Http\Services\Data;

use App\Http\Repos\Data\NominaRepoData;
use stdClass;

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

	/**
	 * obtenerDetalle
	 *
	 * @param  mixed $filtros
	 * @return stdClass
	 */
	public static function obtenerDetalle(array $filtros): stdClass
	{
		try {
			$detalle = new stdClass();
			$nomina = NominaRepoData::listar($filtros)[0];
			$detalles = NominaRepoData::listarDetallesNomina($filtros);

			$detalle->datosPrincipales = $nomina;
			$detalle->detalles = $detalles;
			return $detalle;
		} catch (\Throwable $th) {
			throw $th;
		}
	}
}
