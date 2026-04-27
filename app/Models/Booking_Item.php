<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking_Item extends Model
{
    protected $table = 'booking_items';
    protected $fillable = [
        'booking_id',
        'resource_id',
        'start_datetime',
        'end_datetime',
        'quantity',
        'unit',
        'unit_price',
        'subtotal',
        'notes',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'meta' => 'array',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
