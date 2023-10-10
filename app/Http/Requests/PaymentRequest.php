<?php

namespace App\Http\Requests;

use App\Enum\Payment\PaymentMethod;
use App\Enum\Payment\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'amount' => ['required', 'numeric'],
            'currency' => ['required'],
        ];
    }
}
