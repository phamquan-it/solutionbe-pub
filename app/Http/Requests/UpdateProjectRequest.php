<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'name'        => 'sometimes|string|max:255',
            'price'       => 'sometimes|numeric|min:0',
            'description' => 'sometimes|nullable|string',
            'status'      => 'sometimes|in:initalize,pending,in_progress,completed,canceled',
            'duration'    => 'sometimes|date',
            'category_id' => 'sometimes|exists:categories,id',
            'service_id'  => 'sometimes|nullable|exists:services,id',
            'user_id'     => 'sometimes|exists:users,id|uuid',
        ];
    }
}
