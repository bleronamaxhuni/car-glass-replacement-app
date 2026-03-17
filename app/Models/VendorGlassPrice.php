<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorGlassPrice extends Model
{
    use HasFactory;

    protected $table = 'vendors_glass_prices';

    protected $fillable = [
        'vendor_id',
        'glass_type_id',
        'price',
        'warranty_time',
        'delivery_time'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function glassType()
    {
        return $this->belongsTo(GlassType::class);
    }
    
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}
