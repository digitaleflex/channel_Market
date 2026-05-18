<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Deployment;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities and deployments (Admin).
     */
    public function index()
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->paginate(10, ['*'], 'activities_page');

        $deployments = Deployment::latest()
            ->paginate(10, ['*'], 'deployments_page');

        return view('admin.activity.index', compact('activities', 'deployments'));
    }
}
