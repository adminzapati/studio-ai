<?php

namespace App\Http\Controllers\Features;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BeautifierController extends Controller
{
    public function index()
    {
        return view('features.beautifier.index');
    }
}
