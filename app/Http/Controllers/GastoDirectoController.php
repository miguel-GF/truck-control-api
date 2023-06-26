<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\GastoDirectoRequest;
use App\Http\Services\Action\GastoDirectoServiceAction;
use App\Http\Services\Data\GastoDirectoServiceData;
use Illuminate\Http\Request;

class GastoDirectoController extends Controller
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
			$gastos = GastoDirectoServiceData::listar($params);
			return ApiResponse::success("Datos obtenidos correctamente.", $gastos);
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
	public function agregar(GastoDirectoRequest $request)
	{
		try {
			$params = $request->all();
			$id = GastoDirectoServiceAction::agregar($params);
			return ApiResponse::success("Gasto directo agregado correctamente.", $id);
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}
	/**
	 * editar
	 *
	 * @param  mixed $request
	 * @param  mixed $id
	 * @return void
	 */
	public function editar(GastoDirectoRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			GastoDirectoServiceAction::editar($params);
			return ApiResponse::success("Gasto directo editado correctamente.");
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
	public function eliminar(GastoDirectoRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			GastoDirectoServiceAction::eliminar($params);
			return ApiResponse::success("Gasto directo eliminado correctamente.");
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}
}
