<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Display the guide page.
     */
    public function index()
    {
        return view('guide.index');
    }
}
