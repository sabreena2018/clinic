<?php

namespace App\Http\Requests\Backend\Auth\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ClinicRequest.
 */
class ClinicUserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if (!$this->isMethod('GET') && !$this->isMethod('DELETE')) {
            $rules = [
                'service_location' => 'required|not_in:0',
                'city' => 'required',
                'Tperiod' => 'required|not_in:0',
                'preferred-time' => 'required',
                'date' => 'required',
            ];
        }
        return $rules;
    }
}
