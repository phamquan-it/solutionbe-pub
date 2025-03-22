<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRateRequest extends FormRequest
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
            'post_id' => ['required', 'exists:posts,id'], // Must be a valid post ID
            'like' => ['required', 'in:STAR,LIKE'], // Only accept 'STAR' or 'LIKE'
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.required' => 'The post ID is required.',
            'post_id.exists' => 'The specified post does not exist.',
            'like.required' => 'The like field is required.',
            'like.in' => 'The like value must be either STAR or LIKE.',
        ];
    }
}
