<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_Unavailabilities extends Model
{
    protected $fillable = [
        'resource_id',
        'reason',
        'start_datetime',
        'end_datetime',
        'is_recurring',
        'recurrence_rule',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'is_recurring' => 'boolean',
        ];
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
