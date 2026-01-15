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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? \Illuminate\Support\Facades\Storage::url($this->image_path) : null;
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
