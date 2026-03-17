<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validate input for storing a quote (selected car, glass and vendor option).
 */
class StoreQuoteRequest extends FormRequest
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
            'car_id' => ['required', 'integer', 'exists:cars,id'],
            'glass_type_id' => ['required', 'integer', 'exists:glass_types,id'],
            'vendor_glass_price_id' => ['required', 'integer', 'exists:vendors_glass_prices,id'],
        ];
    }
}
