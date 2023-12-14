<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SessionRequest extends FormRequest {
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
            'label' => ['nullable'],
            'start_date' => ['required', 'date_format:Y-m-d\TH:i:sP'],
            'end_date' => ['required', 'date_format:Y-m-d\TH:i:sP'],
            'place' => ['required', 'numeric']
        ];
    }
}
