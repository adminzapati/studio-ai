<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // Admin sees all, User sees own
        $query = ActivityLog::with('user');
        
        if (!auth()->user()->hasRole('Admin')) {
            $query->forUser(auth()->id());
        }

        // Search filter
        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        // Module filter
        if ($request->filled('module')) {
            $query->byModule($request->input('module'));
        }

        // Action type filter
        if ($request->filled('action')) {
            $query->byAction($request->input('action'));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->input('date_to') . ' 23:59:59');
        }

        $activities = $query->latest()->paginate(15)->withQueryString();

        // Get available modules and actions for filters
        // For Admin, show all available modules/actions in DB
        $filterQuery = auth()->user()->hasRole('Admin') ? ActivityLog::query() : ActivityLog::forUser(auth()->id());

        $modules = $filterQuery->clone()
            ->select('module')
            ->distinct()
            ->pluck('module');

        $actions = $filterQuery->clone()
            ->select('action_type')
            ->distinct()
            ->pluck('action_type');

        return view('history.index', compact('activities', 'modules', 'actions'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        // Delete thumbnail if exists
        if ($activityLog->thumbnail_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($activityLog->thumbnail_path);
        }

        $activityLog->delete();

        return back()->with('success', 'Activity deleted successfully.');
    }
}
