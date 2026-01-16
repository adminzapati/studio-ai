<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureModule extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon_path',
        'route_name',
        'is_enabled',
        'sort_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
