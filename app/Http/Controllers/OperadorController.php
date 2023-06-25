<?php

namespace App\Http\Controllers;

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
	 * @return array
	 */
	public function listar(Request $request): array
	{
		try {
			$params = $request->all();
			$operadores = OperadorServiceData::listar($params);
			return $operadores;
		} catch (\Throwable $th) {
			Log::error("Ocurrió un error en método listar operadores $th");
			throw $th;
		}

	}
}
