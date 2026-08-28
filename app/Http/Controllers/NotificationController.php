<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function open(Request $request, string $notification)
    {
        $user = $request->user();

        $notification = $user
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $assessmentId =
            $notification->data['assessment_id'] ?? null;

        abort_unless($assessmentId, 404);

        /*
         * Mark this notification as read.
         */
        $notification->markAsRead();

        /*
         * Consultant assignment notification.
         */
       $type = $notification->data['type'] ?? null;

if ($type === 'transformation_assigned') {
    return redirect()->route(
        'consultant.assessments.review',
        $assessmentId
    );
}

if ($type === 'transformation_paid') {
    return redirect()->route(
        'admin.transformations.index'
    );
}

abort(404);
    }
}