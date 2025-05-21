<?php

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

/**
 * @codeCoverageIgnore
 */
class CreateTransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'value' => 'required|numeric|min:0.01',
            'payer' => 'required|integer|min:1',
            'payee' => 'required|integer|min:1|different:payer',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
