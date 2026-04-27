<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_number',
        'user_id',
        'title',
        'notes',
        'status',
        'rejection_reason',
        'cancellation_reason',
        'start_datetime',
        'end_datetime',
        'total_price',
        'payment_status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'total_price' => 'decimal:2',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'meta' => 'array',
            'status' => 'string',
            'payment_status' => 'string',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function items()
    {
        return $this->hasMany(Booking_Item::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(Booking_Status_log::class);
    }
}
