<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities (Admin).
     */
    public function index()
    {
        $activities = ActivityLog::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.activity.index', compact('activities'));
    }
}
