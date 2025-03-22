<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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

    public function failedValidation(Validator $validator)
    {
        Log::error('Validation failed', [
            'errors' => $validator->errors(),
            'request_data' => $this->all(),
        ]);

        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
