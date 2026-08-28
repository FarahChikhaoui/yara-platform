<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
   public function index(Request $request)
{
    $logs = AuditLog::with('user')
        ->when($request->filled('search'), function ($query) use ($request) {
            $search = trim($request->search);

            $query->where(function ($query) use ($search) {
                $query->where('action', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('entity_type', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        })

        ->when(
            $request->filled('action'),
            fn ($query) => $query->where('action', $request->action)
        )

        ->when(
            $request->filled('date_from'),
            fn ($query) => $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            )
        )

        ->when(
            $request->filled('date_to'),
            fn ($query) => $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            )
        )

        ->latest()
        ->paginate(25)
        ->withQueryString();

    $actions = AuditLog::query()
        ->select('action')
        ->distinct()
        ->orderBy('action')
        ->pluck('action');

    return view(
        'admin.audit-logs.index',
        compact('logs', 'actions')
    );
}
}