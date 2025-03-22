<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSettingRequest extends FormRequest
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
            'time_update_service' => 'required|date_format:H:i:s',
            'time_update_order' => 'required|date_format:H:i:s',
            'time_deny_order' => 'required|date_format:H:i:s',
            'time_exchange_rate' => 'required|date_format:H:i:s',
            'account_no' => 'required|string|max:255',
            'phone' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'zalo' => 'nullable|string|max:255',
            'bank_id' => 'nullable|exists:banks,id'
        ];
    }
}
