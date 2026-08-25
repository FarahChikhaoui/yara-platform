<?php

namespace App\Http\Controllers;

use App\Models\Assessment;

class AdminAssessmentController extends Controller
{
    public function index()
    {
$assessments = Assessment::with(['company', 'user'])            ->latest()
            ->get();

        return view('admin.assessments.index', compact('assessments'));
    }
}