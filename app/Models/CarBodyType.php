<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Car body type (e.g. Sedan, SUV, Hatchback) used when resolving car selection from the API
 */
class CarBodyType extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'car_body_types';

    /** @var array<int, string> */
    protected $fillable = ['name'];

    /**
     * Cars that have this body type
     *
     * @return HasMany<Car, $this>
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
