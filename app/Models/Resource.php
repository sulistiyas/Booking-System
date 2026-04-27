<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'resource_type_id',
        'name',
        'code',
        'description',
        'location',
        'capacity',
        'price_per_unit',
        'price_unit',
        'image',
        'meta',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'price_per_unit' => 'decimal:2',
            'meta' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function resourceType()
    {
        return $this->belongsTo(Resource_Type::class);
    }

    public function unavailabilities()
    {
        return $this->hasMany(Resource_Unavailabilities::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(Booking_Item::class);
    }
}
