<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Type of car glass that can be replaced (e.g. Front Windshield, Rear Left Door Glass)
 */
class GlassType extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'glass_types';

    /** @var array<int, string> */
    protected $fillable = ['name'];

    /**
     * Vendor price entries for this glass type (one per vendor)
     *
     * @return HasMany<VendorGlassPrice, $this>
     */
    public function vendorGlassPrices(): HasMany
    {
        return $this->hasMany(VendorGlassPrice::class);
    }

    /**
     * Quote requests that selected this glass type
     *
     * @return HasMany<Quote, $this>
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
