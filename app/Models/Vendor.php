<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'name'
    ];

    public function vendorGlassPrices()
    {
        return $this->hasMany(VendorGlassPrice::class);
    }
}
