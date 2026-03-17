<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlassType extends Model
{
    use HasFactory;

    protected $table = 'glass_types';

    protected $fillable = [
        'name'
    ];

    public function vendorGlassPrices()
    {
        return $this->hasMany(VendorGlassPrice::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
