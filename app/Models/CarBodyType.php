<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarBodyType extends Model
{
    use HasFactory;

    protected $table = 'car_body_types';

    protected $fillable = [
        'name'
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
