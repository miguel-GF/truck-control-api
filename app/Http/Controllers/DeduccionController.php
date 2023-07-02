<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\DeduccionRequest;
use App\Http\Services\Action\DeduccionServiceAction;
use App\Http\Services\Data\DeduccionServiceData;
use Illuminate\Http\Request;

class DeduccionController extends Controller
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
			$deducciones = DeduccionServiceData::listar($params);
			return ApiResponse::success("Datos obtenidos correctamente.", $deducciones);
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
	public function agregar(DeduccionRequest $request)
	{
		try {
			$params = $request->all();
			$id = DeduccionServiceAction::agregar($params);
			return ApiResponse::success("Duduccion agregado correctamente.", $id);
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
	public function editar(DeduccionRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			DeduccionServiceAction::editar($params);
			return ApiResponse::success("Deduccion editado correctamente.");
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
	public function eliminar(DeduccionRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			DeduccionServiceAction::eliminar($params);
			return ApiResponse::success("Gasto directo eliminado correctamente.");
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}
}
