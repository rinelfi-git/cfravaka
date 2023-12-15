<?php
	
	namespace App\Http\Requests;
	
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\Auth;
	
	class SaveStudentRequest extends FormRequest {
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize() {
			return Auth::check();
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules() {
			return [
				'id'         => ['nullable', 'numeric'],
				'name'       => 'required',
				'email'      => 'nullable|email',
				'phone'      => ['required', 'regex:/^\+261\s3[0-9]{1}\s[0-9]{2}\s[0-9]{3}\s[0-9]{2}$/'],
				'test_date'  => 'nullable|date_format:Y-m-d',
				'level'      => ['nullable', 'numeric'],
				'partners'   => 'nullable|array',
				'partners.*' => 'numeric'
			];
		}
	}
