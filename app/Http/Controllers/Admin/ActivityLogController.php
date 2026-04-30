<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $event = $request->query('event');
        $userId = $request->query('user_id');

        $logs = ActivityLog::query()
            ->with('user')
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('subject_type', 'like', "%{$search}%");
            })
            ->when($event, function ($query, $event) {
                $query->where('event', $event);
            })
            ->when($userId, function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.admin.activity-logs.index', [
            'logs' => $logs,
            'events' => ActivityLog::query()
                ->select('event')
                ->distinct()
                ->orderBy('event')
                ->pluck('event'),
            'users' => User::orderBy('name')->get(),
            'search' => $search,
            'event' => $event,
            'userId' => $userId,
        ]);
    }
}