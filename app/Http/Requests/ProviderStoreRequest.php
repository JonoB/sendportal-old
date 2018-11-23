<?php

namespace App\Http\Requests;

use App\Models\ProviderType;
use Illuminate\Foundation\Http\FormRequest;

class ProviderStoreRequest extends FormRequest
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
            'type_id' => ['required', 'integer'],

            'key' => ['required'],
            'secret' => ['required_if:type_id,' . ProviderType::AWS_SNS],
            'region' => ['required_if:type_id,' . ProviderType::AWS_SNS],
            'configuration_set_name' => ['required_if:type_id,' . ProviderType::AWS_SNS],

            'domain' => ['required_if:type_id,' . ProviderType::MAILGUN]
        ];
    }

    /**
     * Get the validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'secret.required_if' => 'The AWS Provider requires you to enter a secret',
            'region.required_if' => 'The AWS Provider requires you to enter a region',
            'configuration_set_name.required_if' => 'The AWS Provider requires you to enter a configuration set name',
            'domain.required_if' => 'The Mailgun provider requires you to enter a domain'
        ];
    }
}
