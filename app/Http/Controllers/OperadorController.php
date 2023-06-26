<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\OperadorRequest;
use App\Http\Services\Action\OperadorServiceAction;
use App\Http\Services\Data\OperadorServiceData;
use Illuminate\Http\Request;

class OperadorController extends Controller
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
			$operadores = OperadorServiceData::listar($params);
			return ApiResponse::success("Datos obtenidos correctamente.", $operadores);
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
	public function agregar(OperadorRequest $request)
	{
		try {
			$params = $request->all();
			$id = OperadorServiceAction::agregar($params);
			return ApiResponse::success("Operador agregado correctamente.", $id);
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
	public function editar(OperadorRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			OperadorServiceAction::editar($params);
			return ApiResponse::success("Operador editado correctamente.");
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
	public function eliminar(OperadorRequest $request, $id)
	{
		try {
			$params = $request->all();
			$params['id'] = $id;
			OperadorServiceAction::eliminar($params);
			return ApiResponse::success("Operador eliminado correctamente.");
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}
	}
}
