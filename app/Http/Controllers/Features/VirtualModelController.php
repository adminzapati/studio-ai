<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VirtualModelController extends Controller
{
    public function index()
    {
        return view('features.virtual-model.index');
    }
}
