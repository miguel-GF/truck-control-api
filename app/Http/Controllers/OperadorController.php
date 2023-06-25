<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Services\Data\OperadorServiceData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

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
			return ApiResponse::success($operadores, "Datos obtenidos correctamente.");
		} catch (\Throwable $th) {
			return ApiResponse::error($th->getMessage());
		}

	}
}
