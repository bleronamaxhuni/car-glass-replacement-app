<?php

namespace App\Services;

use App\Models\Car;
use App\Models\CarBodyType;
use App\Models\GlassType;
use App\Models\Quote;
use App\Models\VendorGlassPrice;
use Illuminate\Support\Collection;

/**
 * Handles quote flow business logic: resolving car and vendor options, creating quotes.
 */
class QuoteService
{
    /** Maximum number of vendor options to return per glass type. */
    private const VENDOR_OPTIONS_LIMIT = 4;

    /**
     * Resolve car from selection, fetch glass type and vendor options.
     *
     * @param array{make: string, model: string, year: int, body_type: string, glass_type_id: int} $input
     * @return array{car: Car, glassType: GlassType, vendorOptions: Collection}|array{error: string} On failure returns ['error' => 'body_type'|'no_vendors'].
     */
    public function getVendorOptions(array $input): array
    {
        $bodyType = CarBodyType::where('name', $input['body_type'])->first();

        if (! $bodyType) {
            return ['error' => 'body_type'];
        }

        $car = Car::firstOrCreate([
            'make' => $input['make'],
            'model' => $input['model'],
            'year' => (string) $input['year'],
            'car_body_type_id' => $bodyType->id,
        ]);

        $glassType = GlassType::findOrFail($input['glass_type_id']);

        $vendorOptions = VendorGlassPrice::with('vendor')
            ->where('glass_type_id', $glassType->id)
            ->inRandomOrder()
            ->take(self::VENDOR_OPTIONS_LIMIT)
            ->get();

        if ($vendorOptions->isEmpty()) {
            return ['error' => 'no_vendors'];
        }

        return [
            'car' => $car,
            'glassType' => $glassType,
            'vendorOptions' => $vendorOptions,
        ];
    }

    /**
     * Create a quote for the selected car, glass type and vendor option.
     *
     * @param array{car_id: int, glass_type_id: int, vendor_glass_price_id: int} $input
     * @return Quote
     */
    public function createQuote(array $input): Quote
    {
        $vendorPrice = VendorGlassPrice::with('vendor', 'glassType')->findOrFail($input['vendor_glass_price_id']);

        $quote = Quote::create([
            'car_id' => $input['car_id'],
            'glass_type_id' => $input['glass_type_id'],
            'vendor_glass_price_id' => $vendorPrice->id,
            'final_price' => $vendorPrice->price,
        ]);

        return $quote->load('car.carBodyType', 'glassType', 'vendorGlassPrice.vendor');
    }
}
