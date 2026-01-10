<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromptOption extends Model
{
    protected $fillable = ['step', 'category', 'label', 'value', 'icon'];
}
