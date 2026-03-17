<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Quote request is created when the user selects a vendor and submits the quote
 */
class Quote extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'quotes';

    /** @var array<int, string> */
    protected $fillable = [
        'car_id',
        'glass_type_id',
        'vendor_glass_price_id',
        'final_price',
        'requested_at',
    ];

    /**
     * Car this quote is for
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Glass type to be replaced (e.g. Front Windshield)
     */
    public function glassType(): BelongsTo
    {
        return $this->belongsTo(GlassType::class);
    }

    /**
     * Selected vendor option (price, warranty, delivery) used for this quote
     */
    public function vendorGlassPrice(): BelongsTo
    {
        return $this->belongsTo(VendorGlassPrice::class);
    }
}
