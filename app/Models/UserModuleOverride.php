<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserModuleOverride extends Model
{
    protected $fillable = [
        'user_id',
        'feature_module_id',
        'is_enabled',
        'reason',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(FeatureModule::class, 'feature_module_id');
    }
}
