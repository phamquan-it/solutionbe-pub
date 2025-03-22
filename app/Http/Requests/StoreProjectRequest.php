<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;


class StoreProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',  // Name of the project is required, must be a string and max length of 255 characters
            'price' => 'required',  // Price is required and must be numeric defined statuses
            'duration' => 'required|date',  // Duration is required and must be an integer
            'category_id' => 'required|exists:i_t_categories,id',  // Category ID must exist in the categories table
            // // Validation for files array
            'files' => 'array',  // files must be an array
            'files.*.file' => 'required|string',  // Each file must have a valid URL
            // // Optional validation for technologies (if present)
            'technologies' => 'nullable|array',  // technologies must be an array
            'technologies.*' => 'integer|exists:technologies,id',  // Each technology ID must be valid and exist in the technologies table
        ];
    }

    // /**
    //  * Customize the failed validation response.
    //  */
    protected function failedValidation(Validator $validator)
    {
        // Log validation errors
        Log::error('Validation failed for StoreProjectRequest', [
            'errors' => $validator->errors(),
            'request_data' => $this->all()
        ]);

        // Return a JSON response with validation errors
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
