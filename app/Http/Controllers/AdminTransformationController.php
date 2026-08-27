<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\User;

class AdminTransformationController extends Controller
{
    public function index()
    {
        /*
         * Paid Transformation requests only.
         */
        $requests = Assessment::with([
            'company',
            'user',
            'assignedConsultant',
            'transformationRoadmap',
        ])
            ->where('engagement_type', 'transformation')
            ->where('payment_status', 'paid')
            ->whereIn('transformation_status', [
                'submitted',
                'in_review',
                'roadmap_ready',
            ])
            ->latest('updated_at')
            ->get();
/*
 * Transformation dashboard KPIs.
 *
 * Assignment and workflow status are separate concepts:
 * - Unassigned = no consultant assigned and roadmap not finalized
 * - Assigned = consultant assigned and roadmap not finalized
 * - In Review = consultant work is currently in progress
 * - Ready = final roadmap has been delivered
 */

$unassignedCount = $requests
    ->filter(function ($assessment) {
        return $assessment->assignedConsultant === null
            && $assessment->transformation_status !== 'roadmap_ready';
    })
    ->count();

$assignedCount = $requests
    ->filter(function ($assessment) {
        return $assessment->assignedConsultant !== null
            && $assessment->transformation_status !== 'roadmap_ready';
    })
    ->count();
$inReviewCount = $requests
    ->where('transformation_status', 'in_review')
    ->count();

$readyCount = $requests
    ->where('transformation_status', 'roadmap_ready')
    ->count();


/*
 * Consultants available for assignment.
 */
$consultants = User::where('role', 'consultant')
    ->orderBy('name')
    ->get();


return view(
    'admin.transformations.index',
    compact(
        'requests',
        'consultants',
        'unassignedCount',
        'assignedCount',
        'inReviewCount',
        'readyCount'
    )
);
    }
    public function assign(
    \Illuminate\Http\Request $request,
    Assessment $assessment
) {
    /*
     * Only paid Transformation requests can be assigned.
     */
    abort_unless(
        $assessment->engagement_type === 'transformation'
        && $assessment->payment_status === 'paid',
        404
    );

    /*
     * A delivered roadmap is final.
     * Consultant assignment can no longer be changed.
     */
    abort_if(
        $assessment->transformation_status === 'roadmap_ready',
        409,
        'A finalized Transformation request cannot be reassigned.'
    );

    $validated = $request->validate([
        'consultant_id' => [
            'required',
            'integer',
            'exists:users,id',
        ],
    ]);

    /*
     * Make sure the selected user is actually a consultant.
     */
    $consultant = User::where('id', $validated['consultant_id'])
        ->where('role', 'consultant')
        ->firstOrFail();

    /*
     * Save assignment on the Transformation request.
     */
    $assessment->update([
        'assigned_consultant_id' => $consultant->id,
    ]);

    return redirect()
        ->route('admin.transformations.index')
        ->with(
            'success',
            'Consultant assigned successfully.'
        );
}
}