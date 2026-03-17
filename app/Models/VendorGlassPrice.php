<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** 
 * Used to show 3-4 options per glass and to store the chosen option on a Quote
 */
class VendorGlassPrice extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'vendors_glass_prices';

    /** @var array<int, string> */
    protected $fillable = [
        'vendor_id',
        'glass_type_id',
        'price',
        'warranty_time',
        'delivery_time',
    ];

    /**
     * Vendor that offers this price
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Glass type this price applies to
     */
    public function glassType(): BelongsTo
    {
        return $this->belongsTo(GlassType::class);
    }

    /**
    * Quote requests that selected this vendor option
     *
     * @return HasMany<Quote, $this>
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
