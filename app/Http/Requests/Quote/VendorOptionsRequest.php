<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate input for fetching vendor options (car selection + glass type).
 */
class VendorOptionsRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'make' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:1900', 'max:2100'],
            'body_type' => ['required', 'string', 'max:255'],
            'glass_type_id' => ['required', 'integer', 'exists:glass_types,id'],
        ];
    }
}
