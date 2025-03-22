<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
            'gateway' => 'required|string',
            'transaction_date' => 'required|date',
            'account_number' => 'required|string',
            'amount_in' => 'nullable|numeric',
            'amount_out' => 'nullable|numeric',
            'accumulated' => 'nullable|numeric',
            'code' => 'nullable|string',
            'transaction_content' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'body' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
        ];
    }
}
