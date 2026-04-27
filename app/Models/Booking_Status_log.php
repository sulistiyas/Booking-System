<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking_Status_log extends Model
{
    protected $fillable = [
        'booking_id',
        'changed_by',
        'from_status',
        'to_status',
        'reason',
        'snapshot',
    ];

    protected function casts(): array
    {
        return [
            'snapshot' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
