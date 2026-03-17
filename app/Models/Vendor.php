<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Vendor offering glass replacement
*/
class Vendor extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'vendors';

    /** @var array<int, string> */
    protected $fillable = ['name'];

    /**
     * Price entries for each glass type this vendor offers
     *
     * @return HasMany<VendorGlassPrice, $this> 
     */
    public function vendorGlassPrices(): HasMany
    {
        return $this->hasMany(VendorGlassPrice::class);
    }
}
