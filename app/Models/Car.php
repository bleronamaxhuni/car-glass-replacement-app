<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Car model is created when the user requests vendor options so quotes can reference a concrete car record.
 */
class Car extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'cars';

    /** @var array<int, string> */
    protected $fillable = [
        'make',
        'model',
        'year',
        'car_body_type_id',
    ];

    /**
     * Body type of this car (e.g. Sedan, SUV).
     */
    public function carBodyType(): BelongsTo
    {
        return $this->belongsTo(CarBodyType::class);
    }

    /**
     * Quote requests associated with this car.
     *
     * @return HasMany<Quote, $this>
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
