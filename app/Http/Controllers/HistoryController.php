<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        // TODO: Fetch user's processing history from batches/generations
        $history = collect(); // Placeholder
        return view('history.index', compact('history'));
    }
}
