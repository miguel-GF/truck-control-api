<?php

namespace App\Http\Requests;

use App\Constants\ApiConstantes;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GastoDirectoRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		$rules = [
			'operadorId' => 'required',
			'catGastoDirectoId' => 'required',
			'cantidad' => 'required',
			'precio' => 'required',
			'total' => 'required',
		];

		if ($this->isMethod('PATCH')) {
			$rules = [
				'cantidad' => 'required',
				'precio' => 'required',
				'total' => 'required',
			];
		}

		if ($this->isMethod('DELETE')) {
			$rules = [];
		}

		return $rules;
	}

	public function messages()
	{
		return [
			'operadorId.required' => 'Operador id es obligatorio.',
			'catGastoDirectoId.required' => 'El cat gasto directo id es obligatorio.',
			'cantidad.required' => 'Cantidad es obligatoria',
			'precio.required' => 'Precio es obligatorio',
			'total.required' => 'Total es requerido',
			// Agrega aquí los mensajes de error personalizados para los demás campos
		];
	}

	protected function failedValidation(Validator $validator)
	{
		$response = ApiResponse::error($validator->errors()->first(), ApiConstantes::ERROR_DATOS_INVALIDOS);
		throw new HttpResponseException($response);
	}
}
