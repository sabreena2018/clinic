<?php

namespace App\Http\Requests\Backend\Auth\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NurseRequest.
 */
class NurseRegRequest extends FormRequest
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
        if(!$this->isMethod('GET')){
            $rules = [
                'dateFrom' => 'required',
                'dateTo' => 'required',
                'city' => 'required',
            ];
        }
        return $rules;
    }
}
