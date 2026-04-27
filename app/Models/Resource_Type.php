<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource_Type extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'icon',
        'color',
        'requires_approval',
        'allow_overlap',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'requires_approval' => 'boolean',
            'allow_overlap' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
