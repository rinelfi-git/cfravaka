<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest {
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
            'id' => ['nullable', 'numeric'],
            'name' => ['required'],
            'owner' => ['required'],
            'students' => ['nullable', 'array'],
            'students.*' => ['required', 'numeric']
        ];
    }
}
