<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmPaymentRequest extends FormRequest
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
            'gateway' => 'required|string|max:50',
            'transactionDate' => 'required|date_format:Y-m-d H:i:s',
            'accountNumber' => 'required|string|regex:/^\d+$/|max:20',
            'subAccount' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:50',
            'content' => 'required|string|max:255',
            'transferType' => 'required|in:in,out',
            'description' => 'nullable|string|max:255',
            'transferAmount' => 'required|numeric|min:1',
            'referenceCode' => 'required|string|max:50',
            'accumulated' => 'required|numeric|min:0',
            'id' => 'required|integer|min:1',
        ];
    }
}
