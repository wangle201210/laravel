<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class AuthRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			// 'phone' => 'required|regex:/^1[34578][0-9]{9}$/',
			'name' => 'required',
			'password' => 'required',
			// 'description' => 'required',
			// 'thumbnail' => 'required_if:type,foo|image',
		];
	}
}
