<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quote extends Model
{
    use HasFactory;

    protected $table = 'quotes';

    protected $fillable = [
        'car_id',
        'glass_type_id',
        'vendor_glass_price_id',
        'final_price',
        'requested_at'
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function glassType()
    {
        return $this->belongsTo(GlassType::class);
    }

    public function vendorGlassPrice()
    {
        return $this->belongsTo(VendorGlassPrice::class);
    }

}
