<?php
	
	namespace App\Http\Requests;
	
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\Auth;
	
	class SaveFormationTypeRequest extends FormRequest {
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
				'id'                            => ['nullable', 'numeric'],
				'name'                          => ['required'],
				'partner_id'                    => ['nullable', 'numeric'],
				'subcategories'                 => ['required', 'array', 'min:1'],
				'subcategories.*.id'            => ['nullable', 'numeric'],
				'subcategories.*.modality'      => ['required', 'string'],
				'subcategories.*.formula'       => ['required', 'string'],
				'subcategories.*.convenience'   => ['required', 'string'],
				'subcategories.*.hourly_volume' => ['required', 'numeric'],
				'subcategories.*.price'         => ['required', 'numeric'],
				'subcategories.*.is_monthly'    => ['required', 'boolean']
			];
		}
		
		protected function prepareForValidation() {
			$data = $this->all();
			foreach ($data['subcategories'] as $key => $value) {
				$data['subcategories'][$key]['is_monthly'] = filter_var($value['is_monthly'], FILTER_VALIDATE_BOOLEAN);
			}
			$this->replace($data);
		}
	}
