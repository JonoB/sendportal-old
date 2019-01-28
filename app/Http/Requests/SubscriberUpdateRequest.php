<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriberUpdateRequest extends FormRequest
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
        $subscriberId = $this->segment(2);

        return [
            'email' => [
                'required',
                'email', 'max:255',
                Rule::unique('subscribers')->ignore($subscriberId),
            ],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['max:255'],
            'segments' => ['array'],
        ];
    }
}
