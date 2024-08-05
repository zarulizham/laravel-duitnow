<?php

namespace ZarulIzham\DuitNowPayment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ZarulIzham\DuitNowPayment\Messages\AuthorizationConfirmation as AuthorizationConfirmationMessage;

class AuthorizationConfirmation extends FormRequest
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
        return [];
    }

    /**
     * Presist the data to the users table
     */
    public function handle($type = 'webhook')
    {
        $data = $this->all();
        $data['type'] = $type;

        return (new AuthorizationConfirmationMessage)->handle($data);
    }
}
