<?php

namespace App\Helpers;

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
	public static function success($data, $mensaje, $status = 200)
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
	public static function error($mensaje, $status = 400)
	{
		return response()->json([
			'exito' => false,
			'mensaje' => $mensaje
		], $status);
	}
}
