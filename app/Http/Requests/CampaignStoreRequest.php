<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignStoreRequest extends FormRequest
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
            'name' => [
                'required',
                'max:255'
            ],
            'subject' => [
                'required',
                'max:255'
            ],
            'from_name' => [
                'required',
                'max:255',
            ],
            'from_email' => [
                'required',
                'max:255',
                'email',
            ],
            'provider_id' => [
                'required',
                'integer',
                'exists:providers,id',
            ],
            'template_id' => [
                'required',
                'integer',
                'exists:templates,id',
            ]
        ];
    }
}
