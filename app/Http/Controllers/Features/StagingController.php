<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StagingController extends Controller
{
    public function index()
    {
        return view('features.staging.index');
    }
}
