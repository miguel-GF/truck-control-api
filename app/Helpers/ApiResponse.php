<?php

namespace App\Helpers;

use App\Constants\ApiConstantes;

class ApiResponse
{
	/**
	 * Éxito
	 *
	 * @param  mixed $data
	 * @param  mixed $mensaje
	 * @param  mixed $status
	 * @return mixed
	 */
	public static function success($mensaje, $data = null, $status = ApiConstantes::EXITO_STATUS)
	{
		return response()->json([
			'exito' => true,
			'datos' => $data,
			'mensaje' => $mensaje,
		], $status);
	}

	/**
	 * Error
	 *
	 * @param  mixed $mensaje
	 * @param  mixed $status
	 * @return mixed
	 */
	public static function error($mensaje, $status = ApiConstantes::ERROR_STATUS)
	{
		return response()->json([
			'exito' => false,
			'mensaje' => $mensaje
		], $status);
	}
}
