<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutomationStepUpdateRequest extends FormRequest
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
        return [
            'name' => ['required'],
            'delay' => ['required_if:use-delay,true'],
            'delay_unit' => ['required_if:use-delay,true']
        ];
    }
}
