<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogController extends Controller
{
    /**
     * Display a paginated listing of activity logs with optional filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::latest();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(15)->withQueryString();

        // Get distinct modules and actions for filter dropdowns
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        return view('admin.logs', compact('logs', 'modules', 'actions'));
    }
}
