<?php

namespace App\Http\Requests;

use App\Constants\ApiConstantes;
use App\Helpers\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class NominaRequest extends FormRequest
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
			// 'inicioFecha' => 'required',
			// 'finFecha' => 'required',
		];

		if ($this->isMethod('DELETE')) {
			$rules = [];
		}

		return $rules;
	}

	public function messages()
	{
		return [
			'inicioFecha.required' => 'Inicio fecha es obligatorio.',
			'finFecha.required' => 'Fin fecha es obligatorio.',
			// Agrega aquí los mensajes de error personalizados para los demás campos
		];
	}

	protected function failedValidation(Validator $validator)
	{
		$response = ApiResponse::error($validator->errors()->first(), ApiConstantes::ERROR_DATOS_INVALIDOS);
		throw new HttpResponseException($response);
	}
}
