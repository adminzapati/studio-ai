<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedPrompt extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'prompt',
        'category',
        'is_favorite',
        'image_path',
        'method',
        'wizard_data',
    ];

    protected $casts = [
        'wizard_data' => 'array',
        'is_favorite' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
