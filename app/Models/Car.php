<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'make',
        'model',
        'year',
        'car_body_type_id'
    ];

    public function carBodyType()
    {
        return $this->belongsTo(CarBodyType::class);
    }

    public function quotes(){
        return $this->hasMany(Quote::class);
    }
}
