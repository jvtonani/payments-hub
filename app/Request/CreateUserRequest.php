<?php


namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

/**
 * @codeCoverageIgnore
 */
class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'document' => 'required|string',
            'user_type' => 'required|string|in:common,merchant',
            'password' => 'required|string',
            'name' => 'required|string',
            'person_type' => 'string|in:pf,pj',
            'document_type' => 'string|in:cpf,cnpj',
            'cellphone' => 'required|string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}