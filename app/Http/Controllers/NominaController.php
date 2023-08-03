<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\NominaRequest;
use App\Http\Services\Action\NominaServiceAction;
use App\Http\Services\Data\NominaServiceData;
use Illuminate\Http\Request;

class NominaController extends Controller
{
	/**
	 * listar
	 *
	 * @param  mixed $request
	 * @return mixed
	 */
	public function listar(Request $request)
	{
		try {
			$params = $request->all();
			$nominas = NominaServiceData::listar($params);
			return ApiResponse::success("Datos obtenidos correctamente.", $nominas);
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}

	/**
	 * listar
	 *
	 * @param  mixed $request
	 * @return mixed
	 */
	public function obtenerDetalle(Request $request, $id)
	{
		try {
			$params = $request->all();
			$params['nominaId'] = $id;
			$detalle = NominaServiceData::obtenerDetalle($params);
			return ApiResponse::success("Detalle obtenido correctamente.", $detalle);
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}

	/**
	 * agregar
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function agregar(NominaRequest $request)
	{
		try {
			$params = $request->all();
			$id = NominaServiceAction::agregar($params);
			return ApiResponse::success("Nomina agregado correctamente.", $id);
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}
	/**
	 * cerrarNomina
	 *
	 * @param  mixed $request
	 * @param  mixed $id
	 * @return void
	 */
	public function cerrarNomina(NominaRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			NominaServiceAction::cerrarNomina($params);
			return ApiResponse::success("Nómina cerrada correctamente.");
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}

	/**
	 * eliminar
	 *
	 * @param  mixed $request
	 * @param  mixed $id
	 * @return void
	 */
	public function eliminar(NominaRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			$nomina = NominaServiceAction::eliminar($params);
			return ApiResponse::success("Nomina eliminado correctamente.", $nomina);
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}

	/**
	 * agregar
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function recalcularNomina(NominaRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			NominaServiceAction::recalcularNomina($params);
			return ApiResponse::success("Nómina recalculada correctamente.");
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}
}
