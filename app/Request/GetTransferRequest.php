<?php

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

/**
 * @codeCoverageIgnore
 */
class GetTransferRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer|min:1',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
