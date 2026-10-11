<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminLogController extends Controller
{
    // BITOIN - Display activity logs and allow filtering
    public function index(Request $request): View
    {
        // Get activity logs, starting with the latest
        $query = ActivityLog::latest();

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(15)->withQueryString();

        // Get unique modules and actions for the filter dropdowns
        $modules = ActivityLog::select('module')->distinct()->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        // Send the logs and filter options to the admin logs page
        return view('admin.logs', compact('logs', 'modules', 'actions'));
    }
}
